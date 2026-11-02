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

            @if ($talents->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    @foreach ($talents as $talent)
                        <article class="bg-white rounded-3xl overflow-hidden shadow-xs border border-slate-200/80 hover:border-rose-300 hover:shadow-lg transition-all flex flex-col group">
                            <div class="relative overflow-hidden">
                                @if ($talent->avatar_url)
                                    <img src="{{ $talent->avatar_url }}" alt="{{ $talent->name }}" class="w-full h-72 object-cover object-center group-hover:scale-105 transition-transform duration-300" loading="lazy">
                                @else
                                    <div class="w-full h-72 bg-gradient-to-tr from-rose-100 to-rose-50 flex items-center justify-center text-rose-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 opacity-50" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                        </svg>
                                    </div>
                                @endif

                                <div class="absolute top-3.5 right-3.5 bg-white/95 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-emerald-700 shadow-sm flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span>Tersedia</span>
                                </div>
                            </div>

                            <div class="p-5 flex-1 flex flex-col justify-between">
                                <div>
                                    <h2 class="text-lg font-bold text-slate-900 group-hover:text-rose-600 transition-colors">{{ $talent->name }}</h2>
                                    <p class="text-xs sm:text-sm text-slate-600 line-clamp-2 mt-2 leading-relaxed">
                                        {{ $talent->bio ?? 'Siap menjadi teman ngobrol, curhat, atau mabar game favoritmu.' }}
                                    </p>
                                </div>

                                <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between gap-3">
                                    <div>
                                        <span class="text-[11px] text-slate-500 block">Tarif Mulai</span>
                                        <span class="text-base font-extrabold text-rose-600">
                                            Rp {{ number_format($talent->services->min('price') ?? 35000, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <a href="{{ route('talents.show', $talent->id) }}"
                                       class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white text-xs font-semibold rounded-xl shadow-md shadow-rose-600/20 transition">
                                        Lihat Tarif & Pesan
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-slate-50 rounded-3xl p-10 sm:p-14 border border-slate-100 text-center max-w-lg mx-auto flex flex-col items-center">
                    <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-800 mb-2">Belum Ada Talent Aktif</h2>
                    <p class="text-xs sm:text-sm text-slate-500 max-w-xs leading-relaxed mb-6">
                        Talent yang terdaftar sedang dalam peninjauan jadwal atau belum mengaktifkan layanan publik.
                    </p>
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs sm:text-sm font-semibold rounded-xl transition">
                        Kembali ke Beranda
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-guest-layout>
