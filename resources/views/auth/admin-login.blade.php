<x-guest-layout title="Login Admin - RentalPacar">
    <div class="w-full bg-white py-12 sm:py-20 flex-1 flex items-center justify-center">
        <div class="w-full max-w-md mx-auto px-4 sm:px-6">
            <!-- Selector Role Tab -->
            <div class="flex items-center justify-center p-1 bg-slate-100 rounded-2xl mb-6">
                <a href="{{ route('talent.login') }}"
                   class="flex-1 py-2 text-center text-xs font-semibold rounded-xl text-slate-600 hover:text-slate-900 transition">
                    Portal Talent
                </a>
                <a href="{{ route('admin.login') }}"
                   class="flex-1 py-2 text-center text-xs font-bold rounded-xl bg-slate-900 text-white shadow-xs transition"
                   aria-current="page">
                    Portal Admin
                </a>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xs border border-slate-200/80">
                <div class="text-center mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-800 flex items-center justify-center mx-auto mb-3 border border-slate-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div class="inline-block px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[10px] font-bold uppercase tracking-wider mb-2">
                        Area Khusus Admin
                    </div>
                    <h1 class="text-xl font-extrabold text-slate-900">Login Administrator</h1>
                    <p class="text-xs text-slate-500 mt-1">Portal pemantauan pesanan, manajemen talent, dan kurasi testimoni.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-5 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-700">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">Email Administrator</label>
                        <input type="email" name="email" id="email" required
                               placeholder="admin@rentalpacar.com"
                               value="{{ old('email') }}"
                               class="w-full text-xs px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-800 focus:border-slate-800 transition">
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">Kata Sandi</label>
                        <input type="password" name="password" id="password" required
                               placeholder="••••••••"
                               class="w-full text-xs px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-800 focus:border-slate-800 transition">
                    </div>

                    <div class="flex items-center justify-between text-xs pt-1">
                        <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded text-slate-900 border-slate-300 focus:ring-slate-800">
                            <span>Ingat saya</span>
                        </label>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                                class="w-full py-3.5 px-4 bg-slate-900 hover:bg-slate-800 active:scale-[0.98] text-white font-semibold text-xs rounded-xl shadow-md transition">
                            Masuk sebagai Admin
                        </button>
                    </div>
                </form>

                <div class="mt-6 pt-5 border-t border-slate-100 text-center text-[11px] text-slate-500 leading-relaxed">
                    Akun administrator dikonfigurasi melalui seeder sistem dan tidak membuka pendaftaran publik.
                </div>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('home') }}" class="text-xs text-slate-500 hover:text-slate-800 transition">
                    ← Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>

