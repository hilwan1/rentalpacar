<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $orders = $talent->orders()->with('service')->latest()->take(10)->get();

        return view('talent.dashboard', compact('talent', 'services', 'orders'));
    }

    public function updateProfile(Request $request)
    {
        $talent = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
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
}

