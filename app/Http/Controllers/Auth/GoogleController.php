<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiClient;
use App\Http\Controllers\Controller;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;    
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session; 
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
            $avatarUrl = $googleUser->getAvatar();
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if (!$user) {
                $user = User::create([
                    'name'      => $googleUser->getName(),
                    'email'     => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password'  => Hash::make(Str::random(24)), 
                    'avatar'    => $avatarUrl,
                ]);
            } else {
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar'    => $avatarUrl,
                    ]);
                }
            }

            Auth::login($user);

            Session::put('user_avatar', $user->avatar);
            Session::put('user_name', $user->name);

            $request->session()->regenerate();

            return redirect()->route('home');

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }   
}
