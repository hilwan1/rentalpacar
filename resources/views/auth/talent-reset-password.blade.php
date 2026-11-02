<x-guest-layout title="Verifikasi OTP & Reset Kata Sandi - RentalPacar">
    <div class="w-full bg-white py-12 sm:py-20 flex-1 flex items-center justify-center">
        <div class="w-full max-w-md mx-auto px-4 sm:px-6">
            <!-- Form Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xs border border-slate-200/80">
                <div class="text-center mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-3 border border-rose-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h1 class="text-xl font-extrabold text-slate-900">Verifikasi OTP & Kata Sandi Baru</h1>
                    <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
                        Masukkan 6 digit kode OTP yang telah dikirim ke email kamu, lalu tentukan kata sandi baru.
                    </p>
                </div>

                <!-- Info Email Penerima -->
                <div class="mb-5 p-3 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2 min-w-0">
                        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="text-slate-700 font-semibold truncate">{{ $email }}</span>
                    </div>
                    <a href="{{ route('talent.password.request') }}" class="text-[11px] font-bold text-rose-600 hover:text-rose-700 hover:underline shrink-0">
                        Ubah
                    </a>
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

                <form action="{{ route('talent.password.update_via_otp') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div>
                        <label for="otp" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Kode OTP (6 Digit Angka) <span class="text-rose-600">*</span>
                        </label>
                        <input type="text"
                               name="otp"
                               id="otp"
                               required
                               maxlength="6"
                               inputmode="numeric"
                               pattern="[0-9]{6}"
                               autocomplete="one-time-code"
                               placeholder="Contoh: 123456"
                               value="{{ old('otp') }}"
                               class="w-full text-center tracking-[0.3em] font-mono font-extrabold text-base py-3 px-4 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition @error('otp') border-rose-400 bg-rose-50/30 @enderror">
                        <p class="text-[11px] text-slate-400 mt-1 text-center">Kode OTP berlaku selama 15 menit.</p>
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Kata Sandi Baru <span class="text-rose-600">*</span>
                        </label>
                        <input type="password"
                               name="password"
                               id="password"
                               required
                               placeholder="Minimal 6 karakter"
                               class="w-full text-xs px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition @error('password') border-rose-400 bg-rose-50/30 @enderror">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Konfirmasi Kata Sandi Baru <span class="text-rose-600">*</span>
                        </label>
                        <input type="password"
                               name="password_confirmation"
                               id="password_confirmation"
                               required
                               placeholder="Ulangi kata sandi baru"
                               class="w-full text-xs px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition">
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                                class="w-full py-3.5 px-4 bg-rose-600 hover:bg-rose-700 active:scale-[0.98] text-white font-bold text-xs rounded-xl shadow-md shadow-rose-600/25 transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Simpan Kata Sandi Baru</span>
                        </button>
                    </div>
                </form>

                <!-- Form Kirim Ulang OTP -->
                <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                    <p class="text-xs text-slate-500 mb-2">Tidak menerima atau kode OTP sudah kedaluwarsa?</p>
                    <form action="{{ route('talent.password.resend') }}" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-600 hover:text-rose-700 hover:underline transition">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span>Kirim Ulang Kode OTP</span>
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('talent.login') }}" class="text-xs text-slate-500 hover:text-rose-600 transition">
                    ← Batalkan dan Kembali ke Login
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>

