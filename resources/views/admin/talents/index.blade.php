<x-admin-layout title="Daftar Akun Talent - RentalPacar Admin" header="Kelola Akun Talent">
    <div class="space-y-6">
        <!-- Top Header & Ringkasan -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <a href="{{ route('admin.dashboard') }}" class="text-xs text-slate-400 hover:text-rose-600 transition">Dashboard</a>
                    <span class="text-xs text-slate-300">/</span>
                    <span class="text-xs font-bold text-rose-600">Daftar Akun Talent</span>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Manajemen Akun Talent</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Aktifkan atau nonaktifkan akun talent untuk mengatur visibilitas mereka di katalog publik dan pemesanan.
                </p>
            </div>

            <!-- Metrik Ringkas Talent & Tombol Tambah -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 shrink-0">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-semibold">
                        Total: <strong class="text-slate-900">{{ $totalTalents }}</strong>
                    </span>
                    <span class="px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold">
                        Aktif: <strong>{{ $activeCount }}</strong>
                    </span>
                    <span class="px-3 py-1.5 rounded-xl bg-slate-100 text-slate-600 text-xs font-semibold">
                        Nonaktif: <strong>{{ $inactiveCount }}</strong>
                    </span>
                </div>

                <a href="{{ route('admin.talents.create') }}"
                   class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white text-xs font-bold rounded-xl shadow-md shadow-rose-600/25 transition inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah Talent Baru</span>
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 flex items-center gap-2 shadow-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-600 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Filter & Pencarian Talent -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs">
            <form action="{{ route('admin.talents.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
                <!-- Kolom Search -->
                <div class="relative flex-1 w-full">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text"
                           name="q"
                           value="{{ $search }}"
                           placeholder="Cari nama talent, email, no WhatsApp, atau domisili..."
                           class="w-full pl-10 pr-4 py-2.5 text-xs bg-slate-50/50 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:bg-white transition">
                </div>

                <!-- Dropdown Status -->
                <div class="w-full sm:w-48 shrink-0">
                    <select name="status"
                            onchange="this.form.submit()"
                            class="w-full px-3.5 py-2.5 text-xs bg-slate-50/50 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:bg-white text-slate-700 transition cursor-pointer">
                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Hanya Aktif Publik</option>
                        <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Hanya Nonaktif</option>
                    </select>
                </div>

                <!-- Tombol Submit & Reset -->
                <button type="submit"
                        class="w-full sm:w-auto px-5 py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white text-xs font-semibold rounded-xl shadow-xs transition shrink-0">
                    Cari
                </button>

                @if (!empty($search) || $status !== 'all')
                    <a href="{{ route('admin.talents.index') }}"
                       class="w-full sm:w-auto px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition text-center shrink-0">
                        Reset Filter
                    </a>
                @endif
            </form>
        </div>

        <!-- Tabel Daftar Akun Talent -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            @if ($talents->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50/70 text-slate-400 border-b border-slate-200/80">
                                <th class="py-3.5 px-5 font-semibold">Identitas Talent</th>
                                <th class="py-3.5 px-4 font-semibold">Kontak</th>
                                <th class="py-3.5 px-4 font-semibold text-center">Layanan</th>
                                <th class="py-3.5 px-4 font-semibold text-center">Pesanan</th>
                                <th class="py-3.5 px-4 font-semibold text-center">Status Publik</th>
                                <th class="py-3.5 px-5 font-semibold text-right">Aksi Administrator</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($talents as $talent)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <!-- Identitas Talent -->
                                    <td class="py-4 px-5">
                                        <div class="flex items-center gap-3.5">
                                            <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center font-bold text-sm text-rose-600">
                                                @if ($talent->avatar_url)
                                                    <img src="{{ $talent->avatar_url }}" alt="{{ $talent->name }}" class="w-full h-full object-cover">
                                                @else
                                                    {{ strtoupper(substr($talent->name, 0, 1)) }}
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <span class="font-bold text-sm text-slate-900">{{ $talent->name }}</span>
                                                    @if ($talent->city)
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-slate-100 text-slate-600">
                                                            <svg class="w-2.5 h-2.5 text-rose-500" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                                            </svg>
                                                            <span>{{ $talent->city }}</span>
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">
                                                    {{ $talent->bio ?? 'Belum ada bio singkat.' }}
                                                </p>
                                                <span class="text-[10px] text-slate-400 block mt-0.5">Terdaftar sejak {{ $talent->created_at->translatedFormat('d M Y') }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Kontak -->
                                    <td class="py-4 px-4">
                                        <span class="text-slate-700 font-medium block">{{ $talent->email }}</span>
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $talent->phone) }}" target="_blank"
                                           class="text-[11px] text-emerald-600 hover:underline flex items-center gap-1 mt-0.5">
                                            <span>WA: {{ $talent->phone }}</span>
                                            <span class="text-[10px]">↗</span>
                                        </a>
                                    </td>

                                    <!-- Layanan -->
                                    <td class="py-4 px-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $talent->services_count > 0 ? 'bg-slate-100 text-slate-700' : 'bg-rose-50 text-rose-600' }}">
                                            {{ $talent->services_count }} Paket
                                        </span>
                                    </td>

                                    <!-- Pesanan -->
                                    <td class="py-4 px-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $talent->orders_count > 0 ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-500' }}">
                                            {{ $talent->orders_count }} Order
                                        </span>
                                    </td>

                                    <!-- Status Publik -->
                                    <td class="py-4 px-4 text-center">
                                        @if ($talent->is_active)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
                                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                                <span>Aktif</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                                <span>Nonaktif</span>
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Aksi Administrator -->
                                    <td class="py-4 px-5 text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-2 justify-end">
                                            <!-- Tombol Toggle Status Aktif / Nonaktif -->
                                            <form action="{{ route('admin.talents.toggle-status', $talent->id) }}" method="POST" class="inline">
                                                @csrf
                                                @if ($talent->is_active)
                                                    <button type="submit"
                                                            onclick="return confirm('Apakah kamu yakin ingin menonaktifkan akun talent {{ $talent->name }}? Profilnya tidak akan tampil di katalog publik.')"
                                                            class="px-3 py-1.5 rounded-xl text-xs font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 transition active:scale-95"
                                                            title="Nonaktifkan akun talent ini">
                                                        Nonaktifkan
                                                    </button>
                                                @else
                                                    <button type="submit"
                                                            class="px-3 py-1.5 rounded-xl text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition active:scale-95"
                                                            title="Aktifkan akun talent agar tampil publik">
                                                        Aktifkan Akun
                                                    </button>
                                                @endif
                                            </form>

                                            <!-- Tombol Edit Profil & Layanan -->
                                            <a href="{{ route('admin.talents.edit', $talent->id) }}"
                                               class="px-2.5 py-1.5 rounded-xl text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 transition active:scale-95 inline-flex items-center gap-1.5 shadow-2xs"
                                               title="Edit profil dan paket layanan talent">
                                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                <span>Edit</span>
                                            </a>

                                            <!-- Tombol Lihat Profil Publik -->
                                            <a href="{{ route('talents.show', $talent->id) }}" target="_blank"
                                               class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition"
                                               title="Lihat Halaman Publik Talent">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Halaman -->
                <div class="p-4 border-t border-slate-100">
                    {{ $talents->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Tidak ada akun talent yang ditemukan</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                        @if (!empty($search) || $status !== 'all')
                            Coba ubah kata kunci pencarian atau bersihkan filter status untuk melihat seluruh talent.
                        @else
                            Belum ada talent yang mendaftar di sistem RentalPacar.
                        @endif
                    </p>
                    @if (!empty($search) || $status !== 'all')
                        <a href="{{ route('admin.talents.index') }}" class="inline-block mt-4 px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-xl transition">
                            Reset Filter Pencarian
                        </a>
                    @else
                        <a href="{{ route('admin.talents.create') }}" class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition shadow-xs">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Tambah Talent Pertama</span>
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>

