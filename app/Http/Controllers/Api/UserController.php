<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @group 👤 Profil & Papan Peringkat
 * API untuk mengambil data statistik pribadi pengguna, mengubah profil, serta melihat peringkat global (Leaderboard).
 */
class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Get Profil Saya (Me)
     * * Mengambil informasi lengkap pengguna yang sedang login.
     * Setiap kali endpoint ini dipanggil, sistem akan melakukan *Lazy Evaluation* untuk meregenerasi Energy (nyawa) jika waktu tunggunya sudah terpenuhi.
     * * @authenticated
     * @response {
     * "data": {
     * "id": 1,
     * "name": "Budi Santoso",
     * "email": "budi@mail.com",
     * "avatar": null,
     * "stats": {
     * "xp": 1250,
     * "gems": 0,
     * "streak": 5,
     * "energy": 4,
     * "rank": 12,
     * "next_energy_in": "15m 30s"
     * },
     * "joined_at": "18 Feb 2026"
     * }
     * }
     */
    public function me(Request $request): JsonResponse
    {
        // Panggil service untuk dapat data + kalkulasi
        $data = $this->userService->getUserProfile($request->user());

        return response()->json([
            'data' => new UserResource($data)
        ]);
    }

    /**
     * Papan Peringkat (Leaderboard)
     * * Menampilkan daftar 50 besar pengguna dengan skor XP (Experience Points) tertinggi.
     * * @authenticated
     * @response {
     * "data": [
     * {
     * "id": 1,
     * "name": "Pro Gamer JPN",
     * "email": "pro@mail.com",
     * "avatar": "https://url-ke-gambar.com/avatar.jpg",
     * "stats": {
     * "xp": 9500,
     * "gems": 0,
     * "streak": 30,
     * "energy": 5
     * }
     * }
     * ]
     * }
     */
    public function leaderboard(): JsonResponse
    {
        $users = $this->userService->getLeaderboard();

        // Kita gunakan collection resource untuk list
        return response()->json([
            'data' => UserResource::collection($users)
        ]);
    }

    /**
     * Update Profil
     * * Mengubah nama atau foto profil (Avatar) pengguna.
     * Untuk mengunggah avatar, pastikan mengirim request dalam bentuk `multipart/form-data`.
     * * @authenticated
     * @bodyParam name string optional Nama baru pengguna. Example: Budi Keren
     * @bodyParam avatar file optional File gambar untuk foto profil (JPG/PNG, Max: 2MB).
     * * @response {
     * "message": "Profile updated successfully",
     * "data": {
     * "id": 1,
     * "name": "Budi Keren",
     * "avatar": "http://localhost:8000/storage/avatars/random-string.png",
     * "stats": {
     * "xp": 1250
     * }
     * }
     * }
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'avatar' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'sometimes|string|min:8',
        ]);

        $user = $request->user();

        if ($request->has('password') && $user->provider === 'google') {
            return response()->json([
                'message' => 'Cannot change password on Google account.'
            ], 403);
        }

        $user = $this->userService->updateProfile(
            $request->user(),
            $request->only(['name', 'password']),
            $request->file('avatar')
        );

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => new UserResource($user)
        ]);
    }
}
