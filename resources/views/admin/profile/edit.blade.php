<x-admin-layout title="Kelola Profil & Password - RentalPacar Admin" header="Profil Administrator">
    <div class="space-y-6 max-w-5xl">
        <!-- Top Header & Breadcrumb -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <a href="{{ route('admin.dashboard') }}" class="text-xs text-slate-400 hover:text-rose-600 transition">Dashboard</a>
                    <span class="text-xs text-slate-300">/</span>
                    <span class="text-xs font-bold text-rose-600">Profil & Keamanan</span>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Kelola Profil Administrator</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Perbarui informasi identitas akun administrator serta kelola kata sandi akun untuk menjaga keamanan akses sistem.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-900 text-white text-xs font-bold shadow-xs">
                    <svg class="w-3.5 h-3.5 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span>Hak Akses: Administrator</span>
                </span>
            </div>
        </div>

        <!-- Ringkasan Akun Administrator Aktif -->
        <div class="bg-linear-to-r from-slate-900 via-slate-800 to-rose-950 rounded-3xl p-6 text-white shadow-xs relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-44 h-44 rounded-full bg-rose-500/10 blur-2xl pointer-events-none"></div>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-white/10 border border-white/20 backdrop-blur-md text-white flex items-center justify-center font-extrabold text-xl shadow-inner">
                        {{ strtoupper(substr($admin->name ?? 'AD', 0, 2)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-base sm:text-lg font-bold text-white">{{ $admin->name }}</h2>
                            <span class="px-2 py-0.5 rounded-md bg-rose-500/20 text-rose-300 text-[10px] font-bold border border-rose-500/30">Superadmin</span>
                        </div>
                        <p class="text-xs text-slate-300 mt-0.5">{{ $admin->email }}</p>
                        <p class="text-[11px] text-slate-400 mt-1">Kontak WhatsApp: {{ $admin->phone ?? 'Belum diatur' }}</p>
                    </div>
                </div>

                <div class="flex flex-col sm:items-end text-xs text-slate-300 border-t sm:border-t-0 border-white/10 pt-3 sm:pt-0">
                    <span class="text-slate-400 text-[11px]">Terdaftar sejak:</span>
                    <span class="font-semibold text-white">{{ $admin->created_at ? $admin->created_at->translatedFormat('d F Y') : 'Sistem Utama' }}</span>
                </div>
            </div>
        </div>

        <!-- Grid 2 Kolom Formulir -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
            
            <!-- Kolom 1: Formulir Ubah Data Profil -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900">Data Profil Admin</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Ubah nama, email, dan nomor kontak admin.</p>
                    </div>
                    <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>

                @if (session('profile_success'))
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 flex items-center gap-2.5 shadow-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-600 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-semibold">{{ session('profile_success') }}</span>
                    </div>
                @endif

                @if ($errors->has('name') || $errors->has('email') || $errors->has('phone'))
                    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-700 shadow-xs">
                        <p class="font-bold mb-1">Gagal memperbarui profil:</p>
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach (['name', 'email', 'phone'] as $field)
                                @error($field)
                                    <li>{{ $message }}</li>
                                @enderror
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nama Lengkap Administrator <span class="text-rose-600">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               id="name"
                               required
                               value="{{ old('name', $admin->name) }}"
                               placeholder="Nama lengkap administrator"
                               class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 @error('name') border-rose-400 bg-rose-50/40 @enderror">
                        <p class="text-[11px] text-slate-400 mt-1">Nama ini akan tampil di panel navigasi dan laporan sistem.</p>
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Alamat Email (Login) <span class="text-rose-600">*</span>
                        </label>
                        <input type="email"
                               name="email"
                               id="email"
                               required
                               value="{{ old('email', $admin->email) }}"
                               placeholder="admin@rentalpacar.com"
                               class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 @error('email') border-rose-400 bg-rose-50/40 @enderror">
                        <p class="text-[11px] text-slate-400 mt-1">Email digunakan untuk otentikasi login administrator.</p>
                    </div>

                    <div>
                        <label for="phone" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nomor WhatsApp / Telepon <span class="text-rose-600">*</span>
                        </label>
                        <input type="text"
                               name="phone"
                               id="phone"
                               required
                               value="{{ old('phone', $admin->phone) }}"
                               placeholder="081234567890"
                               class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 @error('phone') border-rose-400 bg-rose-50/40 @enderror">
                        <p class="text-[11px] text-slate-400 mt-1">Nomor kontak langsung administrator untuk koordinasi darurat.</p>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition active:scale-95 shadow-sm">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Simpan Perubahan Profil</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Kolom 2: Formulir Ganti Password -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900">Keamanan & Password</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Perbarui kata sandi akun administrator kamu.</p>
                    </div>
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                </div>

                @if (session('password_success'))
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 flex items-center gap-2.5 shadow-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-600 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-semibold">{{ session('password_success') }}</span>
                    </div>
                @endif

                @if ($errors->has('current_password') || $errors->has('password'))
                    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-700 shadow-xs">
                        <p class="font-bold mb-1">Gagal mengganti password:</p>
                        <ul class="list-disc pl-4 space-y-1">
                            @error('current_password')
                                <li>{{ $message }}</li>
                            @enderror
                            @error('password')
                                <li>{{ $message }}</li>
                            @enderror
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.profile.password.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Password Saat Ini <span class="text-rose-600">*</span>
                        </label>
                        <input type="password"
                               name="current_password"
                               id="current_password"
                               required
                               placeholder="Masukkan password saat ini"
                               class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 @error('current_password') border-rose-400 bg-rose-50/40 @enderror">
                        <p class="text-[11px] text-slate-400 mt-1">Dibutuhkan verifikasi password saat ini demi keamanan.</p>
                    </div>

                    <div>
                        <label for="new_password" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Password Baru <span class="text-rose-600">*</span>
                        </label>
                        <input type="password"
                               name="password"
                               id="new_password"
                               required
                               placeholder="Minimal 6 karakter"
                               class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 @error('password') border-rose-400 bg-rose-50/40 @enderror">
                        <p class="text-[11px] text-slate-400 mt-1">Gunakan kombinasi minimal 6 karakter unik.</p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Konfirmasi Password Baru <span class="text-rose-600">*</span>
                        </label>
                        <input type="password"
                               name="password_confirmation"
                               id="password_confirmation"
                               required
                               placeholder="Ketik ulang password baru"
                               class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500">
                    </div>

                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-[11px] text-slate-600 space-y-1">
                        <p class="font-bold text-slate-800">Tips Keamanan Akun:</p>
                        <p>Pastikan Anda mengingat password baru dan tidak membagikan kredensial login kepada pihak lain.</p>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs transition active:scale-95 shadow-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                            <span>Perbarui Password Admin</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-admin-layout>

