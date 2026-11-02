<x-guest-layout title="Lupa Kata Sandi Talent - RentalPacar">
    <div class="w-full bg-white py-12 sm:py-20 flex-1 flex items-center justify-center">
        <div class="w-full max-w-md mx-auto px-4 sm:px-6">
            <!-- Form Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xs border border-slate-200/80">
                <div class="text-center mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-3 border border-rose-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </div>
                    <h1 class="text-xl font-extrabold text-slate-900">Lupa Kata Sandi Talent</h1>
                    <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
                        Masukkan email akun Talent kamu yang terdaftar. Kami akan mengirimkan 6 digit kode OTP untuk mengatur ulang kata sandi.
                    </p>
                </div>

                @if (session('status'))
                    <div class="mb-5 p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-5 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-700">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('talent.password.email') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Email Talent</label>
                        <div class="relative">
                            <input type="email"
                                   name="email"
                                   id="email"
                                   required
                                   autofocus
                                   placeholder="nama@email.com"
                                   value="{{ old('email') }}"
                                   class="w-full text-xs px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition @error('email') border-rose-400 bg-rose-50/30 @enderror">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Pastikan email aktif dan bisa menerima pesan masuk.</p>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                                class="w-full py-3.5 px-4 bg-rose-600 hover:bg-rose-700 active:scale-[0.98] text-white font-bold text-xs rounded-xl shadow-md shadow-rose-600/25 transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>Kirim Kode OTP</span>
                        </button>
                    </div>
                </form>

                <div class="mt-6 pt-5 border-t border-slate-100 text-center text-xs text-slate-600">
                    Sudah ingat kata sandi akunmu?
                    <a href="{{ route('talent.login') }}" class="font-bold text-rose-600 hover:text-rose-700 underline ml-1">
                        Masuk Sekarang
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

