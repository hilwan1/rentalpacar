<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminTalentController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses khusus Administrator.');
        }

        $search = trim($request->query('q', ''));
        $status = $request->query('status', 'all');

        $query = User::where('role', 'talent')
            ->withCount(['services', 'orders']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $talents = $query->latest()
            ->paginate(10)
            ->withQueryString();

        $totalTalents = User::where('role', 'talent')->count();
        $activeCount = User::where('role', 'talent')->where('is_active', true)->count();
        $inactiveCount = User::where('role', 'talent')->where('is_active', false)->count();

        return view('admin.talents.index', compact(
            'talents',
            'search',
            'status',
            'totalTalents',
            'activeCount',
            'inactiveCount'
        ));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses khusus Administrator.');
        }

        return view('admin.talents.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses khusus Administrator.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'city' => ['required', 'string', 'max:100'],
            'bio' => ['required', 'string', 'max:1000'],
            'password' => ['nullable', 'string', 'min:6'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
            'services' => ['required', 'array', 'min:1'],
            'services.*.service_name' => ['required', 'string', 'max:100'],
            'services.*.category' => ['nullable', 'in:basic,premium'],
            'services.*.duration' => ['required', 'string', 'max:50'],
            'services.*.price' => ['required', 'numeric', 'min:0'],
            'services.*.description' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama lengkap atau nama panggung talent wajib diisi.',
            'name.max' => 'Nama talent maksimal 100 karakter.',
            'email.required' => 'Email talent wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar untuk akun lain.',
            'phone.required' => 'Nomor WhatsApp talent wajib diisi.',
            'city.required' => 'Domisili atau kota asal talent wajib diisi.',
            'bio.required' => 'Bio atau deskripsi singkat diri talent wajib diisi.',
            'password.min' => 'Password minimal 6 karakter jika ditentukan.',
            'avatar.image' => 'File foto profil harus berupa file gambar.',
            'avatar.mimes' => 'Format gambar yang diperbolehkan adalah JPG, PNG, atau WEBP.',
            'avatar.max' => 'Ukuran foto profil maksimal 2 MB.',
            'services.required' => 'Minimal tambahkan 1 paket layanan jasa untuk talent ini.',
            'services.min' => 'Minimal tambahkan 1 paket layanan jasa untuk talent ini.',
            'services.*.service_name.required' => 'Nama paket layanan wajib diisi.',
            'services.*.duration.required' => 'Durasi layanan wajib diisi.',
            'services.*.price.required' => 'Tarif harga layanan wajib diisi.',
            'services.*.price.numeric' => 'Tarif harga layanan harus berupa nominal angka.',
            'services.*.price.min' => 'Tarif harga layanan minimal Rp 0.',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $talent = DB::transaction(function () use ($validated, $request, $avatarPath) {
            $password = !empty($validated['password']) ? $validated['password'] : 'password123';

            $newTalent = User::create([
                'name' => $validated['name'],
                'email' => strtolower(trim($validated['email'])),
                'password' => Hash::make($password),
                'role' => 'talent',
                'phone' => trim($validated['phone']),
                'city' => trim($validated['city']),
                'bio' => trim($validated['bio']),
                'avatar' => $avatarPath,
                'is_active' => $request->boolean('is_active', true),
            ]);

            foreach ($validated['services'] as $srv) {
                if (!empty($srv['service_name'])) {
                    Service::create([
                        'talent_id' => $newTalent->id,
                        'service_name' => trim($srv['service_name']),
                        'category' => !empty($srv['category']) && in_array($srv['category'], ['basic', 'premium']) ? $srv['category'] : 'basic',
                        'duration' => trim($srv['duration']),
                        'price' => (int) $srv['price'],
                        'description' => !empty($srv['description']) ? trim($srv['description']) : null,
                        'is_active' => true,
                    ]);
                }
            }

            return $newTalent;
        });

        return redirect()->route('admin.talents.index')->with('success', "Talent {$talent->name} berhasil ditambahkan beserta paket layanannya dan langsung tampil di katalog publik.");
    }

    public function edit($id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses khusus Administrator.');
        }

        $talent = User::where('role', 'talent')
            ->with('services')
            ->findOrFail($id);

        return view('admin.talents.edit', compact('talent'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses khusus Administrator.');
        }

        $talent = User::where('role', 'talent')->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email,' . $talent->id],
            'phone' => ['required', 'string', 'max:20'],
            'city' => ['required', 'string', 'max:100'],
            'bio' => ['required', 'string', 'max:1000'],
            'password' => ['nullable', 'string', 'min:6'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'remove_avatar' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'services' => ['required', 'array', 'min:1'],
            'services.*.id' => ['nullable', 'integer', 'exists:services,id'],
            'services.*.service_name' => ['required', 'string', 'max:100'],
            'services.*.category' => ['nullable', 'in:basic,premium'],
            'services.*.duration' => ['required', 'string', 'max:50'],
            'services.*.price' => ['required', 'numeric', 'min:0'],
            'services.*.description' => ['nullable', 'string', 'max:500'],
            'services.*.is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama lengkap talent wajib diisi.',
            'name.max' => 'Nama talent maksimal 100 karakter.',
            'email.required' => 'Email talent wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar untuk akun lain.',
            'phone.required' => 'Nomor WhatsApp talent wajib diisi.',
            'city.required' => 'Domisili kota talent wajib diisi.',
            'bio.required' => 'Bio atau deskripsi singkat diri talent wajib diisi.',
            'password.min' => 'Password minimal 6 karakter jika ditentukan.',
            'avatar.image' => 'File foto profil harus berupa file gambar.',
            'avatar.mimes' => 'Format gambar yang diperbolehkan adalah JPG, PNG, atau WEBP.',
            'avatar.max' => 'Ukuran foto profil maksimal 2 MB.',
            'services.required' => 'Minimal harus ada 1 paket layanan untuk talent ini.',
            'services.min' => 'Minimal harus ada 1 paket layanan untuk talent ini.',
            'services.*.service_name.required' => 'Nama paket layanan wajib diisi.',
            'services.*.duration.required' => 'Durasi layanan wajib diisi.',
            'services.*.price.required' => 'Tarif harga layanan wajib diisi.',
            'services.*.price.numeric' => 'Tarif harga layanan harus berupa nominal angka.',
            'services.*.price.min' => 'Tarif harga layanan minimal Rp 0.',
        ]);

        DB::transaction(function () use ($request, $validated, $talent) {
            // 1. Tangani avatar
            if ($request->boolean('remove_avatar')) {
                if ($talent->avatar && Storage::disk('public')->exists($talent->avatar)) {
                    Storage::disk('public')->delete($talent->avatar);
                }
                $talent->avatar = null;
            } elseif ($request->hasFile('avatar')) {
                if ($talent->avatar && Storage::disk('public')->exists($talent->avatar)) {
                    Storage::disk('public')->delete($talent->avatar);
                }
                $talent->avatar = $request->file('avatar')->store('avatars', 'public');
            }

            // 2. Update data talent
            $talent->name = trim($validated['name']);
            $talent->email = strtolower(trim($validated['email']));
            $talent->phone = trim($validated['phone']);
            $talent->city = trim($validated['city']);
            $talent->bio = trim($validated['bio']);
            $talent->is_active = $request->boolean('is_active');

            if (!empty($validated['password'])) {
                $talent->password = Hash::make($validated['password']);
            }

            $talent->save();

            // 3. Tangani update / create services
            $submittedServiceIds = [];

            foreach ($validated['services'] as $srvData) {
                if (empty($srvData['service_name'])) {
                    continue;
                }

                $srvId = !empty($srvData['id']) ? (int) $srvData['id'] : null;
                $srvActive = isset($srvData['is_active']) ? (bool) $srvData['is_active'] : true;

                if ($srvId) {
                    $existingService = Service::where('id', $srvId)
                        ->where('talent_id', $talent->id)
                        ->first();

                    if ($existingService) {
                        $existingService->update([
                            'service_name' => trim($srvData['service_name']),
                            'category' => !empty($srvData['category']) && in_array($srvData['category'], ['basic', 'premium']) ? $srvData['category'] : $existingService->category,
                            'duration' => trim($srvData['duration']),
                            'price' => (int) $srvData['price'],
                            'description' => !empty($srvData['description']) ? trim($srvData['description']) : null,
                            'is_active' => $srvActive,
                        ]);
                        $submittedServiceIds[] = $existingService->id;
                    }
                } else {
                    $newService = Service::create([
                        'talent_id' => $talent->id,
                        'service_name' => trim($srvData['service_name']),
                        'category' => !empty($srvData['category']) && in_array($srvData['category'], ['basic', 'premium']) ? $srvData['category'] : 'basic',
                        'duration' => trim($srvData['duration']),
                        'price' => (int) $srvData['price'],
                        'description' => !empty($srvData['description']) ? trim($srvData['description']) : null,
                        'is_active' => $srvActive,
                    ]);
                    $submittedServiceIds[] = $newService->id;
                }
            }

            // Hapus atau nonaktifkan service lama yang dibuang dari form
            $servicesToDelete = Service::where('talent_id', $talent->id)
                ->whereNotIn('id', $submittedServiceIds)
                ->get();

            foreach ($servicesToDelete as $oldSrv) {
                if ($oldSrv->orders()->exists()) {
                    $oldSrv->update(['is_active' => false]);
                } else {
                    $oldSrv->delete();
                }
            }
        });

        return redirect()->route('admin.talents.index')->with('success', "Data profil dan paket layanan talent {$talent->name} berhasil diperbarui.");
    }

    public function toggleStatus(Request $request, $id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses khusus Administrator.');
        }

        $talent = User::where('role', 'talent')->findOrFail($id);
        $talent->is_active = !$talent->is_active;
        $talent->save();

        $statusText = $talent->is_active ? 'diaktifkan dan dapat tampil di katalog publik' : 'dinonaktifkan dari katalog publik';

        return back()->with('success', "Status akun talent {$talent->name} berhasil {$statusText}.");
    }

    public function destroy($id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses khusus Administrator.');
        }

        $talent = User::where('role', 'talent')->findOrFail($id);
        $talentName = $talent->name;

        DB::transaction(function () use ($talent) {
            // Hapus riwayat pesanan yang terhubung dengan talent ini
            Order::where('talent_id', $talent->id)->delete();

            // Hapus seluruh paket layanan milik talent
            Service::where('talent_id', $talent->id)->delete();

            // Hapus foto profil dari storage publik jika bukan URL eksternal
            if ($talent->avatar && !str_starts_with($talent->avatar, 'http://') && !str_starts_with($talent->avatar, 'https://')) {
                Storage::disk('public')->delete($talent->avatar);
            }

            // Hapus akun talent
            $talent->delete();
        });

        return redirect()->route('admin.talents.index')->with('success', "Akun talent {$talentName} beserta seluruh paket layanannya berhasil dihapus.");
    }
}

