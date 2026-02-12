<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserService
{
    /**
     * Mengambil data user lengkap dengan kalkulasi ranking & regenerasi nyawa
     */
    public function getUserProfile(User $user): array
    {
        // 1. Cek & Regenerasi Nyawa (Logic Duolingo)
        // Jika nyawa < 5, cek apakah sudah waktunya nambah?
        $this->processHeartRegeneration($user);

        // 2. Hitung Ranking Global User
        // (Cara cepat: hitung berapa orang yang XP-nya lebih tinggi + 1)
        $rank = User::where('xp_total', '>', $user->xp_total)->count() + 1;

        // 3. Siapkan data tambahan
        return [
            'user' => $user,
            'rank' => $rank,
            'next_heart_in' => $this->calculateNextHeartTime($user),
        ];
    }

    /**
     * Mengambil Top 10 User untuk Leaderboard
     */
    public function getLeaderboard(): Collection
    {
        return User::select('id', 'name', 'avatar_url', 'xp_total', 'streak')
            ->orderByDesc('xp_total')
            ->limit(10)
            ->get();
    }

    /**
     * Update profil user (Nama & Avatar)
     */
    public function updateProfile(User $user, array $data): User
    {
        // Jika ada logic validasi bisnis tambahan, taruh di sini
        // Contoh: User Premium bisa ganti warna nama, dsb.

        $user->update([
            'name' => $data['name'] ?? $user->name,
            'avatar_url' => $data['avatar_url'] ?? $user->avatar_url,
        ]);

        return $user;
    }

    // --- PRIVATE HELPER METHODS ---

    /**
     * Logic Regenerasi Nyawa:
     * Menambah 1 hati setiap 2 jam (contoh) jika hati < 5
     */
    private function processHeartRegeneration(User $user): void
    {
        if ($user->hearts >= 5) {
            return;
        }

        $lastReplenish = $user->hearts_replenished_at
            ? Carbon::parse($user->hearts_replenished_at)
            : Carbon::now()->subHours(5); // Default lama

        // Hitung selisih waktu dalam jam
        $hoursPassed = $lastReplenish->diffInHours(Carbon::now());

        if ($hoursPassed >= 1) {
            // Tambah hati sesuai jam yang berlalu (max mentok di 5)
            $heartsToAdd = floor($hoursPassed / 2); // Misal: 1 hati tiap 2 jam

            // Logic sederhana: kalau lewat 4 jam, tambah 1 hati (bisa disesuaikan)
            // Di sini kita buat logic: Setiap akses, kalau sudah lewat waktu cooldown, tambah 1.
            $newHearts = min(5, $user->hearts + 1); // Tambah 1 hati per cycle akses valid

            if ($newHearts > $user->hearts) {
                $user->hearts = $newHearts;
                $user->hearts_replenished_at = Carbon::now();
                $user->save();
            }
        }
    }

    private function calculateNextHeartTime(User $user): ?string
    {
        if ($user->hearts >= 5)
            return null;

        // Kapan hati berikutnya muncul? (Misal 2 jam dari terakhir replenish)
        $lastReplenish = $user->hearts_replenished_at
            ? Carbon::parse($user->hearts_replenished_at)
            : Carbon::now();

        return $lastReplenish->addHours(2)->diffForHumans(); // "in 1 hour"
    }
}
