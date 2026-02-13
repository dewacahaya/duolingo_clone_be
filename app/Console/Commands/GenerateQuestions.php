<?php

namespace App\Console\Commands;

use App\Models\Question;
use App\Models\Unit;
use App\Services\GeminiService;
use Illuminate\Console\Command;

class GenerateQuestions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-content {unit_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate materi (Guide) dan soal kuis menggunakan Gemini AI';

    /**
     * Execute the console command.
     */
    public function handle(GeminiService $gemini)
    {
        $unitId = $this->argument('unit_id');
        $units = $unitId ? Unit::where('id', $unitId)->get() : Unit::all();

        $this->info("🚀 Menemukan " . $units->count() . " unit untuk diproses.");

        foreach ($units as $unit) {
            $this->info("\n📘 Memproses Unit: {$unit->name} ({$unit->topic_keyword})...");

            $difficulty = 'easy';
            if ($unit->order_sequence > 3)
                $difficulty = 'medium';
            if ($unit->order_sequence > 7)
                $difficulty = 'hard';
            $this->info("   🎚️  Target Difficulty: " . strtoupper($difficulty));

            if (empty($unit->guide_md)) {
                $this->info("   ✍️  Materi kosong. Sedang meminta Gemini menulis materi...");
                $guide = $gemini->generateLessonGuide($unit->topic_keyword);

                if ($guide) {
                    $unit->guide_md = $guide;
                    $unit->save();
                    $this->info("   ✅ Materi berhasil disimpan!");
                } else {
                    $this->error("   ❌ Gagal menulis materi (API Error/Empty).");
                }
            } else {
                $this->warn("   ⏩ Materi sudah ada. Skip.");
            }

            if ($unit->questions()->count() < 10) {
                $this->info("   🧠  Soal kurang. Sedang generate soal kuis...");

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
                            $this->error("   ❌ Gagal menyimpan soal: " . $e->getMessage());
                        }
                    }
                    $this->info("   ✅ Berhasil menyimpan {$count} soal baru.");
                } else {
                    $this->error("   ❌ Gagal generate soal (Format JSON invalid/API Error).");
                }
            } else {
                $this->warn("   ⏩ Soal sudah cukup. Skip.");
            }

            $this->info("   ⏳ Cooldown 30 detik...");
            sleep(30);
        }

        $this->info("\n🎉 Selesai! Semua konten siap digunakan.");
    }
}
