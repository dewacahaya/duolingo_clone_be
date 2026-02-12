<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChapterResource;
use App\Models\Chapter;
use App\Models\Unit;
use Illuminate\Http\Request;

class CurriculumController extends Controller
{
    /**
     * Menampilkan semua Chapter dan Unit (Learning Map)
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
     * Menampilkan detail unit (Materi/Guide)
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
