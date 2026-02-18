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

/**
 * @group 🎮 Engine Kuis & Latihan
 * API untuk memulai kuis, memotong energy, dan menghitung skor kelulusan.
 * Termasuk di dalamnya adalah sistem Remedial otomatis dan Feedback AI.
 */
class QuizController extends Controller
{
    /**
     * Passing grade untuk lulus kuis (persentase).
     *
     * @var int
     */
    protected $passingGrade = 70;

    /**
     * Start Kuis (Ambil Soal)
     * * Endpoint ini digunakan saat user menekan tombol "Mulai Belajar" di suatu Unit.
     * Memanggil endpoint ini akan otomatis **MEMOTONG 1 ENERGY** milik user.
     * Jika ada soal yang sebelumnya salah dijawab oleh user (di unit ini), soal tersebut akan dimunculkan kembali (Remedial Mode).
     * * @authenticated
     * @urlParam unit_id integer required ID dari Unit yang akan dimainkan. Example: 5
     * * @response {
     * "unit_id": "5",
     * "is_remedial_mode": false,
     * "remedial_count": 0,
     * "questions": [
     * {
     * "id": 50,
     * "unit_id": 5,
     * "type": "multiple_choice",
     * "difficulty": "easy",
     * "content": {
     * "question": "Bentuk Katakana 'エ' (E) sangat mirip dengan huruf kapital alfabet apa?",
     * "options": ["I", "E", "T", "H"],
     * "answer": "I",
     * "explanation": "Secara visual, Katakana 'E' (エ) mirip dengan huruf 'I' kapital..."
     * }
     * }
     * ]
     * }
     * @response status=403 scenario="Energy Habis" {
     * "message": "Nyawa habis! Tunggu regenerasi."
     * }
     * @response status=404 scenario="Unit Kosong" {
     * "message": "Soal belum tersedia untuk unit ini."
     * }
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
     * Submit Jawaban Kuis
     * * Endpoint pamungkas untuk mengirim semua jawaban user.
     * Sistem akan menghitung skor, mengupdate XP dan Streak, membuka gembok unit selanjutnya jika lulus (skor >= 70), dan meminta Gemini AI untuk merangkum kesalahan user.
     * * @authenticated
     * @bodyParam unit_id integer required ID dari unit kuis yang baru diselesaikan. Example: 5
     * @bodyParam answers object[] required Array berisi kumpulan jawaban user.
     * @bodyParam answers[].question_id integer required ID soal. Example: 50
     * @bodyParam answers[].selected string required Teks jawaban yang dipilih user. Untuk tipe susun kata, gabungkan katanya. Example: "I"
     * * @response {
     * "score": 100,
     * "is_passed": true,
     * "xp_gained": 100,
     * "energy_left": 4,
     * "unlocked_unit_id": 6,
     * "ai_feedback_summary": "Luar biasa! Kamu menjawab semua pertanyaan dengan sempurna. Pertahankan kerjamu!"
     * }
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
}
