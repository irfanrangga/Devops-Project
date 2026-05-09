<?php

namespace App\Http\Controllers;

use App\Helpers\ApiClient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Http;
use function Symfony\Component\Clock\now;

class RegisterController extends Controller
{
    public function index()
    {
        return view('register');
    }

    public function store(Request $request)
    {
        // 1. VALIDASI DATA
        $validatedData = $request->validate([
            'name' => 'required|min:3|max:255',
            'email' => 'required|email:dns',
            'password' => 'required|min:6|confirmed',
        ]);

        // 2. KIRIM KE API
        $response = ApiClient::post('/register', [
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
        ]);

        // 3. JIKA GAGAL
        if ($response->failed()) {
            return back()->withErrors([
                'register' => $response->json('message') ?? 'Registrasi gagal'
            ]);
        }

        // 4. REDIRECT KE LOGIN
        return redirect()
            ->route('login')
            ->with('success', 'Akun berhasil dibuat! Silakan login.');
    }
}
