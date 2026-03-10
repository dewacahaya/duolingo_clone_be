<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService
{

    const max_energy = 10;
    const regen_interval = 15;
    /**
     * Mengambil data user lengkap dengan kalkulasi ranking & regenerasi nyawa
     */
    public function getUserProfile(User $user): array
    {
        // 1. Cek & Regenerasi Nyawa (Logic Duolingo)
        // Jika nyawa < 5, cek apakah sudah waktunya nambah?
        $this->processEnergyRegeneration($user);

        $this->processStreakCheck($user);

        // 2. Hitung Ranking Global User
        // (Cara cepat: hitung berapa orang yang XP-nya lebih tinggi + 1)
        $rank = User::where('xp_total', '>', $user->xp_total)->count() + 1;

        // 3. Siapkan data tambahan
        // return [
        //     'id' => $user->id,
        //     'name' => $user->name,
        //     'email' => $user->email,
        //     'avatar_url' => $user->avatar_url ? asset('storage/' . $user->avatar_url) : null,
        //     'stats' => [
        //         'xp' => $user->xp_total,
        //         'energy' => $user->energy,
        //         'streak' => $user->streak,
        //         'gems' => $user->gems ?? 0,
        //     ],
        //     'rank' => $rank,
        //     'next_energy_in' => $this->calculateNextEnergyTime($user),
        // ];
        return [
            'user' => $user, // Ini adalah Object Model User
            'rank' => $rank,
            'next_energy_in' => $this->calculateNextEnergyTime($user),
        ];
    }

    /**
     * Mengambil Top 10 User untuk Leaderboard
     */
    public function getLeaderboard(): Collection
    {
        $users = User::orderByDesc('xp_total')
            ->limit(10)
            ->get();

        return $users->map(function ($user, $index) {
            return [
                'user' => $user,
                'rank' => $index + 1,
            ];
        });
    }

    /**
     * Update profil user (Nama & Avatar)
     */
    public function updateProfile(User $user, array $data, $avatarFile = null): User
    {
        if (isset($data['name'])) {
            $user->name = $data['name'];
        }

        if (isset($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        if ($avatarFile) {
            if ($user->avatar_url && !str_starts_with($user->avatar_url, 'http')) {
                if (Storage::disk('public')->exists($user->avatar_url)) {
                    Storage::disk('public')->delete($user->avatar_url);
                }
            }

            $path = $avatarFile->store('avatars', 'public');
            $user->avatar_url = $path;
        }

        $user->save();
        return $user;
    }

    // --- PRIVATE HELPER METHODS ---

    /**
     * Logic Pengecekan Streak:
     * Kalau user terakhir main sebelum kemarin, reset streak ke 0.
     */
    private function processStreakCheck(User $user): void
    {
        // Kalau belum pernah main atau streak sudah 0, aman. Abaikan saja.
        if (!$user->last_study_at || $user->streak == 0) {
            return;
        }

        $lastStudy = Carbon::parse($user->last_study_at)->startOfDay();
        $yesterday = Carbon::yesterday(); // Waktu jam 00:00:00 hari kemarin

        // Jika terakhir main H-2 atau lebih lama (sebelum kemarin)
        if ($lastStudy->lessThan($yesterday)) {
            $user->streak = 0;
            $user->save();
        }
    }

    /**
     * Logic Regenerasi Nyawa:
     * Menambah 1 hati setiap 1 jam (contoh) jika hati < 5
     */
    private function processEnergyRegeneration(User $user): void
    {
        if ($user->energy >= self::max_energy) {
            if ($user->energy_replenished_at) {
                $user->update(['energy_replenished_at' => null]);
            }
            return;
        }

        if (!$user->energy_replenished_at) {
            $user->update(['energy_replenished_at' => Carbon::now()]);
            return;
        }

        $lastReplenish = Carbon::parse($user->energy_replenished_at);
        $now = Carbon::now();

        $minutesPassed = $lastReplenish->diffInMinutes($now);
        $energyToAdd = floor($minutesPassed / self::regen_interval);

        if ($energyToAdd > 0) {
            $newenergy = min(self::max_energy, $user->energy + $energyToAdd);

            if ($newenergy == self::max_energy) {
                $user->energy = $newenergy;
                $user->energy_replenished_at = null;
            } else {
                $user->energy = $newenergy;
                $user->energy_replenished_at = $lastReplenish->addMinutes($energyToAdd * self::regen_interval);
            }

            $user->save();
        }
    }

    private function calculateNextEnergyTime(User $user): ?string
    {
        if ($user->energy >= self::max_energy || !$user->energy_replenished_at) {
            return null;
        }

        $lastReplenish = Carbon::parse($user->energy_replenished_at);
        $nextEnergyTime = $lastReplenish->addMinutes(self::regen_interval);

        $now = Carbon::now();

        $diff = $nextEnergyTime->diff($now);
        $hours = $diff->h;
        $minutes = $diff->i;

        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }
        return "{$minutes}m";
    }
}
