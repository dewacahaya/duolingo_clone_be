<?php

namespace App\Http\Resources;

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
        $progress = $this->userProgress->first();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'topic_keyword' => $this->topic_keyword,
            'order_sequence' => $this->order_sequence,
            'status' => $progress ? $progress->status : 'locked', // locked, in_progress, completed
            'stars' => $progress ? $progress->stars : 0,
        ];
    }
}
