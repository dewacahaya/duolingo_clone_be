<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\QuizSession;
use App\Models\Unit;
use App\Models\UserProgress;
use App\Models\UserWrongAnswer;
use App\Services\GeminiService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{

    protected $passingGrade = 70;
    /**
     * Mengambil 10 soal acak untuk kuis
     */
    public function start($unit_id, Request $request)
    {
        $user = $request->user();
        $maxQuestions = 10;

        if ($user->energy <= 0) {
            return response()->json(['message' => 'Nyawa habis! Tunggu regenerasi.'], 403);
        }

        $user->decrement('energy');

        $remedialQuestions = Question::where('unit_id', $unit_id)
            ->whereHas('wrongAnswers', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('is_mastered', false);
            })
            ->inRandomOrder()
            ->limit($maxQuestions)
            ->get();

        $slotsLeft = $maxQuestions - $remedialQuestions->count();

        $finalQuestions = $remedialQuestions;

        if ($slotsLeft > 0) {
            $newQuestions = Question::where('unit_id', $unit_id)
                ->whereNotIn('id', $remedialQuestions->pluck('id'))
                ->inRandomOrder()
                ->limit($slotsLeft)
                ->get();

            $finalQuestions = $finalQuestions->merge($newQuestions);
        }

        if ($finalQuestions->isEmpty()) {
            return response()->json(['message' => 'Soal belum tersedia untuk unit ini.'], 404);
        }

        return response()->json([
            'unit_id' => $unit_id,
            'is_remedial_mode' => $remedialQuestions->count() > 0,
            'remedial_count' => $remedialQuestions->count(),
            'questions' => $finalQuestions
        ]);
    }

    /**
     * Submit hasil kuis & Generate AI Feedback
     */
    public function submit(Request $request, GeminiService $gemini)
    {

        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.selected' => 'required'
        ]);

        $user = $request->user();
        $unit = Unit::find($request->unit_id);

        $correctCount = 0;
        $totalQuestions = count($request->answers);
        $wrongQuestionsDetails = [];

        DB::beginTransaction();
        try {
            foreach ($request->answers as $ans) {
                $question = Question::find($ans['question_id']);
                $isCorrect = $ans['selected'] === $question->content['answer'];

                if ($isCorrect) {
                    $correctCount++;
                    UserWrongAnswer::where('user_id', $user->id)
                        ->where('question_id', $question->id)
                        ->update(['is_mastered' => true]);
                } else {
                    $wrongEntry = UserWrongAnswer::firstOrNew([
                        'user_id' => $user->id,
                        'question_id' => $question->id
                    ]);
                    $wrongEntry->wrong_count += 1;
                    $wrongEntry->is_mastered = false;
                    $wrongEntry->save();

                    $wrongQuestionsDetails[] = [
                        'question' => $question->content['question'],
                        'user_answer' => $ans['selected'],
                        'correct_answer' => $question->content['answer']
                    ];
                }
            }
            $score = ($totalQuestions > 0) ? round(($correctCount / $totalQuestions) * 100) : 0;
            $isPassed = $score >= $this->passingGrade;

            // if (!$isPassed) {
            //     $user->decrement('energy');
            // }

            $xpGained = $score;
            $user->increment('xp_total', $xpGained);
            // Streak logic

            $progress = UserProgress::firstOrCreate(
                ['user_id' => $user->id, 'unit_id' => $unit->id],
                ['status' => 'open', 'stars' => 0, 'high_score' => 0]
            );

            if ($score > $progress->high_score) {
                $progress->high_score = $score;
            }

            $stars = $score == 100 ? 3 : ($score >= 80 ? 2 : ($score >= 60 ? 1 : 0));
            if ($stars > $progress->stars) {
                $progress->stars = $stars;
            }

            $unlockedUnitId = null;
            if ($isPassed) {
                $progress->status = 'completed';

                // Cari unit selanjutnya
                $nextUnit = Unit::where('chapter_id', $unit->chapter_id)
                    ->where('order_sequence', '>', $unit->order_sequence)
                    ->orderBy('order_sequence', 'asc')
                    ->first();

                if ($nextUnit) {
                    // Buka next unit
                    UserProgress::firstOrCreate(
                        ['user_id' => $user->id, 'unit_id' => $nextUnit->id],
                        ['status' => 'open', 'stars' => 0]
                    );
                    $unlockedUnitId = $nextUnit->id;
                }
            }
            $progress->save();

            $aiFeedback = null;
            if (!empty($wrongQuestionsDetails)) {
                // Panggil GeminiService (Pastikan method generateFeedback sudah siap)
                // Kita jalankan via Job/Queue nanti biar gak loading lama.
                // Untuk sekarang direct dulu.
                $aiFeedbackRaw = $gemini->generateFeedback($wrongQuestionsDetails);
                $aiFeedback = $aiFeedbackRaw['ai_feedback_summary'] ?? 'Teruslah berlatih!';
            }

            QuizSession::create([
                'user_id' => $user->id,
                'unit_id' => $unit->id,
                'score' => $score,
                'correct_count' => $correctCount,
                'ai_feedback_summary' => $aiFeedback
            ]);

            DB::commit();

            return response()->json([
                'score' => $score,
                'is_passed' => $isPassed,
                'xp_gained' => $xpGained,
                'energy_left' => $user->fresh()->energy,
                'unlocked_unit_id' => $unlockedUnitId,
                'ai_feedback_summary' => $aiFeedback
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    // private function updateStreak($user)
    // {
    //     $today = Carbon::today();
    //     $lastStudy = $user->last_study_at ? Carbon::parse($user->last_study_at)->startOfDay() : null;

    //     if (!$lastStudy) {
    //         $user->streak = 1;
    //     } elseif ($lastStudy->isYesterday()) {
    //         $user->streak += 1;
    //     } elseif (!$lastStudy->isToday()) {
    //         $user->streak = 1;
    //     }

    //     $user->last_study_at = now();
    // }

    // private function unlockNextUnit($userId, $currentUnitId)
    // {
    //     UserProgress::updateOrCreate(
    //         ['user_id' => $userId, 'unit_id' => $currentUnitId],
    //         ['status' => 'completed']
    //     );

    //     $currentUnit = Unit::find($currentUnitId);
    //     $nextUnit = Unit::where('chapter_id', $currentUnit->chapter_id)
    //         ->where('order_sequence', '>', $currentUnit->order_sequence)
    //         ->orderBy('order_sequence', 'asc')
    //         ->first();

    //     if ($nextUnit) {
    //         UserProgress::updateOrCreate(
    //             ['user_id' => $userId, 'unit_id' => $nextUnit->id],
    //             ['status' => 'in_progress']
    //         );
    //     }
    // }
}
