<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CurriculumController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WritingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| DEV ROUTES (Hanya aktif di local)
|--------------------------------------------------------------------------
*/
if (app()->environment('local')) {
    Route::get('/dev/token', function () {
        $user = App\Models\User::firstOrCreate(
            ['email' => 'dev@test.com'],
            [
                'name' => 'Dev Tester',
                'password' => bcrypt('password'),
                'energy' => 5,
                'xp_total' => 0
            ]
        );
        return ['token' => $user->createToken('test')->plainTextToken];
    });
}

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (Auth)
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    // Manual Auth
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Socialite (Google)
    Route::get('/google', [AuthController::class, 'redirectToProvider']);
    Route::get('/google/callback', [AuthController::class, 'handleProviderCallback']);
});

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (Butuh Token)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [UserController::class, 'me']);
    Route::post('/me/update', [UserController::class, 'update']);
    Route::get('/leaderboard', [UserController::class, 'leaderboard']);

    // --- Curriculum (Map & Materi) ---
    Route::get('/chapters', [CurriculumController::class, 'index']);
    Route::get('/units/{id}', [CurriculumController::class, 'showUnit']);

    // --- Quiz System ---
    Route::get('/quiz/start/{unit_id}', [QuizController::class, 'start']);
    Route::post('/quiz/submit', [QuizController::class, 'submit']);
    Route::get('/quiz/history/{unit_id}', [QuizController::class, 'history']);

    // --- Writing Practice (Fitur Canvas) ---
    // 1. Ambil daftar huruf (Hiragana/Katakana)
    Route::get('/characters', [WritingController::class, 'index']);
    Route::get('/characters/{id}', [WritingController::class, 'show']);
    // 2. Kirim gambar canvas ke Gemini Vision
    Route::post('/writing/analyze', [WritingController::class, 'analyze']);
    Route::post('/writing/progress', [WritingController::class, 'saveProgress']);

    // --- Shop / Heart Refill (Opsional/Future) ---
    // Route::post('/shop/items', [ShopController::class, 'itemShops']);
});
