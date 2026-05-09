<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiClient;
use App\Http\Controllers\Controller;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;    // <--- PASTIKAN INI ADA
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session; // <--- TAMBAHKAN INI
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{


    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if (!$user) {
                $user = User::create([
                    'name'      => $googleUser->getName(),
                    'email'     => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    // Buat password acak yang panjang karena user login via Google
                    'password'  => Hash::make(Str::random(24)), 
                    // 'role' => 'user' // Buka komentar ini jika Anda punya kolom role dengan default tertentu
                ]);
            } else {
                // (Opsional) Jika user sudah ada tapi google_id-nya masih kosong, kita update
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->getId()
                    ]);
                }
            }

            // Login ke session Laravel
            Auth::login($user);

            $request->session()->regenerate();

            return redirect()->route('home');

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }   
}
