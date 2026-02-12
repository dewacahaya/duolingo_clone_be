<?php

use App\Models\Question;
use App\Services\GeminiService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-gemini', function () {
    // 1. Inisialisasi Service
    $gemini = new GeminiService();

    // 2. Tentukan Topik Test
    $topic = 'Japanese Fruits (Ringo, Mikan)';

    // 3. Coba Generate 2 Soal (sedikit aja biar cepat)
    echo "<h1>Sedang menghubungi Gemini...</h1>";

    $startTime = microtime(true);

    // Kita panggil fungsi generateQuestions yang sudah kita buat di Service
    // Pastikan method generateQuestions di GeminiService kamu bersifat PUBLIC
    $result = $gemini->generateQuestions($topic, 2);

    $duration = microtime(true) - $startTime;

    // 4. Tampilkan Hasil Debugging
    return [
        'status' => 'success',
        'duration' => round($duration, 2) . ' seconds',
        'raw_response' => $result
    ];
});

Route::get('/debug-questions', function () {
    // Ambil 1 soal terakhir yang dibuat AI
    $q = Question::where('is_ai_generated', true)->latest()->first();

    if (!$q)
        return 'Belum ada soal AI.';

    return [
        'id' => $q->id,
        'unit_id' => $q->unit_id,
        // Kita dump kolom content apa adanya
        'content_raw' => $q->content,
        // Cek spesifik apakah key explanation ada?
        'has_explanation' => isset($q->content['explanation']) ? 'ADA ✅' : 'HILANG ❌',
        'explanation_text' => $q->content['explanation'] ?? '-'
    ];
});


// Route::get('/debug-env', function () {
//     // Kita dump semua variabel terkait
//     dd([
//         '1. Cek langsung .env' => env('GEMINI_API_KEY'),
//         '2. Cek via Config' => config('services.gemini.key'),
//         '3. Cek seluruh Config Services' => config('services'),
//     ]);
// });


Route::get('/check-models', function () {
    $apiKey = config('services.gemini.key');

    // Kita tembak endpoint ListModels
    $response = Illuminate\Support\Facades\Http::get(
        "https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}"
    );

    if ($response->failed()) {
        return [
            'error' => true,
            'message' => $response->body()
        ];
    }

    $models = $response->json('models');

    // Kita filter cuma ambil yang support 'generateContent'
    $available = collect($models)
        ->filter(fn($m) => in_array('generateContent', $m['supportedGenerationMethods']))
        ->map(fn($m) => $m['name']) // Ambil namanya doang, misal: models/gemini-pro
        ->values();

    return [
        'count' => $available->count(),
        'available_models' => $available
    ];
});
