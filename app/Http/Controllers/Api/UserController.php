<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    public function __construct(protected UserService $userService)
    {
    }

    /**
     * Get Current User Profile (Me)
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
     * Get Global Leaderboard
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
     * Update Profile
     */
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'string|max:50',
            'avatar_url' => 'nullable|url'
        ]);

        $user = $this->userService->updateProfile($request->user(), $validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => new UserResource($user)
        ]);
    }
}
