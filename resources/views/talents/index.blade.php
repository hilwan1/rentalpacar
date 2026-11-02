<x-guest-layout title="Daftar Talent - RentalPacar">
    <div class="w-full bg-white py-8 sm:py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Halaman -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 mb-8 border-b border-slate-100">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <a href="{{ route('home') }}"
                           class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-full transition focus:outline-none focus:ring-2 focus:ring-rose-500"
                           aria-label="Kembali ke halaman utama">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                            <span>Menu Utama</span>
                        </a>
                        <span class="text-xs text-slate-400">/</span>
                        <span class="text-xs font-medium text-slate-500">Pilih Talent</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Pilih Talent</h1>
                </div>

                <p class="text-xs sm:text-sm text-slate-500 max-w-sm">
                    Pilih teman kencan virtual yang cocok untuk menemani hari, curhat santai, atau bermain game bersama.
                </p>
            </div>

            <!-- Widget Pencarian & Filter Domisili -->
            <div class="bg-slate-50/80 border border-slate-200/80 rounded-2xl p-3 sm:p-4 mb-8">
                <form action="{{ route('talents.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
                    <!-- Kolom Pencarian -->
                    <div class="relative flex-1 w-full">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text"
                               name="q"
                               value="{{ $search }}"
                               placeholder="Cari nama talent atau keahlian..."
                               class="w-full pl-10 pr-4 py-2.5 text-xs bg-white rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition">
                    </div>

                    <!-- Filter Domisili Dropdown -->
                    <div class="relative w-full sm:w-56">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-rose-500">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <select name="city"
                                onchange="this.form.submit()"
                                class="w-full pl-9 pr-8 py-2.5 text-xs bg-white rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 text-slate-700 transition appearance-none cursor-pointer">
                            <option value="">Semua Domisili</option>
                            @foreach ($availableCities as $city)
                                <option value="{{ $city }}" {{ $selectedCity === $city ? 'selected' : '' }}>
                                    {{ $city }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    <!-- Tombol Cari -->
                    <button type="submit"
                            class="w-full sm:w-auto px-5 py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white text-xs font-semibold rounded-xl shadow-xs transition flex items-center justify-center gap-1.5 shrink-0">
                        <span>Cari Talent</span>
                    </button>

                    <!-- Tombol Reset jika ada filter aktif -->
                    @if (!empty($search) || !empty($selectedCity))
                        <a href="{{ route('talents.index') }}"
                           class="w-full sm:w-auto px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition text-center shrink-0">
                            Reset
                        </a>
                    @endif
                </form>

                @if (!empty($search) || !empty($selectedCity))
                    <div class="mt-2.5 pt-2.5 border-t border-slate-200/60 flex items-center justify-between text-[11px] text-slate-500">
                        <span>
                            Menampilkan hasil untuk:
                            @if (!empty($search)) <strong class="text-slate-800">"{{ $search }}"</strong> @endif
                            @if (!empty($search) && !empty($selectedCity)) di @endif
                            @if (!empty($selectedCity)) <strong class="text-rose-600">📍 {{ $selectedCity }}</strong> @endif
                        </span>
                        <a href="{{ route('talents.index') }}" class="text-rose-600 hover:underline">Hapus Semua Filter</a>
                    </div>
                @endif
            </div>

            @if ($talents->isNotEmpty())
                <div class="space-y-4 mb-8">
                    @foreach ($talents as $talent)
                        <article class="bg-white rounded-3xl p-4 sm:p-5 border border-slate-200/80 shadow-xs hover:border-rose-200 hover:shadow-sm transition-all duration-150 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-4 flex-1">
                                <!-- Foto Talent -->
                                <div class="relative shrink-0">
                                    @if ($talent->avatar_url)
                                        <img src="{{ $talent->avatar_url }}" alt="{{ $talent->name }}" class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl object-cover object-center border border-slate-200 shadow-xs" loading="lazy">
                                    @else
                                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center border border-rose-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 opacity-60" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Informasi Talent -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h2 class="text-base font-bold text-slate-900 truncate">{{ $talent->name }}</h2>
                                        @if ($talent->city)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-600">
                                                <svg class="w-3 h-3 text-rose-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                                </svg>
                                                <span>{{ $talent->city }}</span>
                                            </span>
                                        @endif
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            <span>Tersedia</span>
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500 line-clamp-1 sm:line-clamp-2 mt-1">
                                        {{ $talent->bio ?? 'Siap menjadi teman ngobrol, curhat, atau mabar game favoritmu.' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Tarif & Tombol Aksi -->
                            <div class="flex items-center justify-between sm:justify-end gap-4 pt-3 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                                <div class="text-left sm:text-right">
                                    <span class="text-[10px] text-slate-500 block">Tarif Mulai</span>
                                    <span class="text-sm font-extrabold text-rose-600">
                                        Rp {{ number_format($talent->services->min('price') ?? 35000, 0, ',', '.') }}
                                    </span>
                                </div>

                                <a href="{{ route('talents.show', $talent->id) }}"
                                   class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white text-xs font-semibold rounded-xl shadow-md shadow-rose-600/20 transition whitespace-nowrap">
                                    Lihat Tarif & Pesan
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Navigasi Halaman (Pagination) -->
                <div class="mt-8">
                    {{ $talents->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-slate-50 rounded-3xl p-10 sm:p-14 border border-slate-100 text-center max-w-lg mx-auto flex flex-col items-center">
                    <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-800 mb-1">
                        @if (!empty($search) || !empty($selectedCity))
                            Talent Tidak Ditemukan
                        @else
                            Belum Ada Talent Tersedia
                        @endif
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 max-w-xs leading-relaxed mb-6">
                        @if (!empty($search) || !empty($selectedCity))
                            Tidak ada talent yang cocok dengan kata kunci atau filter domisili yang dipilih.
                        @else
                            Talent yang terdaftar sedang dalam peninjauan jadwal atau belum mengaktifkan layanan publik.
                        @endif
                    </p>
                    @if (!empty($search) || !empty($selectedCity))
                        <a href="{{ route('talents.index') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs sm:text-sm font-semibold rounded-xl transition">
                            Reset Pencarian dan Filter
                        </a>
                    @else
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs sm:text-sm font-semibold rounded-xl transition">
                            Kembali ke Beranda
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-guest-layout>
