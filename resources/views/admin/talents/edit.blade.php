<x-admin-layout title="Edit Talent: {{ $talent->name }} - RentalPacar Admin" header="Edit Talent">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb & Header -->
        <div class="flex items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="text-xs text-slate-400 hover:text-rose-600 transition">Dashboard</a>
                    <span class="text-xs text-slate-300">/</span>
                    <a href="{{ route('admin.talents.index') }}" class="text-xs text-slate-400 hover:text-rose-600 transition">Talent</a>
                    <span class="text-xs text-slate-300">/</span>
                    <span class="text-xs font-bold text-rose-600">Edit: {{ $talent->name }}</span>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Edit Talent: {{ $talent->name }}</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Kelola profil lengkap talent, perbarui foto, domisili daerah, serta edit dan kelola paket layanan jasanya.
                </p>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('talents.show', $talent->id) }}" target="_blank"
                   class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    <span>Halaman Publik</span>
                </a>

                <a href="{{ route('admin.talents.index') }}"
                   class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali</span>
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-700 shadow-xs">
                <div class="font-bold mb-1">Harap perbaiki kesalahan berikut sebelum menyimpan:</div>
                <ul class="list-disc list-inside space-y-0.5 text-[11px]">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 flex items-center gap-2 shadow-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-600 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.talents.update', $talent->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Bagian 1: Data Profil Talent -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">1. Data Profil Talent</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Identitas, domisili, foto profil, dan status publikasi.</p>
                    </div>

                    <div class="flex items-center gap-2">
                        @if ($talent->is_active)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span>Katalog Aktif</span>
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                <span>Katalog Nonaktif</span>
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Foto Profil Talent dengan Live Cropper & Preview -->
                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-2">
                        Foto Profil Talent <span class="text-slate-400 font-normal">(Crop 1:1 & Kompresi Otomatis)</span>
                    </label>

                    <div class="flex flex-col sm:flex-row items-center gap-5 p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                        <!-- Preview Avatar -->
                        <div class="relative w-24 h-24 rounded-full overflow-hidden bg-rose-100 ring-4 ring-white shadow-md flex items-center justify-center shrink-0">
                            @if ($talent->avatar_url)
                                <img id="avatar-preview" src="{{ $talent->avatar_url }}" alt="{{ $talent->name }}" class="w-full h-full object-cover">
                                <div id="avatar-placeholder" class="text-rose-400 hidden flex flex-col items-center justify-center">
                                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            @else
                                <img id="avatar-preview" src="#" alt="Preview Foto" class="w-full h-full object-cover hidden">
                                <div id="avatar-placeholder" class="text-rose-400 flex flex-col items-center justify-center">
                                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Kontrol Upload & Hapus Foto -->
                        <div class="flex-1 text-center sm:text-left space-y-2">
                            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                                <label for="avatar" class="cursor-pointer px-4 py-2 bg-white hover:bg-slate-100 text-slate-700 text-xs font-semibold rounded-xl border border-slate-200 transition shadow-2xs inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>Pilih & Potong Foto Baru</span>
                                </label>
                                <input type="file" id="avatar" name="avatar" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="handleAvatarSelection(this)">

                                @if ($talent->avatar_url)
                                    <label class="cursor-pointer px-3.5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold rounded-xl border border-rose-200 transition inline-flex items-center gap-1.5">
                                        <input type="checkbox" name="remove_avatar" value="1" class="w-3.5 h-3.5 rounded border-rose-300 text-rose-600 focus:ring-rose-500">
                                        <span>Hapus Foto Profil</span>
                                    </label>
                                @endif

                                <button type="button" id="btn-cancel-new-avatar" onclick="cancelNewAvatar()" class="hidden px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition">
                                    Batalkan Foto Baru
                                </button>
                            </div>
                            <p class="text-[11px] text-slate-400">Pilih foto baru untuk membuka alat crop 1:1 dan kompresi otomatis.</p>
                            <p id="avatar-filename" class="text-[11px] text-slate-600 font-medium"></p>
                        </div>
                    </div>
                    @error('avatar')
                        <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Input Grid Profil -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Nama Talent -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-800 mb-1.5">
                            Nama Lengkap / Panggilan <span class="text-rose-600">*</span>
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name', $talent->name) }}"
                               required
                               placeholder="Contoh: Jessica atau Clarissa"
                               class="w-full px-4 py-2.5 text-xs bg-slate-50 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:bg-white text-slate-800 transition @error('name') border-rose-400 bg-rose-50/30 @enderror">
                        @error('name')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Domisili Daerah / Kota -->
                    <div>
                        <label for="city" class="block text-xs font-bold text-slate-800 mb-1.5">
                            Domisili Daerah / Kota <span class="text-rose-600">*</span>
                        </label>
                        <input type="text"
                               id="city"
                               name="city"
                               value="{{ old('city', $talent->city) }}"
                               required
                               placeholder="Contoh: Jakarta Selatan, Bandung, Surabaya"
                               class="w-full px-4 py-2.5 text-xs bg-slate-50 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:bg-white text-slate-800 transition @error('city') border-rose-400 bg-rose-50/30 @enderror">
                        <p class="text-[11px] text-slate-400 mt-1">Digunakan untuk filter pencarian domisili di katalog.</p>
                        @error('city')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor WhatsApp -->
                    <div>
                        <label for="phone" class="block text-xs font-bold text-slate-800 mb-1.5">
                            Nomor WhatsApp Talent <span class="text-rose-600">*</span>
                        </label>
                        <input type="text"
                               id="phone"
                               name="phone"
                               value="{{ old('phone', $talent->phone) }}"
                               required
                               placeholder="Contoh: 081234567890"
                               class="w-full px-4 py-2.5 text-xs bg-slate-50 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:bg-white text-slate-800 transition @error('phone') border-rose-400 bg-rose-50/30 @enderror">
                        @error('phone')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Akun Talent -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-800 mb-1.5">
                            Email Akun Talent <span class="text-rose-600">*</span>
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email', $talent->email) }}"
                               required
                               placeholder="Contoh: talent@rentalpacar.com"
                               class="w-full px-4 py-2.5 text-xs bg-slate-50 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:bg-white text-slate-800 transition @error('email') border-rose-400 bg-rose-50/30 @enderror">
                        @error('email')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Password Akun Talent Baru -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-800 mb-1.5">
                        Ganti Password Akun <span class="text-slate-400 font-normal">(Kosongkan jika tidak ingin mengubah password)</span>
                    </label>
                    <input type="text"
                           id="password"
                           name="password"
                           value="{{ old('password') }}"
                           placeholder="Masukkan password baru (minimal 6 karakter)"
                           class="w-full sm:w-1/2 px-4 py-2.5 text-xs bg-slate-50 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:bg-white text-slate-800 transition @error('password') border-rose-400 bg-rose-50/30 @enderror">
                    @error('password')
                        <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bio / Deskripsi Diri -->
                <div>
                    <label for="bio" class="block text-xs font-bold text-slate-800 mb-1.5">
                        Bio & Deskripsi Diri <span class="text-rose-600">*</span>
                    </label>
                    <textarea id="bio"
                              name="bio"
                              rows="3"
                              required
                              placeholder="Ceritakan kepribadian, gaya komunikasi, hobi, topik obrolan favorit, atau kebiasaan seru talent..."
                              class="w-full px-4 py-2.5 text-xs bg-slate-50 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:bg-white text-slate-800 transition leading-relaxed @error('bio') border-rose-400 bg-rose-50/30 @enderror">{{ old('bio', $talent->bio) }}</textarea>
                    @error('bio')
                        <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Checkbox Status Aktif -->
                <div class="pt-2">
                    <label class="relative flex items-center gap-3 cursor-pointer select-none">
                        <input type="checkbox"
                               name="is_active"
                               value="1"
                               {{ old('is_active', $talent->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-slate-300 text-rose-600 focus:ring-rose-500 cursor-pointer">
                        <span class="text-xs font-bold text-slate-800">
                            Aktifkan Akun & Tampilkan di Katalog Publik
                        </span>
                    </label>
                </div>
            </div>

            <!-- Bagian 2: Paket Jasa / Layanan Talent (Kelola Langsung dari Admin) -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">2. Paket Jasa / Layanan Talent</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Admin dapat menambah, mengedit tarif harga, durasi, nama layanan, atau menghapus paket jasa di bawah ini.</p>
                    </div>

                    <button type="button"
                            onclick="addServiceRow()"
                            class="px-4 py-2 bg-rose-50 hover:bg-rose-100 active:scale-95 text-rose-700 text-xs font-bold rounded-xl transition inline-flex items-center gap-1.5 shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Tambah Layanan Baru</span>
                    </button>
                </div>

                <!-- Container Baris Layanan -->
                <div id="services-container" class="space-y-4">
                    @php
                        $servicesData = old('services');
                        if (!$servicesData) {
                            $servicesData = $talent->services->map(function ($s) {
                                return [
                                    'id' => $s->id,
                                    'service_name' => $s->service_name,
                                    'category' => $s->category ?? 'basic',
                                    'duration' => $s->duration,
                                    'price' => $s->price,
                                    'description' => $s->description,
                                    'is_active' => $s->is_active ? 1 : 0,
                                ];
                            })->toArray();

                            if (empty($servicesData)) {
                                $servicesData = [
                                    [
                                        'id' => null,
                                        'service_name' => 'Chat Seharian',
                                        'category' => 'basic',
                                        'duration' => '1 Hari',
                                        'price' => 35000,
                                        'description' => '',
                                        'is_active' => 1,
                                    ]
                                ];
                            }
                        }
                    @endphp

                    @foreach ($servicesData as $index => $srv)
                        <div class="service-item bg-slate-50/70 p-4 sm:p-5 rounded-2xl border border-slate-200/80 space-y-3 relative transition" data-index="{{ $index }}">
                            @if (!empty($srv['id']))
                                <input type="hidden" name="services[{{ $index }}][id]" value="{{ $srv['id'] }}">
                            @endif

                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-slate-700 service-number">Paket Layanan #{{ $index + 1 }}</span>
                                    @if (!empty($srv['id']))
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-200/80 text-slate-600">ID: {{ $srv['id'] }}</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-emerald-100 text-emerald-800">Paket Baru</span>
                                    @endif
                                </div>

                                <div class="flex items-center gap-3">
                                    <!-- Toggle Status Layanan -->
                                    <label class="inline-flex items-center gap-1.5 cursor-pointer text-xs text-slate-600 select-none">
                                        <input type="checkbox"
                                               name="services[{{ $index }}][is_active]"
                                               value="1"
                                               {{ (!isset($srv['is_active']) || $srv['is_active']) ? 'checked' : '' }}
                                               class="w-3.5 h-3.5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                        <span class="text-[11px] font-medium">Layanan Aktif</span>
                                    </label>

                                    <!-- Tombol Hapus -->
                                    <button type="button"
                                            onclick="removeServiceRow(this)"
                                            class="btn-remove-service text-slate-400 hover:text-rose-600 p-1 rounded-lg hover:bg-rose-50 transition text-xs font-semibold inline-flex items-center gap-1 {{ count($servicesData) === 1 ? 'hidden' : '' }}"
                                            title="Hapus layanan ini">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        <span class="text-[11px]">Hapus</span>
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">
                                        Nama Layanan <span class="text-rose-600">*</span>
                                    </label>
                                    <input type="text"
                                           name="services[{{ $index }}][service_name]"
                                           value="{{ $srv['service_name'] ?? '' }}"
                                           required
                                           placeholder="Contoh: Voice Call 1 Jam"
                                           class="w-full px-3.5 py-2 text-xs bg-white rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 text-slate-800 transition">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">
                                        Kategori <span class="text-rose-600">*</span>
                                    </label>
                                    <select name="services[{{ $index }}][category]"
                                            class="w-full px-3 py-2 text-xs bg-white rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 text-slate-800 transition font-semibold">
                                        <option value="basic" {{ ($srv['category'] ?? 'basic') === 'basic' ? 'selected' : '' }}>Basic</option>
                                        <option value="premium" {{ ($srv['category'] ?? '') === 'premium' ? 'selected' : '' }}>⭐ Premium</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">
                                        Durasi Layanan <span class="text-rose-600">*</span>
                                    </label>
                                    <input type="text"
                                           name="services[{{ $index }}][duration]"
                                           value="{{ $srv['duration'] ?? '' }}"
                                           required
                                           placeholder="Contoh: 60 Menit / 1 Hari"
                                           class="w-full px-3.5 py-2 text-xs bg-white rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 text-slate-800 transition">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">
                                        Tarif Harga (Rp) <span class="text-rose-600">*</span>
                                    </label>
                                    <input type="number"
                                           name="services[{{ $index }}][price]"
                                           value="{{ $srv['price'] ?? '' }}"
                                           required
                                           min="0"
                                           step="1000"
                                           placeholder="Contoh: 50000"
                                           class="w-full px-3.5 py-2 text-xs bg-white rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 text-slate-800 transition">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">
                                    Deskripsi Singkat Layanan <span class="text-slate-400 font-normal">(Opsional)</span>
                                </label>
                                <input type="text"
                                       name="services[{{ $index }}][description]"
                                       value="{{ $srv['description'] ?? '' }}"
                                       placeholder="Contoh: Obrolan santai via Discord atau WA Call."
                                       class="w-full px-3.5 py-2 text-xs bg-white rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 text-slate-800 transition">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Tombol Aksi Simpan -->
            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.talents.index') }}"
                   class="w-full sm:w-auto px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition text-center">
                    Batal
                </a>
                <button type="submit"
                        class="w-full sm:w-auto px-6 py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white text-xs font-bold rounded-xl shadow-md shadow-rose-600/25 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Simpan Perubahan Talent & Layanan</span>
                </button>
            </div>
        </form>
    </div>

    @include('components.image-cropper-modal')

    <!-- Script Javascript untuk Avatar Crop & Dynamic Service Repeater -->
    <script>
        const originalAvatarSrc = "{{ $talent->avatar_url ?? '' }}";

        function handleAvatarSelection(input) {
            const file = input.files?.[0];
            if (!file) return;

            if (window.openImageCropper) {
                window.openImageCropper(file, input, function(previewUrl, croppedFile, sizeKb) {
                    const preview = document.getElementById('avatar-preview');
                    const placeholder = document.getElementById('avatar-placeholder');
                    const cancelBtn = document.getElementById('btn-cancel-new-avatar');
                    const fileNameText = document.getElementById('avatar-filename');

                    preview.src = previewUrl;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    cancelBtn.classList.remove('hidden');
                    fileNameText.innerHTML = '<span class="text-emerald-600 font-semibold">✓ Foto baru terpotong & terkompresi (' + sizeKb + ' KB)</span>';
                });
            } else {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('avatar-preview');
                    const placeholder = document.getElementById('avatar-placeholder');
                    const cancelBtn = document.getElementById('btn-cancel-new-avatar');
                    const fileNameText = document.getElementById('avatar-filename');
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    cancelBtn.classList.remove('hidden');
                    fileNameText.textContent = file.name + ' (' + Math.round(file.size / 1024) + ' KB)';
                };
                reader.readAsDataURL(file);
            }
        }

        function cancelNewAvatar() {
            const input = document.getElementById('avatar');
            const preview = document.getElementById('avatar-preview');
            const placeholder = document.getElementById('avatar-placeholder');
            const cancelBtn = document.getElementById('btn-cancel-new-avatar');
            const fileNameText = document.getElementById('avatar-filename');

            input.value = '';
            if (originalAvatarSrc) {
                preview.src = originalAvatarSrc;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                preview.src = '#';
                preview.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }
            cancelBtn.classList.add('hidden');
            fileNameText.textContent = '';
        }

        let serviceCounter = {{ count($servicesData) }};

        function addServiceRow() {
            const container = document.getElementById('services-container');
            const newIndex = serviceCounter++;

            const newCard = document.createElement('div');
            newCard.className = 'service-item bg-slate-50/70 p-4 sm:p-5 rounded-2xl border border-slate-200/80 space-y-3 relative transition';
            newCard.dataset.index = newIndex;

            newCard.innerHTML = `
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-slate-700 service-number">Paket Layanan #${container.children.length + 1}</span>
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-emerald-100 text-emerald-800">Paket Baru</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <label class="inline-flex items-center gap-1.5 cursor-pointer text-xs text-slate-600 select-none">
                            <input type="checkbox"
                                   name="services[${newIndex}][is_active]"
                                   value="1"
                                   checked
                                   class="w-3.5 h-3.5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            <span class="text-[11px] font-medium">Layanan Aktif</span>
                        </label>

                        <button type="button"
                                onclick="removeServiceRow(this)"
                                class="btn-remove-service text-slate-400 hover:text-rose-600 p-1 rounded-lg hover:bg-rose-50 transition text-xs font-semibold inline-flex items-center gap-1"
                                title="Hapus layanan ini">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span class="text-[11px]">Hapus</span>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">
                            Nama Layanan <span class="text-rose-600">*</span>
                        </label>
                        <input type="text"
                               name="services[${newIndex}][service_name]"
                               required
                               placeholder="Contoh: Voice Call 1 Jam"
                               class="w-full px-3.5 py-2 text-xs bg-white rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 text-slate-800 transition">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">
                            Kategori <span class="text-rose-600">*</span>
                        </label>
                        <select name="services[${newIndex}][category]"
                                class="w-full px-3 py-2 text-xs bg-white rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 text-slate-800 transition font-semibold">
                            <option value="basic">Basic</option>
                            <option value="premium">⭐ Premium</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">
                            Durasi Layanan <span class="text-rose-600">*</span>
                        </label>
                        <input type="text"
                               name="services[${newIndex}][duration]"
                               required
                               placeholder="Contoh: 60 Menit"
                               class="w-full px-3.5 py-2 text-xs bg-white rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 text-slate-800 transition">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">
                            Tarif Harga (Rp) <span class="text-rose-600">*</span>
                        </label>
                        <input type="number"
                               name="services[${newIndex}][price]"
                               required
                               min="0"
                               step="1000"
                               placeholder="Contoh: 50000"
                               class="w-full px-3.5 py-2 text-xs bg-white rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 text-slate-800 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-1">
                        Deskripsi Singkat Layanan <span class="text-slate-400 font-normal">(Opsional)</span>
                    </label>
                    <input type="text"
                           name="services[${newIndex}][description]"
                           placeholder="Contoh: Obrolan santai via Discord / WA Call."
                           class="w-full px-3.5 py-2 text-xs bg-white rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 text-slate-800 transition">
                </div>
            `;

            container.appendChild(newCard);
            refreshServiceRowNumbers();
        }

        function removeServiceRow(btn) {
            const card = btn.closest('.service-item');
            const container = document.getElementById('services-container');

            if (container.querySelectorAll('.service-item').length > 1) {
                card.remove();
                refreshServiceRowNumbers();
            }
        }

        function refreshServiceRowNumbers() {
            const container = document.getElementById('services-container');
            const items = container.querySelectorAll('.service-item');

            items.forEach((item, idx) => {
                const label = item.querySelector('.service-number');
                if (label) label.textContent = 'Paket Layanan #' + (idx + 1);

                const removeBtn = item.querySelector('.btn-remove-service');
                if (removeBtn) {
                    if (items.length === 1) {
                        removeBtn.classList.add('hidden');
                    } else {
                        removeBtn.classList.remove('hidden');
                    }
                }
            });
        }
    </script>
</x-admin-layout>

