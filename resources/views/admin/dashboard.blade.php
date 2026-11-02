<x-guest-layout title="Dashboard Admin - RentalPacar">
    <div class="w-full bg-white py-10 sm:py-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xs border border-slate-200/80">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                    <div>
                        <div class="inline-block px-2.5 py-0.5 rounded-full bg-slate-900 text-white text-xs font-bold mb-2">
                            Dashboard Administrator
                        </div>
                        <h1 class="text-2xl font-extrabold text-slate-900">Panel Admin RentalPacar</h1>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">Pantau pesanan masuk, akun talent, dan testimoni pelanggan.</p>
                    </div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-700 text-xs font-semibold rounded-xl border border-slate-200 transition">
                            Keluar (Logout)
                        </button>
                    </form>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mt-6">
                    <div class="p-5 rounded-2xl bg-rose-50/50 border border-rose-100">
                        <span class="text-xs font-semibold text-rose-800 block">Total Pesanan Masuk</span>
                        <span class="text-2xl font-extrabold text-rose-600 mt-2 block">
                            {{ \App\Models\Order::count() }}
                        </span>
                        <span class="text-[11px] text-slate-500 mt-1 block">Tercatat di sistem</span>
                    </div>

                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-xs font-semibold text-slate-700 block">Talent Terdaftar</span>
                        <span class="text-2xl font-extrabold text-slate-900 mt-2 block">
                            {{ \App\Models\User::where('role', 'talent')->count() }}
                        </span>
                        <span class="text-[11px] text-slate-500 mt-1 block">Akun talent aktif</span>
                    </div>

                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-xs font-semibold text-slate-700 block">Testimoni Publik</span>
                        <span class="text-2xl font-extrabold text-slate-900 mt-2 block">
                            {{ \App\Models\Testimonial::count() }}
                        </span>
                        <span class="text-[11px] text-slate-500 mt-1 block">Ulasan disetujui</span>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
                    <a href="{{ route('home') }}" class="text-xs font-semibold text-rose-600 hover:text-rose-700 transition">
                        ← Kunjungi Halaman Publik
                    </a>
                    <span class="text-xs text-slate-400">Modul manajemen database siap digunakan.</span>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

