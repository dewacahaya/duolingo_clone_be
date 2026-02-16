<?php

namespace App\Http\Resources;

use App\Models\QuizSession;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $request->user();
        $progress = $this->whenLoaded('userProgress') ? $this->userProgress->first() : null;

        $status = 'locked';

        if ($this->order_sequence == 1 && !$progress) {
            $status = 'open';
        }

        if ($progress) {
            if ($progress->is_completed == 1 || $progress->is_completed == true) {
                $status = 'completed';
            } elseif ($progress->is_locked == 0 || $progress->is_locked == false) {
                $status = 'open';
            }
        }

        $stars = 0;
        if ($user && $status !== 'locked') {
            $highestScore = QuizSession::where('user_id', $user->id)
                ->where('unit_id', $this->id)
                ->max('score') ?? 0;

            if ($highestScore == 100) {
                $stars = 3;
            } elseif ($highestScore >= 80) {
                $stars = 2;
            } elseif ($highestScore >= 60) {
                $stars = 1;
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'topic_keyword' => $this->topic_keyword,
            'order_sequence' => $this->order_sequence,
            'status' => $status,
            'stars' => $stars,
            'current_level' => $progress ? $progress->current_level : 0,
        ];
    }
}
