<x-guest-layout title="Login Talent - RentalPacar">
    <div class="w-full bg-white py-12 sm:py-20 flex-1 flex items-center justify-center">
        <div class="w-full max-w-md mx-auto px-4 sm:px-6">
            <!-- Selector Role Tab -->
            <div class="flex items-center justify-center p-1 bg-slate-100 rounded-2xl mb-6">
                <a href="{{ route('talent.login') }}"
                   class="flex-1 py-2 text-center text-xs font-bold rounded-xl bg-rose-600 text-white shadow-xs transition"
                   aria-current="page">
                    Portal Talent
                </a>
                <a href="{{ route('admin.login') }}"
                   class="flex-1 py-2 text-center text-xs font-semibold rounded-xl text-slate-600 hover:text-slate-900 transition">
                    Portal Admin
                </a>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xs border border-slate-200/80">
                <div class="text-center mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-3 border border-rose-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h1 class="text-xl font-extrabold text-slate-900">Masuk Portal Talent</h1>
                    <p class="text-xs text-slate-500 mt-1">Kelola profil pribadi, tarif layanan, dan jadwal ketersediaanmu.</p>
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

                <form action="{{ route('talent.login.post') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Email</label>
                        <input type="email" name="email" id="email" required
                               placeholder="nama@email.com"
                               value="{{ old('email') }}"
                               class="w-full text-xs px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition">
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">Kata Sandi</label>
                        <input type="password" name="password" id="password" required
                               placeholder="••••••••"
                               class="w-full text-xs px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition">
                    </div>

                    <div class="flex items-center justify-between text-xs pt-1">
                        <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded text-rose-600 border-slate-300 focus:ring-rose-500">
                            <span>Ingat saya</span>
                        </label>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                                class="w-full py-3.5 px-4 bg-rose-600 hover:bg-rose-700 active:scale-[0.98] text-white font-semibold text-xs rounded-xl shadow-md shadow-rose-600/25 transition">
                            Masuk sebagai Talent
                        </button>
                    </div>
                </form>

                <div class="mt-6 pt-5 border-t border-slate-100 text-center text-xs text-slate-600">
                    Belum punya akun Talent?
                    <a href="{{ route('talent.register') }}" class="font-bold text-rose-600 hover:text-rose-700 underline ml-1">
                        Daftar Sekarang
                    </a>
                </div>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('home') }}" class="text-xs text-slate-500 hover:text-rose-600 transition">
                    ← Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>

