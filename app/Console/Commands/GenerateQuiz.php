<?php

namespace App\Console\Commands;

use App\Models\Question;
use App\Models\Unit;
use App\Services\GeminiService;
use Illuminate\Console\Command;

class GenerateQuiz extends Command
{
    protected $signature = 'app:generate-quiz {unit_id?}';
    protected $description = 'Generate HANYA soal kuis (JSON)';

    public function handle(GeminiService $gemini)
    {
        $unitId = $this->argument('unit_id');
        $units = $unitId
            ? Unit::where('id', $unitId)->get()
            : Unit::all();

        $this->info("🚀 Memulai proses generate soal...");

        foreach ($units as $unit) {
            if ($unit->questions()->count() >= 10) {
                continue;
            }

            $this->info("\n🧠 Memproses Unit: {$unit->name}...");

            $difficulty = 'easy';
            if ($unit->order_sequence > 3)
                $difficulty = 'medium';
            if ($unit->order_sequence > 7)
                $difficulty = 'hard';

            $this->info("   🎚️  Target Difficulty: " . strtoupper($difficulty));

            $this->info("   🤖 Sedang request ke Gemini...");

            $response = $gemini->generateQuestions($unit->topic_keyword, 10, $difficulty);

            if (isset($response['questions']) && is_array($response['questions'])) {
                $count = 0;
                foreach ($response['questions'] as $q) {
                    try {
                        Question::create([
                            'unit_id' => $unit->id,
                            'type' => $q['type'] ?? 'multiple_choice',
                            'difficulty' => $q['difficulty'] ?? $difficulty,
                            'content' => $q['content'],
                            'is_ai_generated' => true
                        ]);
                        $count++;
                    } catch (\Exception $e) {
                        $this->error("   ❌ Gagal simpan DB: " . $e->getMessage());
                    }
                }
                $this->info("   ✅ Berhasil menyimpan {$count} soal.");
            } else {
                $this->error("   ❌ Gagal generate (Format Invalid/API Error).");
            }

            $this->info("   ⏳ Cooldown 15 detik...");
            sleep(15);
        }

        $this->info("\n🎉 Selesai generate kuis!");
    }
}
