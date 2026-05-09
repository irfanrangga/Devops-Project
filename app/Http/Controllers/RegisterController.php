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
            'email' => 'required|email:dns|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

        User::create($validatedData);

        // 4. REDIRECT KE LOGIN
        return redirect()
            ->route('login')
            ->with('success', 'Akun berhasil dibuat! Silakan login.');
    }
}
