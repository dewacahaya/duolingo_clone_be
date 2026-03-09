<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $user = $this->resource['user'] ?? $this->resource;
        $rank = $this->resource['rank'] ?? null;
        $nextEnergy = $this->resource['next_energy_in'] ?? null;

        $avatarUrl = $user->avatar_url;
        if ($avatarUrl && !str_starts_with($avatarUrl, 'http')) {
            // Jika bukan dari Google (tidak ada http), tambahkan URL storage
            $avatarUrl = asset('storage/' . $avatarUrl);
        }

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar_url' => $avatarUrl,
            'stats' => [
                'xp' => $user->xp_total,
                'gems' => $user->gems,
                'streak' => $user->streak,
                'energy' => $user->energy,
                'rank' => $rank,
                'next_heart_in' => $nextEnergy,
            ],

            'joined_at' => $user->created_at->format('d M Y'),
        ];
    }
}
