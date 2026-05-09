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
<<<<<<< HEAD
        // 1. Validasi input dari user
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
=======
        $response = ApiClient::post('/login', [
            'email' => $request->email,
            'password' => $request->password
>>>>>>> 5ecbfe40e72a06df6b8c5d4bc73b2fd0cf3e9361
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
<<<<<<< HEAD
=======
        }

        $token = $response->json('token')
                ?? $response->json('data.token');

        if(!$token) {
            return back()
                ->withInput()
                ->withErrors([
                'email' => 'Token tidak diterima dari API'
                ]);
        }

        $decoded = JWT::decode(
            $token, 
            new Key(env('JWT_SECRET'), 'HS256')
        );

        $user = new User([
            'id' => $decoded->id,
            'name' => $decoded->name ?? $decoded->email,
            'email' => $decoded->email,
            'role' => $decoded->role
        ]);

        Auth::login($user);

        // Simpan ke session
        Session::put('jwt_token', $token);
        Session::put('user_role', $decoded->role);
        Session::put('user_id', $decoded->id);
        Session::put('user_name', $decoded->name ?? $decoded->email);
        Session::put('user_email', $decoded->email);

        return redirect()->route('home');
>>>>>>> 5ecbfe40e72a06df6b8c5d4bc73b2fd0cf3e9361
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
