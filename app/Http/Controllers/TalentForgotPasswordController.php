<?php

namespace App\Http\Controllers;

use App\Mail\TalentOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class TalentForgotPasswordController extends Controller
{
    /**
     * Tampilkan formulir permintaan OTP reset password khusus talent.
     */
    public function showForgotForm()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('talent.dashboard');
        }

        return view('auth.talent-forgot-password');
    }

    /**
     * Kirim kode OTP 6-digit ke email talent yang valid.
     */
    public function sendOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
        ]);

        $email = strtolower(trim($validated['email']));

        // Validasi: hanya untuk akun dengan role 'talent'
        $talent = User::where('email', $email)->first();

        if (!$talent || !$talent->isTalent()) {
            return back()->withErrors([
                'email' => 'Alamat email tidak terdaftar sebagai akun Talent.',
            ])->withInput();
        }

        if (!$talent->is_active) {
            return back()->withErrors([
                'email' => 'Akun Talent Anda sedang dinonaktifkan. Silakan hubungi admin.',
            ])->withInput();
        }

        // Generate 6 digit angka acak (100000 - 999999)
        $otp = (string) random_int(100000, 999999);

        // Simpan token OTP (dalam bentuk hash) ke tabel password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => Hash::make($otp),
                'created_at' => now(),
            ]
        );

        // Kirim email OTP
        Mail::to($talent->email)->send(new TalentOtpMail($talent, $otp));

        // Simpan ke session untuk langkah verifikasi berikutnya
        session([
            'talent_reset_email' => $email,
            'talent_otp_sent_at' => now()->timestamp,
        ]);

        return redirect()->route('talent.password.reset')->with(
            'status',
            'Kode OTP 6-digit telah dikirim ke email ' . $email . '. Silakan periksa inbox atau folder spam Anda.'
        );
    }

    /**
     * Tampilkan formulir verifikasi OTP dan input kata sandi baru.
     */
    public function showResetForm()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('talent.dashboard');
        }

        $email = session('talent_reset_email');

        if (!$email) {
            return redirect()->route('talent.password.request')->withErrors([
                'email' => 'Silakan masukkan email akun Talent Anda terlebih dahulu.',
            ]);
        }

        return view('auth.talent-reset-password', compact('email'));
    }

    /**
     * Verifikasi kode OTP dan perbarui kata sandi akun talent.
     */
    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'digits:6'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'email.required' => 'Email wajib disertakan.',
            'email.email' => 'Format email tidak valid.',
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.digits' => 'Kode OTP harus berupa 6 digit angka.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $email = strtolower(trim($validated['email']));

        // Ambil data token reset
        $tokenRecord = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$tokenRecord) {
            return back()->withErrors([
                'otp' => 'Sesi reset password tidak ditemukan atau telah kedaluwarsa. Silakan minta kode OTP baru.',
            ])->withInput($request->only('email'));
        }

        // Cek kedaluwarsa OTP (15 menit)
        if (Carbon::parse($tokenRecord->created_at)->addMinutes(15)->isPast()) {
            return back()->withErrors([
                'otp' => 'Kode OTP telah kedaluwarsa (melebihi batas 15 menit). Silakan minta kode OTP baru.',
            ])->withInput($request->only('email'));
        }

        // Cek kecocokan OTP
        if (!Hash::check($validated['otp'], $tokenRecord->token)) {
            return back()->withErrors([
                'otp' => 'Kode OTP yang Anda masukkan tidak sesuai.',
            ])->withInput($request->only('email'));
        }

        // Cari talent dan update kata sandi
        $talent = User::where('email', $email)->first();

        if (!$talent || !$talent->isTalent()) {
            return redirect()->route('talent.password.request')->withErrors([
                'email' => 'Akun Talent tidak valid.',
            ]);
        }

        $talent->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Bersihkan token & session
        DB::table('password_reset_tokens')->where('email', $email)->delete();
        session()->forget(['talent_reset_email', 'talent_otp_sent_at']);

        return redirect()->route('talent.login')->with(
            'success',
            'Kata sandi akun Talent Anda berhasil diatur ulang! Silakan masuk menggunakan kata sandi baru.'
        );
    }

    /**
     * Kirim ulang kode OTP dengan cooldown 60 detik.
     */
    public function resendOtp(Request $request)
    {
        $email = session('talent_reset_email') ?: strtolower(trim($request->input('email', '')));

        if (!$email) {
            return redirect()->route('talent.password.request')->withErrors([
                'email' => 'Silakan masukkan email akun Talent Anda terlebih dahulu.',
            ]);
        }

        // Cooldown cek (60 detik)
        $lastSent = session('talent_otp_sent_at');
        if ($lastSent && (now()->timestamp - $lastSent) < 60) {
            $remaining = 60 - (now()->timestamp - $lastSent);
            return back()->withErrors([
                'resend' => "Mohon tunggu {$remaining} detik sebelum meminta kode OTP baru.",
            ]);
        }

        $talent = User::where('email', $email)->first();

        if (!$talent || !$talent->isTalent()) {
            return redirect()->route('talent.password.request')->withErrors([
                'email' => 'Alamat email tidak terdaftar sebagai akun Talent.',
            ]);
        }

        if (!$talent->is_active) {
            return redirect()->route('talent.password.request')->withErrors([
                'email' => 'Akun Talent Anda sedang dinonaktifkan.',
            ]);
        }

        $otp = (string) random_int(100000, 999999);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => Hash::make($otp),
                'created_at' => now(),
            ]
        );

        Mail::to($talent->email)->send(new TalentOtpMail($talent, $otp));

        session([
            'talent_reset_email' => $email,
            'talent_otp_sent_at' => now()->timestamp,
        ]);

        return back()->with(
            'status',
            'Kode OTP baru berhasil dikirim ulang ke email ' . $email . '. Cek inbox atau folder spam.'
        );
    }
}

