<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\Auth\GoogleController;

// HOME
Route::get('/', function () {
    return view('homepage');
})->name('home');

// LAYANAN
Route::get('/layanan', function () {
    return view('layanan');
})->name('layanan');

// Register
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Login
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.process');
});

// Google OAuth
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('/product', [ProductController::class, 'index'])->name('product.index');

Route::middleware(['auth'])->group(function () {
    // LOGOUT
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // DASHBOARD/PENGATURAN AKUN (Profile)
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

    // PRODUCT (Etalase & Detail)
    Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

    // PEMBARUAN DATA PENGGUNA (Update Info & Password)
    Route::put('/profile/update-info', [ProfileController::class, 'updateUserInfo'])->name('profile.update-info');
    Route::put('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/payment/{id}', [CheckoutController::class, 'show'])->name('payment.show');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    Route::get('/dashboard', function () {
        return redirect(config('filament.path', 'admin'));
    })->name('dashboard');
});

Route::post('/webhook/xendit', [WebhookController::class, 'handle'])->name('webhook.xendit');
