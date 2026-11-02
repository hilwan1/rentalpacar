<x-guest-layout title="Top Talent Mingguan - RentalPacar">
    <div class="w-full bg-white py-8 sm:py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
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
                        <span class="text-xs font-medium text-slate-500">Peringkat Mingguan</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center font-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M19 5h-2V3H7v2H5c-1.1 0-2 .9-2 2v1c0 2.55 1.92 4.63 4.39 4.94A5.01 5.01 0 0011 15.9V19H7v2h10v-2h-4v-3.1c1.9-.3 3.51-1.63 4.1-3.41C19.26 12.18 21 10.29 21 8V7c0-1.1-.9-2-2-2zM5 8V7h2v3.82C5.84 10.4 5 9.3 5 8zm14 0c0 1.3-.84 2.4-2 2.82V7h2v1z"/>
                            </svg>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Top Talent Mingguan</h1>
                    </div>
                </div>

                <p class="text-xs sm:text-sm text-slate-500 max-w-sm">
                    Daftar talent terpopuler yang paling banyak dipesan oleh pelanggan berdasarkan jumlah pesanan masuk setiap minggunya.
                </p>
            </div>

            <!-- Filter Pilihan Minggu & Info Periode -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 mb-8 border-b border-slate-100">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-bold text-slate-700 mr-1">Pilih Periode:</span>
                    @for ($w = 0; $w <= 3; $w++)
                        @php
                            $label = $w === 0 ? 'Minggu Ini' : ($w === 1 ? '1 Minggu Lalu' : $w . ' Minggu Lalu');
                            $isActive = $weekOffset === $w;
                        @endphp
                        <a href="{{ route('top-talents.index', ['week' => $w]) }}"
                           class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition {{ $isActive ? 'bg-rose-600 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                            {{ $label }}
                        </a>
                    @endfor
                </div>

                <div class="text-xs text-slate-500 flex items-center gap-1.5 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Periode: <strong class="text-slate-800">{{ $dateRangeText }}</strong></span>
                </div>
            </div>

            @if ($talents->isNotEmpty())
                <!-- Daftar Ranking Talent -->
                <div class="space-y-4 mb-10">
                    @foreach ($talents as $index => $talent)
                        @php
                            $rank = $index + 1;
                            $isTop1 = $rank === 1 && $talent->orders_count > 0;
                            $isTop2 = $rank === 2 && $talent->orders_count > 0;
                            $isTop3 = $rank === 3 && $talent->orders_count > 0;
                        @endphp
                        <article class="bg-white rounded-3xl p-4 sm:p-5 border transition-all duration-150 flex flex-col sm:flex-row sm:items-center justify-between gap-4 {{ $isTop1 ? 'border-rose-300 ring-2 ring-rose-100 shadow-md' : 'border-slate-200/80 shadow-xs hover:border-rose-200 hover:shadow-sm' }}">
                            <div class="flex items-center gap-4 flex-1">
                                <!-- Badge Peringkat (Rank) -->
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl flex items-center justify-center font-extrabold text-sm sm:text-base shrink-0
                                    {{ $isTop1 ? 'bg-gradient-to-tr from-amber-400 to-amber-200 text-amber-950 shadow-sm' :
                                       ($isTop2 ? 'bg-slate-200 text-slate-800' :
                                       ($isTop3 ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600')) }}">
                                    @if ($isTop1)
                                        <span title="Juara 1 Minggu Ini">#1</span>
                                    @elseif ($isTop2)
                                        <span title="Juara 2">#2</span>
                                    @elseif ($isTop3)
                                        <span title="Juara 3">#3</span>
                                    @else
                                        <span>#{{ $rank }}</span>
                                    @endif
                                </div>

                                <!-- Foto Talent -->
                                <div class="relative shrink-0">
                                    @if ($talent->avatar)
                                        <img src="{{ asset('storage/' . $talent->avatar) }}" alt="{{ $talent->name }}" class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl object-cover object-center border border-slate-200 shadow-xs" loading="lazy">
                                    @else
                                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center border border-rose-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 opacity-60" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                            </svg>
                                        </div>
                                    @endif

                                    @if ($isTop1)
                                        <div class="absolute -top-1.5 -right-1.5 bg-amber-400 text-amber-950 rounded-full w-5 h-5 flex items-center justify-center shadow-xs text-[10px]" title="Peringkat Pertama">
                                            👑
                                        </div>
                                    @endif
                                </div>

                                <!-- Informasi Talent -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h2 class="text-base font-bold text-slate-900 truncate">{{ $talent->name }}</h2>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $talent->orders_count > 0 ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-600' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                            </svg>
                                            <span>{{ $talent->orders_count }} Pesanan</span>
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500 line-clamp-1 mt-1">
                                        {{ $talent->bio ?? 'Siap menjadi teman ngobrol, curhat, dan mabar game favoritmu.' }}
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
                                    Lihat Profil & Pesan
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <!-- Empty State jika belum ada talent terdaftar -->
                <div class="bg-slate-50 rounded-3xl p-10 sm:p-14 border border-slate-100 text-center max-w-lg mx-auto flex flex-col items-center">
                    <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-800 mb-2">Belum Ada Data Talent</h2>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed mb-6">
                        Talent yang berstatus aktif akan muncul pada daftar peringkat ini saat layanan dibuka.
                    </p>
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs sm:text-sm font-semibold rounded-xl transition">
                        Kembali ke Beranda
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-guest-layout>

