<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChapterResource;
use App\Models\Chapter;
use App\Models\Unit;
use Illuminate\Http\Request;

/**
 * @group 🗺️ Peta Kurikulum & Materi
 * API untuk menampilkan struktur bab (Chapter), daftar unit materi, dan panduan belajar sebelum kuis.
 */
class CurriculumController extends Controller
{
    /**
     * Tampilkan Learning Map (Homepage)
     * * Mengambil daftar seluruh Chapter dan Unit secara berurutan. API ini otomatis menyisipkan status progress dari user yang sedang login (apakah unit tersebut `locked`, `open`, atau `completed`), serta jumlah bintang yang diraih.
     * * @authenticated
     * @response {
     * "data": [
     * {
     * "id": 1,
     * "name": "Pengenalan Huruf Jepang",
     * "topic_keyword": "Hiragana & Katakana Dasar",
     * "order_sequence": 1,
     * "units": [
     * {
     * "id": 1,
     * "name": "Vokal Hiragana (A, I, U, E, O)",
     * "topic_keyword": "Hiragana Vowels",
     * "order_sequence": 1,
     * "status": "completed",
     * "stars": 3,
     * "current_level": 1
     * },
     * {
     * "id": 2,
     * "name": "Baris K & S",
     * "topic_keyword": "Hiragana K S",
     * "order_sequence": 2,
     * "status": "open",
     * "stars": 0,
     * "current_level": 0
     * }
     * ]
     * }
     * ]
     * }
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $chapters = Chapter::with([
            'units' => function ($query) use ($user) {
                $query->with([
                    'userProgress' => function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    }
                ])->orderBy('order_sequence', 'asc');
            }
        ])->orderBy('order_sequence', 'asc')->get();

        return ChapterResource::collection($chapters);
    }

    /**
     * Detail Materi Unit (Guide)
     * * Mengambil detail satu unit beserta teks materinya (`guide_md`). Ini ditampilkan saat user menekan Unit di peta, sebelum mereka menekan tombol "Mulai Kuis".
     * * @authenticated
     * @urlParam id integer required ID dari Unit yang ingin dilihat materinya. Example: 1
     * @response {
     * "id": 1,
     * "name": "Vokal Hiragana (A, I, U, E, O)",
     * "guide_md": "# Pengenalan\nHuruf vokal dalam bahasa Jepang terdiri dari あ (a), い (i), う (u), え (e), dan お (o)...",
     * "topic": "Hiragana Vowels",
     * "progress": {
     * "id": 15,
     * "user_id": 3,
     * "unit_id": 1,
     * "current_level": 1,
     * "is_completed": true,
     * "is_locked": false,
     * "created_at": "2026-02-18T10:00:00Z",
     * "updated_at": "2026-02-18T10:15:00Z"
     * }
     * }
     * @response status=404 scenario="Unit tidak ditemukan" {
     * "message": "No query results for model [App\\Models\\Unit] 999"
     * }
     */
    public function showUnit($id, Request $request)
    {
        $unit = Unit::with([
            'userProgress' => function ($q) use ($request) {
                $q->where('user_id', $request->user()->id);
            }
        ])->findOrFail($id);

        return [
            'id' => $unit->id,
            'name' => $unit->name,
            'guide_md' => $unit->guide_md,
            'topic' => $unit->topic_keyword,
            'progress' => $unit->userProgress->first()
        ];
    }
}
