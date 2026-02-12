<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Character;
use App\Models\UserCharacterProgress;
use App\Services\GeminiService;
use Illuminate\Http\Request;

class WritingController extends Controller
{
    public function index()
    {
        $chars = Character::all();

        $grouped = [
            'hiragana' => $chars->where('type', 'hiragana')->values(),
            'katakana' => $chars->where('type', 'katakana')->values(),
        ];

        $user = auth()->user();
        if ($user) {
            $progress = UserCharacterProgress::where('user_id', $user->id)->get()->keyBy('character_id');

            foreach ($grouped as $type => $characters) {
                foreach ($characters as $char) {
                    $p = $progress->get($char->id);
                    $char->mastery_level = $p ? $p->mastery_level : 0;
                }
            }
        }

        return response()->json(['data' => $grouped]);
    }

    public function show($id)
    {
        $char = Character::findOrFail($id);
        return response()->json(['data' => $char]);
    }

    public function analyze(Request $request, GeminiService $gemini)
    {
        $request->validate([
            'image' => 'required',
            'character_id' => 'required|exists:characters,id'
        ]);

        $char = Character::find($request->character_id);

        $result = $gemini->analyzeHandwriting($request->image, $char->char);

        if (!$result) {
            return response()->json(['message' => 'Gagal menganalisis gambar'], 500);
        }

        $analysis = json_decode($result, true);

        return response()->json([
            'score' => $analysis['score'] ?? 0,
            'feedback' => $analysis['feedback'] ?? 'Belum ada feedback!',
            'target' => $char
        ]);
    }

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
