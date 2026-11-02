<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCKOUT_DECAY_SECONDS = 60;

    public function showLoginForm()
    {
        return redirect()->route('talent.login');
    }

    public function showTalentLoginForm()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('talent.dashboard');
        }

        return view('auth.talent-login');
    }

    public function talentLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|max:150',
            'password' => 'required|string|max:255',
        ]);

        $email = strtolower(trim($credentials['email']));
        $throttleKey = $this->throttleKey($request, 'talent-login', $email);

        $this->ensureIsNotRateLimited($request, $throttleKey);

        if (Auth::attempt(['email' => $email, 'password' => $credentials['password'], 'role' => 'talent'], $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            $request->session()->regenerateToken();

            return redirect()->intended(route('talent.dashboard'));
        }

        RateLimiter::hit($throttleKey, self::LOCKOUT_DECAY_SECONDS);

        return back()->withErrors([
            'email' => 'Kombinasi email atau kata sandi tidak cocok.',
        ])->onlyInput('email');
    }

    public function showTalentRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->isAdmin() ? 'admin.dashboard' : 'talent.dashboard');
        }

        return view('auth.talent-register');
    }

    public function talentRegister(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email',
            'phone' => 'required|string|max:30',
            'city' => 'nullable|string|max:100',
            'password' => 'required|string|min:6|max:255|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'name.required' => 'Nama atau nama panggung wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'avatar.image' => 'File harus berupa gambar.',
            'avatar.mimes' => 'Format foto harus berupa JPG, PNG, atau WebP.',
            'avatar.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'phone' => trim($validated['phone']),
            'city' => isset($validated['city']) ? trim($validated['city']) : null,
            'password' => Hash::make($validated['password']),
            'avatar' => $avatarPath,
            'role' => 'talent',
            'is_active' => true,
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->regenerateToken();

        return redirect()->route('talent.dashboard')->with('success', 'Pendaftaran berhasil! Selamat datang di RentalPacar.');
    }

    public function showAdminLoginForm()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('talent.dashboard');
        }

        return view('auth.admin-login');
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|max:150',
            'password' => 'required|string|max:255',
        ]);

        $email = strtolower(trim($credentials['email']));
        $throttleKey = $this->throttleKey($request, 'admin-login', $email);

        $this->ensureIsNotRateLimited($request, $throttleKey);

        if (Auth::attempt(['email' => $email, 'password' => $credentials['password'], 'role' => 'admin'], $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            $request->session()->regenerateToken();

            return redirect()->intended(route('admin.dashboard'));
        }

        RateLimiter::hit($throttleKey, self::LOCKOUT_DECAY_SECONDS);

        return back()->withErrors([
            'email' => 'Kombinasi email atau kata sandi admin tidak cocok.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function throttleKey(Request $request, string $portal, string $email): string
    {
        return Str::transliterate($portal . '|' . $email . '|' . $request->ip());
    }

    private function ensureIsNotRateLimited(Request $request, string $throttleKey): void
    {
        if (!RateLimiter::tooManyAttempts($throttleKey, self::MAX_LOGIN_ATTEMPTS)) {
            return;
        }

        event(new Lockout($request));

        $seconds = RateLimiter::availableIn($throttleKey);

        throw ValidationException::withMessages([
            'email' => "Terlalu banyak percobaan masuk yang gagal. Silakan coba lagi dalam {$seconds} detik.",
        ]);
    }
}
