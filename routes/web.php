<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\AdminSettingController;
use App\Http\Controllers\AdminTalentController;
use App\Http\Controllers\AdminTestimonialController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TalentController;
use App\Http\Controllers\TalentDashboardController;
use App\Http\Controllers\TalentForgotPasswordController;
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
Route::get('/orders/{order_code}', [OrderController::class, 'show'])->name('orders.show');

// Autentikasi Umum (Redirect ke Login Talent)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Autentikasi Talent (Login & Registrasi)
Route::prefix('talent')->name('talent.')->group(function () {
    Route::get('/login', [AuthController::class, 'showTalentLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'talentLogin'])
        ->middleware('throttle:10,1')
        ->name('login.post');
    Route::get('/register', [AuthController::class, 'showTalentRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'talentRegister'])
        ->middleware('throttle:5,1')
        ->name('register.post');

    // Reset Password via OTP Email (Khusus Talent)
    Route::get('/forgot-password', [TalentForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [TalentForgotPasswordController::class, 'sendOtp'])
        ->middleware('throttle:5,1')
        ->name('password.email');
    Route::get('/reset-password', [TalentForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [TalentForgotPasswordController::class, 'resetPassword'])
        ->middleware('throttle:5,1')
        ->name('password.update_via_otp');
    Route::post('/resend-otp', [TalentForgotPasswordController::class, 'resendOtp'])
        ->middleware('throttle:5,1')
        ->name('password.resend');

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [TalentDashboardController::class, 'index'])->name('dashboard');
        Route::post('/profile', [TalentDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::post('/password', [TalentDashboardController::class, 'updatePassword'])->name('password.update');
        Route::post('/services', [TalentDashboardController::class, 'storeService'])->name('services.store');
        Route::post('/services/{id}/toggle', [TalentDashboardController::class, 'toggleService'])->name('services.toggle');
        Route::delete('/services/{id}', [TalentDashboardController::class, 'destroyService'])->name('services.destroy');
    });
});

// Area Panel Admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showAdminLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'adminLogin'])
        ->middleware('throttle:10,1')
        ->name('login.post');

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Manajemen Pesanan Masuk
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::delete('/orders/{id}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');

        Route::get('/talents', [AdminTalentController::class, 'index'])->name('talents.index');
        Route::get('/talents/create', [AdminTalentController::class, 'create'])->name('talents.create');
        Route::post('/talents', [AdminTalentController::class, 'store'])->name('talents.store');
        Route::get('/talents/{id}/edit', [AdminTalentController::class, 'edit'])->name('talents.edit');
        Route::put('/talents/{id}', [AdminTalentController::class, 'update'])->name('talents.update');
        Route::delete('/talents/{id}', [AdminTalentController::class, 'destroy'])->name('talents.destroy');
        Route::post('/talents/{id}/toggle-status', [AdminTalentController::class, 'toggleStatus'])->name('talents.toggle-status');
        Route::get('/settings/whatsapp', [AdminSettingController::class, 'showWhatsAppSettings'])->name('settings.whatsapp');
        Route::post('/settings/whatsapp', [AdminSettingController::class, 'updateWhatsAppSettings'])->name('settings.whatsapp.update');

        // Manajemen Testimoni
        Route::get('/testimonials', [AdminTestimonialController::class, 'index'])->name('testimonials.index');
        Route::get('/testimonials/create', [AdminTestimonialController::class, 'create'])->name('testimonials.create');
        Route::post('/testimonials', [AdminTestimonialController::class, 'store'])->name('testimonials.store');
        Route::get('/testimonials/{id}/edit', [AdminTestimonialController::class, 'edit'])->name('testimonials.edit');
        Route::put('/testimonials/{id}', [AdminTestimonialController::class, 'update'])->name('testimonials.update');
        Route::post('/testimonials/{id}/toggle-status', [AdminTestimonialController::class, 'toggleStatus'])->name('testimonials.toggle-status');
        Route::delete('/testimonials/{id}', [AdminTestimonialController::class, 'destroy'])->name('testimonials.destroy');

        // Kelola Profil & Password Admin
        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [AdminProfileController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password.update');
    });
});
