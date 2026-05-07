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
        $response = ApiClient::post('/login', [
            'email' => $request->email,
            'password' => $request->password
        ]);

        if ($response->failed()) {
            return back()
            ->withInput()
            ->withErrors([
                'email' => $response->json('message') ?? 'Email atau password salah'
            ]);
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
    }

    // LOGOUT
    public function logout(Request $request)
    {
        $request->session()->forget([
            'jwt_token',
            'user_role',
            'user_name',
            'user_email',
            'user_id'
        ]);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil keluar.');
    }
}
