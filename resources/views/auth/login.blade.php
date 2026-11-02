<x-guest-layout title="Masuk - RentalPacar">
    <div class="w-full bg-white py-12 sm:py-20 flex-1 flex items-center justify-center">
        <div class="w-full max-w-md mx-auto px-4 sm:px-6">
            <div class="bg-white rounded-3xl p-8 shadow-xs border border-slate-200/80">
                <div class="text-center mb-8">
                    <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-3 border border-rose-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h1 class="text-xl font-bold text-slate-900">Login Portal</h1>
                    <p class="text-xs text-slate-500 mt-1">Akses dashboard untuk Talent dan Admin</p>
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

                <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
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

                    <div class="pt-2">
                        <button type="submit"
                                class="w-full py-3.5 px-4 bg-rose-600 hover:bg-rose-700 active:scale-[0.98] text-white font-semibold text-xs rounded-xl shadow-md shadow-rose-600/25 transition">
                            Masuk ke Dashboard
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('home') }}" class="text-xs text-slate-500 hover:text-rose-600 transition">
                        ← Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
