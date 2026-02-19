<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Validation\ValidationException;

/**
 * @group 🔐 Autentikasi & Akun
 * API untuk pendaftaran, login (Manual & Google OAuth), serta pembuatan token sesi menggunakan Laravel Sanctum.
 */
class AuthController extends Controller
{
    /**
     * Google Login: Redirect
     * * API ini mengembalikan URL otorisasi Google. Frontend harus me-redirect user ke URL ini agar mereka bisa login menggunakan akun Google.
     * * @unauthenticated
     * @response {
     * "url": "https://accounts.google.com/o/oauth2/auth?client_id=..."
     * }
     */
    public function redirectToProvider()
    {
        $url = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();

        return response()->json([
            'url' => $url
        ]);
    }

    /**
     * Google Login: Callback (Hidden)
     * @hideFromAPIDocumentation
     */
    public function handleProviderCallback()
    {
        try {
            $socialUser = Socialite::driver('google')->stateless()->user();

            // Cari atau buat user baru
            $user = User::updateOrCreate(
                ['email' => $socialUser->getEmail()],
                [
                    'name' => $socialUser->getName(),
                    'password' => Hash::make(uniqid()),
                    'avatar_url' => $socialUser->getAvatar(),
                ]
            );

            if ($user->wasRecentlyCreated) {
                $user->energy = 5;
                $user->xp_total = 0;
                $user->save();
            }
            $token = $user->createToken('auth_token')->plainTextToken;
            $frontendUrl = env('FRONTEND_URL', 'http://127.0.0.1:3000') . '/auth/callback?token=' . $token;
            return redirect()->away($frontendUrl);
        } catch (\Exception $e) {
            // \Log::error('Socialite Error: ' . $e->getMessage());
            $frontendUrl = env('FRONTEND_URL', 'http://127.0.0.1:3000') . '/login?error=auth_failed';
            return redirect()->away($frontendUrl);
        }
    }

    /**
     * Register Manual
     * * Mendaftarkan pengguna baru dengan email dan password. Otomatis memberikan 5 Energy awal dan token akses.
     * * @unauthenticated
     * @bodyParam name string required Nama lengkap pengguna. Example: Taro Yamada
     * @bodyParam email string required Email aktif yang belum pernah didaftarkan. Example: taro@gmail.com
     * @bodyParam password string required Password minimal 8 karakter. Example: rahasia123
     * @bodyParam password_confirmation string required Konfirmasi password (wajib sama dengan password). Example: rahasia123
     * * @response {
     * "message": "Registration successful",
     * "token": "1|abcdef1234567890...",
     * "user": {
     * "name": "Taro Yamada",
     * "email": "taro@gmail.com",
     * "energy": 5,
     * "xp_total": 0,
     * "streak": 0,
     * "updated_at": "2026-02-18T10:00:00.000000Z",
     * "created_at": "2026-02-18T10:00:00.000000Z",
     * "id": 5
     * }
     * }
     * @response status=422 scenario="Validasi Gagal (Email sudah dipakai)" {
     * "message": "The email has already been taken.",
     * "errors": {
     * "email": ["The email has already been taken."]
     * }
     * }
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $avatarUrl = null;
        if ($request->hasFile('avatar')) {
            $avatarUrl = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'avatar_url' => $avatarUrl,
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

    /**
     * Login Manual
     * * Mendapatkan token akses (Bearer Token) untuk user yang sudah terdaftar.
     * * @unauthenticated
     * @bodyParam email string required Email user yang valid. Example: taro@gmail.com
     * @bodyParam password string required Password akun. Example: rahasia123
     * * @response {
     * "message": "Login successful",
     * "token": "2|xyz0987654321...",
     * "user": {
     * "id": 5,
     * "name": "Taro Yamada",
     * "email": "taro@gmail.com",
     * "energy": 5,
     * "xp_total": 150,
     * "streak": 2
     * }
     * }
     * @response status=422 scenario="Password Salah" {
     * "message": "The provided credentials are incorrect.",
     * "errors": {
     * "email": ["The provided credentials are incorrect."]
     * }
     * }
     */
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

    /**
     * Logout
     * * Menghancurkan token sesi saat ini agar tidak bisa digunakan lagi.
     * * @authenticated
     * @response {
     * "message": "Logged out successfully"
     * }
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
