<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TalentController;
use App\Http\Controllers\TalentDashboardController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\TopTalentController;
use Illuminate\Support\Facades\Route;

// Halaman Publik (Customer View)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
Route::get('/talents', [TalentController::class, 'index'])->name('talents.index');
Route::get('/talents/{id}', [TalentController::class, 'show'])->name('talents.show');
Route::get('/top-talent', [TopTalentController::class, 'index'])->name('top-talents.index');

// Pemesanan dengan proteksi Rate Limiting 5 kali per menit per IP (PRD Bagian 6)
Route::post('/orders', [OrderController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('orders.store');

// Autentikasi Umum (Redirect ke Login Talent)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Autentikasi Talent (Login & Registrasi)
Route::prefix('talent')->name('talent.')->group(function () {
    Route::get('/login', [AuthController::class, 'showTalentLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'talentLogin'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showTalentRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'talentRegister'])->name('register.post');

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [TalentDashboardController::class, 'index'])->name('dashboard');
        Route::post('/profile', [TalentDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::post('/services', [TalentDashboardController::class, 'storeService'])->name('services.store');
        Route::post('/services/{id}/toggle', [TalentDashboardController::class, 'toggleService'])->name('services.toggle');
    });
});

// Autentikasi Admin (Khusus Login)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showAdminLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'adminLogin'])->name('login.post');

    Route::middleware('auth')->get('/dashboard', function () {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Akses khusus Administrator.');
        }
        return view('admin.dashboard');
    })->name('dashboard');
});
