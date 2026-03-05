<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Character;
use App\Models\UserCharacterProgress;
use App\Services\GeminiService;
use Illuminate\Http\Request;


/**
 * @group ✍️ Latihan Menulis (AI Vision)
 * API untuk sistem latihan menulis huruf Jepang (Drawing Canvas) yang dinilai langsung oleh AI Gemini Vision.
 */
class WritingController extends Controller
{
    /**
     * Daftar Karakter & Skor Penguasaan
     * * Mengambil seluruh daftar karakter Hiragana dan Katakana yang tersedia di sistem, lengkap dengan persentase `mastery_level` (skor tertinggi) yang pernah diraih user.
     * * @authenticated
     * @response {
     * "data": {
     * "hiragana": [
     * {
     * "id": 1,
     * "char": "あ",
     * "romaji": "a",
     * "type": "hiragana",
     * "mastery_level": 95
     * }
     * ],
     * "katakana": [
     * {
     * "id": 47,
     * "char": "ア",
     * "romaji": "a",
     * "type": "katakana",
     * "mastery_level": 0
     * }
     * ]
     * }
     * }
     */
    public function index(Request $request)
    {
        try {
            $chars = Character::all();

            $grouped = [
                'hiragana' => $chars->where('type', 'hiragana')->values(),
                'katakana' => $chars->where('type', 'katakana')->values(),
            ];

            $user = $request->user();
            if ($user) {
                $progress = UserCharacterProgress::where('user_id', $user->id)->get()->keyBy('character_id');

                foreach ($grouped as $type => $characters) {
                    $grouped[$type] = $characters->map(function ($char) use ($progress) {
                        $p = $progress->get($char->id);
                        $char->mastery_level = $p ? $p->mastery_level : 0;
                        return $char;
                    });
                }
            }

            return response()->json(['data' => $grouped]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan di server.',
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * Detail Karakter Tunggal
     * * @authenticated
     * @urlParam id integer required ID Karakter yang ada di database. Example: 1
     * @response {
     * "data": {
     * "id": 1,
     * "char": "あ",
     * "romaji": "a",
     * "type": "hiragana",
     * "stroke_count": 3
     * }
     * }
     */
    public function show($id)
    {
        $char = Character::findOrFail($id);
        return response()->json(['data' => $char]);
    }

    /**
     * Analisis Coretan Canvas (Submit AI)
     * * Endpoint krusial untuk fitur menulis. Frontend harus mengkonversi hasil goresan HTML5 Canvas menjadi format Base64 PNG/JPEG dan mengirimkannya ke endpoint ini. AI akan menilai kemiripannya dengan huruf asli.
     * * @authenticated
     * @bodyParam image string required Gambar hasil canvas dalam format Base64 (Data URI scheme). Example: data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=
     * @bodyParam character_id integer required ID karakter target yang sedang dipelajari. Example: 1
     * * @response {
     * "score": 85,
     * "feedback": "Goresan pertama sudah tepat, namun lengkungan di bagian bawah kurang membulat. Coba perhatikan proporsinya lagi!",
     * "target": {
     * "id": 1,
     * "char": "あ",
     * "romaji": "a",
     * "type": "hiragana"
     * }
     * }
     * @response status=500 scenario="AI Gagal Membaca" {
     * "message": "Gagal menganalisis gambar. Pastikan gambar jelas."
     * }
     */
    // public function analyze(Request $request, GeminiService $gemini)
    // {
    //     $request->validate([
    //         'image' => 'required',
    //         'character_id' => 'required|exists:characters,id'
    //     ]);

    //     $char = Character::find($request->character_id);

    //     $result = $gemini->analyzeHandwriting($request->image, $char->char);

    //     if (!$result) {
    //         return response()->json(['message' => 'Gagal menganalisis gambar'], 500);
    //     }

    //     // $analysis = json_decode($result, true);
    //     $analysis = is_array($result) ? $result : json_decode($result, true);

    //     return response()->json([
    //         'score' => $analysis['score'] ?? 0,
    //         'feedback' => $analysis['feedback'] ?? 'Belum ada feedback!',
    //         'target' => $char
    //     ]);
    // }
    public function analyze(Request $request, GeminiService $gemini)
    {
        $request->validate([
            'image' => 'required',
            'character_id' => 'required|exists:characters,id'
        ]);

        $char = Character::find($request->character_id);

        // 🛡️ PENGECEKAN CERDAS:
        $imageData = $request->image; // Asumsi awal: bentuk teks base64 dari Frontend

        // Tapi, kalau yang dikirim adalah sebuah FILE (seperti di Postman):
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $base64 = base64_encode(file_get_contents($file->path()));
            $mime = $file->getClientMimeType();
            $imageData = "data:{$mime};base64,{$base64}"; // Ubah file jadi teks Base64
        }

        // Lempar imageData yang sudah dipastikan berwujud teks ke Service
        $result = $gemini->analyzeHandwriting($imageData, $char->char);

        if (!$result) {
            return response()->json(['message' => 'Gagal menganalisis gambar. Coba lagi.'], 500);
        }

        // Pengecekan cerdas dari solusi kita sebelumnya
        $analysis = is_array($result) ? $result : json_decode($result, true);

        return response()->json([
            'score' => $analysis['score'] ?? 0,
            'feedback' => $analysis['feedback'] ?? 'Belum ada feedback!',
            'target' => $char
        ]);
    }

    /**
     * Simpan Skor Menulis
     * * Menyimpan hasil kemiripan tertinggi (skor) ke database setelah user berhasil berlatih.
     * * @authenticated
     * @bodyParam character_id integer required ID Karakter target. Example: 1
     * @bodyParam score integer required Skor kemiripan yang didapat dari AI (0-100). Example: 85
     * * @response {
     * "message": "Progress saved!",
     * "data": {
     * "user_id": 3,
     * "character_id": 1,
     * "mastery_level": 85,
     * "last_practiced_at": "2026-02-18T10:30:00.000000Z"
     * }
     * }
     */
    public function saveProgress(Request $request)
    {
        $request->validate([
            'character_id' => 'required|exists:characters,id',
            'score' => 'required|integer|min:0|max:100'
        ]);

        $user = $request->user();

        $progress = UserCharacterProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'character_id' => $request->character_id
            ],
            [
                'last_practiced_at' => now(),
            ]
        );
        $progress->mastery_level = $request->score;
        $progress->save();

        return response()->json([
            'message' => 'Progress saved!',
            'data' => $progress
        ]);
    }
}
