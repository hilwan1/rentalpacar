<x-guest-layout title="Testimoni Pelanggan - RentalPacar">
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
                        <span class="text-xs font-medium text-slate-500">Testimoni</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Testimoni Pelanggan</h1>
                </div>

                <p class="text-xs sm:text-sm text-slate-500 max-w-sm">
                    Cerita dan pengalaman jujur dari teman-teman yang telah memesan layanan teman kencan virtual kami.
                </p>
            </div>

            <!-- Daftar Kartu Testimoni (Grid 2 Kolom di Desktop, 1 Kolom di HP) -->
            @if ($testimonials->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
                    @foreach ($testimonials as $item)
                        <article class="bg-white rounded-2xl p-5 shadow-xs border border-slate-200/80 hover:border-rose-200 hover:shadow-md transition-all flex flex-col justify-between">
                            <div>
                                <div class="flex items-start justify-between gap-3 mb-3">
                                    <div>
                                        <h2 class="text-sm font-bold text-slate-800">{{ $item->customer_alias }}</h2>
                                        @if ($item->talent)
                                            <p class="text-xs text-rose-600 font-semibold mt-0.5">
                                                Layanan bersama {{ $item->talent->name }}
                                            </p>
                                        @endif
                                    </div>

                                    <!-- Rating Bintang Kuning -->
                                    <div class="flex items-center gap-0.5 bg-amber-50 px-2 py-1 rounded-lg border border-amber-100" aria-label="Rating {{ $item->rating }} dari 5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-3.5 h-3.5 {{ $i <= $item->rating ? 'text-amber-400 fill-current' : 'text-slate-200 fill-current' }}" viewBox="0 0 24 24" aria-hidden="true">
                                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>

                                <!-- Isi Ulasan -->
                                <blockquote class="text-xs sm:text-sm text-slate-700 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100 mb-3">
                                    "{{ $item->content }}"
                                </blockquote>

                                <!-- Screenshot Percakapan (Jika Ada) -->
                                @if ($item->screenshot_url)
                                    <div class="mt-2 mb-3">
                                        <a href="{{ $item->screenshot_url }}" target="_blank" rel="noopener noreferrer" class="block overflow-hidden rounded-xl border border-slate-200 hover:opacity-95 transition">
                                            <img src="{{ $item->screenshot_url }}" alt="Bukti chat testimoni {{ $item->customer_alias }}" class="w-full h-auto max-h-56 object-cover object-top" loading="lazy">
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="text-[11px] text-slate-400 pt-2 border-t border-slate-100 flex items-center justify-between">
                                <span>Terverifikasi Admin</span>
                                <span>{{ $item->created_at->translatedFormat('d F Y') }}</span>
                            </div>
                        </article>
                    @endforeach
                </div>

                @if ($testimonials->hasPages())
                    <div class="pt-4">
                        {{ $testimonials->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="bg-slate-50 rounded-3xl p-10 sm:p-14 border border-slate-100 text-center max-w-lg mx-auto flex flex-col items-center">
                    <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-800 mb-2">Belum Ada Ulasan</h2>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed mb-6">
                        Ulasan dari pelanggan yang telah memesan layanan teman kencan akan dipublikasikan di halaman ini setelah melalui verifikasi admin.
                    </p>
                    <a href="{{ route('talents.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-md shadow-rose-600/20 transition">
                        <span>Lihat Daftar Talent</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-guest-layout>
