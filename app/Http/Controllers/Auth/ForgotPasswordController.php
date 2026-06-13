<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    // TAMPILKAN FORM LUPA PASSWORD
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    // PROSES PENGIRIMAN LINK RESET PASSWORD
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        // Mengirimkan tautan reset password menggunakan broker default Laravel
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Tautan reset password telah dikirim ke email Anda! Silakan periksa kotak masuk atau log email.');
        }

        // Pemetaan pesan kesalahan ke Bahasa Indonesia
        $errorMessage = match ($status) {
            Password::INVALID_USER => 'Kami tidak dapat menemukan pengguna dengan email tersebut.',
            Password::RESET_THROTTLED => 'Harap tunggu sebelum meminta tautan reset password kembali.',
            default => 'Gagal mengirimkan tautan reset password.',
        };

        return back()->withErrors(['email' => $errorMessage]);
    }
}
