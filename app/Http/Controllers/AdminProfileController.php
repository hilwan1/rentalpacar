<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
{
    public function edit()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses khusus Administrator.');
        }

        $admin = Auth::user();

        return view('admin.profile.edit', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses khusus Administrator.');
        }

        $admin = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email,' . $admin->id],
            'phone' => ['required', 'string', 'max:30'],
        ], [
            'name.required' => 'Nama administrator wajib diisi.',
            'name.max' => 'Nama administrator maksimal 100 karakter.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah dipakai oleh akun lain.',
            'phone.required' => 'Nomor WhatsApp / telepon wajib diisi.',
            'phone.max' => 'Nomor telepon maksimal 30 karakter.',
        ]);

        $admin->update([
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'phone' => trim($validated['phone']),
        ]);

        return back()->with('profile_success', 'Data profil Administrator berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses khusus Administrator.');
        }

        $admin = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'string', 'max:255', 'current_password'],
            'password' => ['required', 'string', 'min:6', 'max:255', 'confirmed'],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini yang Anda masukkan salah.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $admin->update([
            'password' => Hash::make($validated['password']),
        ]);

        $request->session()->regenerate();
        $request->session()->regenerateToken();

        return back()->with('password_success', 'Password akun Administrator berhasil diubah!');
    }
}

