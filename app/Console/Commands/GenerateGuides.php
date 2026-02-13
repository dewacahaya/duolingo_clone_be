<?php

namespace App\Console\Commands;

use App\Models\Unit;
use App\Services\GeminiService;
use Illuminate\Console\Command;

class GenerateGuides extends Command
{
    protected $signature = 'app:generate-guides {unit_id?}';
    protected $description = 'Generate HANYA materi pelajaran (Markdown)';

    public function handle(GeminiService $gemini)
    {
        $unitId = $this->argument('unit_id');
        $units = $unitId
            ? Unit::where('id', $unitId)->get()
            : Unit::whereNull('guide_md')->orWhere('guide_md', '')->get();

        $this->info("🚀 Menemukan " . $units->count() . " unit tanpa materi.");

        foreach ($units as $unit) {
            $this->info("\n📘 Memproses Unit: {$unit->name} ({$unit->topic_keyword})...");

            if (empty($unit->guide_md)) {
                $this->info("   ✍️  Sedang meminta Gemini menulis materi...");

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

            $this->info("   ⏳ Cooldown 15 detik...");
            sleep(15);
        }

        $this->info("\n🎉 Selesai generate materi!");
    }
}
