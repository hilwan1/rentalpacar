<x-guest-layout title="RentalPacar - Teman Cerita & Kencan Online">
    <div class="w-full bg-white py-10 sm:py-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center">
            <!-- Profil Header -->
            <div class="relative inline-block mb-5">
                <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full ring-4 ring-rose-100 bg-gradient-to-tr from-rose-600 to-rose-500 flex items-center justify-center text-white mx-auto shadow-xl shadow-rose-600/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 sm:w-14 sm:h-14 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                </div>
            </div>

            <!-- Nama Brand & Badge -->
            <div class="flex items-center justify-center gap-2 mb-2">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">RentalPacar</h1>
                <svg class="w-6 h-6 text-rose-600 fill-current" viewBox="0 0 24 24" aria-label="Terverifikasi" role="img">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
            </div>

            <p class="text-base sm:text-lg font-semibold text-rose-600 mb-3">Teman Cerita & Kencan Online</p>
            <p class="text-xs sm:text-sm text-slate-600 max-w-lg mx-auto leading-relaxed mb-8">
                Temukan teman ngobrol, curhat, voice call, atau mabar game yang siap menemani harimu dengan aman dan nyaman.
            </p>

            <!-- Menu Tombol Aksi (Linktree Style yang beradaptasi di Mobile & Desktop) -->
            <nav class="max-w-md mx-auto space-y-3.5 mb-12" aria-label="Menu Utama">
                <!-- Button 1: Daftar Jasa & Talent -->
                <a href="{{ route('talents.index') }}"
                   class="w-full py-4 px-6 bg-rose-600 hover:bg-rose-700 active:scale-[0.98] text-white font-semibold rounded-2xl shadow-lg shadow-rose-600/25 flex items-center justify-center gap-3 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <span>Daftar Jasa & Talent</span>
                </a>

                <!-- Button 2: Testimoni Customer -->
                <a href="{{ route('testimonials.index') }}"
                   class="w-full py-4 px-6 bg-white hover:bg-rose-50 active:scale-[0.98] text-rose-600 border-2 border-rose-600 font-semibold rounded-2xl shadow-xs flex items-center justify-center gap-3 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-500 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                    </svg>
                    <span>Testimoni Pelanggan</span>
                </a>

                <!-- Button 3: Hubungi WhatsApp Admin -->
                <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp_home_number', config('services.whatsapp.admin_number', env('WHATSAPP_ADMIN_NUMBER', '6281234567890'))) }}?text={{ rawurlencode('Halo Admin RentalPacar, saya ingin tanya-tanya seputar layanan teman kencan virtual.') }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="w-full py-4 px-6 bg-rose-50 hover:bg-rose-100 active:scale-[0.98] text-rose-800 border border-rose-200 font-semibold rounded-2xl shadow-xs flex items-center justify-center gap-3 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                    </svg>
                    <span>Hubungi WA RentalPacar</span>
                </a>
            </nav>

            <!-- Informasi Keunggulan Layanan (Responsif Grid 3 Kolom di Desktop, 1 Kolom di HP) -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-2xl mx-auto pt-6 border-t border-slate-100 text-left">
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center font-bold text-xs mb-2">
                        1
                    </div>
                    <h2 class="text-xs font-bold text-slate-800 mb-1">Tanpa Akun & Praktis</h2>
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        Pesan layanan instan tanpa perlu repot registrasi atau membuat akun.
                    </p>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center font-bold text-xs mb-2">
                        2
                    </div>
                    <h2 class="text-xs font-bold text-slate-800 mb-1">Privasi Dijamin Aman</h2>
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        Data dan riwayat percakapan dijaga dengan kerahasiaan penuh.
                    </p>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center font-bold text-xs mb-2">
                        3
                    </div>
                    <h2 class="text-xs font-bold text-slate-800 mb-1">WhatsApp Langsung</h2>
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        Pemesanan menghasilkan kode unik dan langsung terhubung ke WhatsApp Admin resmi.
                    </p>
                </div>
            </div>

            <!-- Banner Undangan Talent (Hanya Tampil untuk Pengunjung/Guest) -->
            @guest
                <div class="mt-8 p-5 rounded-2xl bg-gradient-to-r from-rose-50 via-white to-rose-50/50 border border-rose-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left max-w-2xl mx-auto shadow-xs">
                    <div>
                        <p class="text-xs font-bold text-slate-900">Tertarik Menjadi Talent di RentalPacar?</p>
                        <p class="text-[11px] text-slate-500 mt-0.5">Atur sendiri paket layanan, tarif, dan jadwal fleksibelmu.</p>
                    </div>
                    <a href="{{ route('talent.register') }}"
                       class="px-4 py-2 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white text-xs font-semibold rounded-xl shadow-md shadow-rose-600/20 transition shrink-0">
                        Daftar Jadi Talent
                    </a>
                </div>
            @endguest
        </div>
    </div>
</x-guest-layout>
