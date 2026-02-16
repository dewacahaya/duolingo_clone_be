<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\QuizSession;
use App\Models\Unit;
use App\Models\UserProgress;
use App\Models\UserWrongAnswer;
use App\Services\GeminiService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        $userService = app(UserService::class);
        $userService->getUserProfile($user);

        $user->refresh();

        if ($user->energy < 1) {
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
            $user->increment('energy');
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
                        'correct_answer' => $question->content['answer'],
                        'explanation' => $question->content['explanation'] ?? 'Tidak ada penjelasan khusus.'
                    ];
                }
            }

            $score = ($totalQuestions > 0) ? round(($correctCount / $totalQuestions) * 100) : 0;
            $isPassed = $score >= $this->passingGrade;
            $xpGained = $score;

            $now = Carbon::now();
            $lastStudy = $user->last_study_at ? Carbon::parse($user->last_study_at) : null;

            if (!$lastStudy) {
                $user->streak = 1;
            } elseif ($lastStudy->isYesterday()) {
                $user->streak += 1;
            } elseif (!$lastStudy->isToday()) {
                $user->streak = 1;
            }

            $user->last_study_at = $now;
            $user->xp_total += $xpGained;
            $user->save();

            $progress = UserProgress::firstOrCreate(
                ['user_id' => $user->id, 'unit_id' => $unit->id],
                [
                    'is_locked' => false,
                    'is_completed' => false,
                    'current_level' => 0
                ]
            );

            $progress->current_level += 1;

            $unlockedUnitId = null;
            if ($isPassed) {
                $progress->is_completed = true;

                // Cari unit selanjutnya
                $nextUnit = Unit::where('order_sequence', '>', $unit->order_sequence)
                    ->orderBy('order_sequence', 'asc')
                    ->first();

                if ($nextUnit) {
                    UserProgress::firstOrCreate(
                        ['user_id' => $user->id, 'unit_id' => $nextUnit->id],
                        [
                            'is_locked' => 0,
                            'is_completed' => 0,
                            'current_level' => 0
                        ]
                    );
                    $unlockedUnitId = $nextUnit->id;
                }
            }
            $progress->save();

            $aiFeedback = null;
            if (!empty($wrongQuestionsDetails)) {
                $aiFeedback = $gemini->generateFeedback($wrongQuestionsDetails);
            } else {
                $aiFeedback = "Luar biasa! Kamu menjawab semua pertanyaan dengan sempurna. Pertahankan kerjamu!";
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
                'energy_left' => $user->energy,
                'unlocked_unit_id' => $unlockedUnitId,
                'ai_feedback_summary' => $aiFeedback
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    // public function submit(Request $request, GeminiService $gemini)
    // {

    //     $request->validate([
    //         'unit_id' => 'required|exists:units,id',
    //         'answers' => 'required|array',
    //         'answers.*.question_id' => 'required|exists:questions,id',
    //         'answers.*.selected' => 'required'
    //     ]);

    //     $user = $request->user();
    //     $unitId = $request->unit_id;
    //     $answers = $request->answers;

    //     $correctCount = 0;
    //     $totalQuestions = count($request->answers);
    //     $wrongQuestionsDetails = [];


    //     foreach ($answers as $ans) {
    //         $question = Question::find($ans['question_id']);

    //         if ($question && $ans['selected'] === $question->content['answer']) {
    //             $correctCount++;
    //             UserWrongAnswer::where('user_id', $user->id)
    //                 ->where('question_id', $question->id)
    //                 ->update(['is_mastered' => true]);
    //         } else {
    //             UserWrongAnswer::updateOrCreate(
    //                 ['user_id' => $user->id, 'question_id' => $question->id],
    //                 ['is_mastered' => false]
    //             );

    //             $wrongDetails[] = [
    //                 'question' => $question->content['question'],
    //                 'user_answer' => $ans['selected'],
    //                 'correct_answer' => $question->content['answer'],
    //                 'explanation' => $question->content['explanation']
    //             ];
    //         }
    //     }

    //     $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;
    //     $isPassed = $score >= 70;
    //     $xpGained = $isPassed ? ($correctCount * 10) : 0;

    //     if ($isPassed) {
    //         $user->xp_total += $xpGained;
    //         $user->last_study_at = Carbon::now();
    //         $user->save();

    //         $currentUnit = Unit::find($unitId);
    //         $nextUnit = Unit::where('chapter_id', $currentUnit->chapter_id)
    //             ->where('order_sequence', '>', $currentUnit->order_sequence)
    //             ->orderBy('order_sequence', 'asc')
    //             ->first();

    //         UserProgress::updateOrCreate(
    //             ['user_id' => $user->id, 'unit_id' => $unitId],
    //             ['status' => 'completed', 'score' => $score]
    //         );

    //         if ($nextUnit) {
    //             UserProgress::firstOrCreate(
    //                 ['user_id' => $user->id, 'unit_id' => $nextUnit->id],
    //                 ['status' => 'open', 'score' => 0]
    //             );
    //         }
    //     }

    //     $aiFeedback = null;
    //     if (count($wrongDetails) > 0) {
    //         $gemini = app(GeminiService::class);
    //         $prompt = "Kamu adalah tutor bahasa Jepang. Muridmu baru saja salah menjawab kuis. Berikan 1 paragraf feedback singkat, ramah, dan menyemangati bahasa Indonesia tentang kesalahan ini. Fokus pada perbaikan konsepnya. Data kesalahan: " . json_encode($wrongDetails);

    //         // Pakai mode teks biasa, bukan JSON
    //         $aiFeedback = $gemini->askGemini($prompt, false);
    //     } else {
    //         $aiFeedback = "Sempurna! Kamu menjawab semua pertanyaan tanpa salah. Pertahankan kerjamu!";
    //     }

    //     return response()->json([
    //         'score' => $score,
    //         'is_passed' => $isPassed,
    //         'correct_count' => $correctCount,
    //         'xp_gained' => $xpGained,
    //         'ai_feedback' => $aiFeedback
    //     ]);

    // }

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
