<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TalentDashboardController extends Controller
{
    public function index()
    {
        $talent = Auth::user();

        if (!$talent->isTalent()) {
            return redirect()->route('admin.dashboard');
        }

        $services = Service::where('talent_id', $talent->id)->latest()->get();
        $totalOrdersCount = $talent->orders()->count();
        $orders = $talent->orders()->with('service')->latest()->take(5)->get();

        return view('talent.dashboard', compact('talent', 'services', 'orders', 'totalOrdersCount'));
    }

    public function updateProfile(Request $request)
    {
        $talent = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
            'city' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'remove_avatar' => 'nullable|boolean',
        ], [
            'name.required' => 'Nama tampilan wajib diisi.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'avatar.image' => 'File yang diunggah harus berupa gambar.',
            'avatar.mimes' => 'Format foto harus berupa JPG, PNG, atau WebP.',
            'avatar.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'city' => $validated['city'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->boolean('remove_avatar')) {
            if ($talent->avatar && Storage::disk('public')->exists($talent->avatar)) {
                Storage::disk('public')->delete($talent->avatar);
            }
            $updateData['avatar'] = null;
        } elseif ($request->hasFile('avatar')) {
            if ($talent->avatar && Storage::disk('public')->exists($talent->avatar)) {
                Storage::disk('public')->delete($talent->avatar);
            }
            $updateData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $talent->update($updateData);

        return back()->with('success', 'Profil dan foto profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $talent = Auth::user();

        if (!$talent->isTalent()) {
            abort(403, 'Akses khusus Talent.');
        }

        $validated = $request->validate([
            'current_password' => ['required', 'string', 'max:255', 'current_password'],
            'password' => ['required', 'string', 'min:6', 'max:255', 'confirmed'],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini yang kamu masukkan salah.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $talent->update([
            'password' => Hash::make($validated['password']),
        ]);

        $request->session()->regenerate();
        $request->session()->regenerateToken();

        return back()->with('success', 'Password akun talent berhasil diubah!');
    }

    public function storeService(Request $request)
    {
        $talent = Auth::user();

        $validated = $request->validate([
            'service_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:300',
            'duration' => 'required|string|max:50',
            'price' => 'required|integer|min:5000',
        ]);

        Service::create([
            'talent_id' => $talent->id,
            'service_name' => $validated['service_name'],
            'description' => $validated['description'] ?? null,
            'duration' => $validated['duration'],
            'price' => $validated['price'],
            'is_active' => true,
        ]);

        return back()->with('success', 'Paket layanan berhasil ditambahkan!');
    }

    public function toggleService($id)
    {
        $talent = Auth::user();
        $service = Service::where('id', $id)->where('talent_id', $talent->id)->firstOrFail();

        $service->update([
            'is_active' => !$service->is_active,
        ]);

        $statusText = $service->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Layanan {$service->service_name} berhasil {$statusText}!");
    }

    public function destroyService($id)
    {
        $talent = Auth::user();
        $service = Service::where('id', $id)->where('talent_id', $talent->id)->firstOrFail();
        $serviceName = $service->service_name;

        if ($service->orders()->exists()) {
            $service->update(['is_active' => false]);
            return back()->with('success', "Paket layanan {$serviceName} memiliki riwayat transaksi pelanggan, sehingga statusnya dinonaktifkan.");
        }

        $service->delete();

        return back()->with('success', "Paket layanan {$serviceName} berhasil dihapus.");
    }
}

