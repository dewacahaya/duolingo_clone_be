<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    // 1. Redirect user ke halaman login Google
    public function redirectToProvider()
    {
        $url = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();

        return response()->json([
            'url' => $url
        ]);
    }

    // 2. Google membalas ke sini setelah user login
    public function handleProviderCallback()
    {
        try {
            $socialUser = Socialite::driver('google')->stateless()->user();

            // Cari atau buat user baru
            $user = User::updateOrCreate(
                ['email' => $socialUser->getEmail()],
                [
                    'name' => $socialUser->getName(),
                    'password' => $socialUser->token,
                    'avatar_url' => $socialUser->getAvatar(),
                ]
            );

            if ($user->wasRecentlyCreated) {
                $user->energy = 5;
                $user->xp_total = 0;
                $user->save();
            }
            $token = $user->createToken('auth_token')->plainTextToken;
            $frontendUrl = config('app.frontend_url') . '/auth/callback?token=' . $token;
            return redirect()->away($frontendUrl);
        } catch (\Exception $e) {
            // \Log::error('Socialite Error: ' . $e->getMessage());
            $frontendUrl = config('app.frontend_url', 'http://localhost:3000') . '/login?error=auth_failed';
            return redirect()->away($frontendUrl);
        }
    }


    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'energy' => 5,
            'xp_total' => 0,
            'streak' => 0
        ]);

        return response()->json([
            'message' => 'Registration successful',
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user' => $user
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'message' => 'Login successful',
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
