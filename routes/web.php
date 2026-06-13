<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DesignFileController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

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

// Login & Password Reset
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.process');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
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
    Route::post('/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');

    // Design File Routes (Download & View)
    Route::get('/order/{orderId}/item/{itemId}/download', [DesignFileController::class, 'download'])->name('design.download');
    Route::get('/order/{orderId}/item/{itemId}/view', [DesignFileController::class, 'view'])->name('design.view');
    Route::post('/design/upload', [DesignFileController::class, 'upload'])->name('design.upload');

    Route::get('/chat/messages', [ChatController::class, 'getMessages'])->name('chat.get');
    Route::post('/chat/send', [ChatController::class, 'storeMessage'])->name('chat.store');

    Route::get('/dashboard', function () {
        return redirect(config('filament.path', 'admin'));
    })->name('dashboard');
});

Route::post('/webhook/xendit', [WebhookController::class, 'handle'])->name('webhook.xendit');
