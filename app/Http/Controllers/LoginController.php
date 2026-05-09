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

        // 2. Coba proses login langsung ke tabel 'users' di MySQL
        // Auth::attempt akan otomatis mencocokkan email dan hash password
        if (Auth::attempt($credentials)) {
            // Regenerasi session untuk mencegah serangan Session Fixation
            $request->session()->regenerate();

            // Arahkan ke halaman utama/dashboard
            return redirect()->route('home');
        }

        // 3. Jika login gagal (email/password salah)
        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'Email atau password salah.',
            ]);
    }

    // LOGOUT
    public function logout(Request $request)
    {
        // Keluarkan user dari session Laravel
        Auth::logout();

        // Bersihkan dan amankan kembali session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil keluar.');
    }
}
