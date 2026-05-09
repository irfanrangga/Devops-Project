<?php

namespace App\Http\Controllers;

use App\Helpers\ApiClient;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    // TAMPILKAN FORM LOGIN
    public function index()
    {
        return view('login');
    }
    
    // PROSES LOGIN
    public function authenticate(Request $request)
    {
        // 1. Validasi input dari user
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            // Regenerasi session untuk mencegah serangan Session Fixation
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin');
            }

            return redirect()->route('home');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'Email atau password salah.',
            ]);
    }

    // LOGOUT
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil keluar.');
    }
}
