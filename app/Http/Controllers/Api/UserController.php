<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
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
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'avatar' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        $user = $this->userService->updateProfile(
            $request->user(),
            $request->only('name'),
            $request->file('avatar')
        );

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => new UserResource($user)
        ]);
    }
}
