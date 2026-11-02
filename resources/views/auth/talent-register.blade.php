<x-guest-layout title="Daftar Talent - RentalPacar">
    <div class="w-full bg-white py-12 sm:py-16 flex-1 flex items-center justify-center">
        <div class="w-full max-w-lg mx-auto px-4 sm:px-6">
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xs border border-slate-200/80">
                <div class="text-center mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-3 border border-rose-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900">Daftar Jadi Talent</h1>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto leading-relaxed">
                        Bergabung bersama RentalPacar dan mulai tawarkan jasa teman cerita & kencan online secara mandiri.
                    </p>
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

                <form action="{{ route('talent.register.post') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Nama Panggung / Panggilan Kamu <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" required
                               placeholder="Contoh: Jessica atau Clarissa"
                               value="{{ old('name') }}"
                               class="w-full text-xs px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition">
                    </div>

                    <div>
                        <label for="avatar" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Foto Profil <span class="text-slate-400 font-normal">(Opsional, bisa diatur nanti di dashboard)</span>
                        </label>
                        <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png,image/webp"
                               class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100 transition cursor-pointer bg-slate-50/50">
                        <p class="text-[11px] text-slate-400 mt-1">Format JPG, PNG, atau WebP (maksimal 2MB).</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Alamat Email Aktif <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" name="email" id="email" required
                                   placeholder="nama@email.com"
                                   value="{{ old('email') }}"
                                   class="w-full text-xs px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition">
                        </div>

                        <div>
                            <label for="phone" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Nomor WhatsApp <span class="text-rose-500">*</span>
                            </label>
                            <input type="tel" name="phone" id="phone" required
                                   placeholder="Contoh: 081234567890"
                                   value="{{ old('phone') }}"
                                   class="w-full text-xs px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Kata Sandi <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" name="password" id="password" required
                                   placeholder="Minimal 6 karakter"
                                   class="w-full text-xs px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition">
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Konfirmasi Kata Sandi <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                   placeholder="Ulangi kata sandi"
                                   class="w-full text-xs px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition">
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                                class="w-full py-4 px-4 bg-rose-600 hover:bg-rose-700 active:scale-[0.98] text-white font-semibold text-xs rounded-xl shadow-md shadow-rose-600/25 transition">
                            Daftar sebagai Talent
                        </button>
                    </div>
                </form>

                <div class="mt-6 pt-5 border-t border-slate-100 text-center text-xs text-slate-600">
                    Sudah memiliki akun Talent?
                    <a href="{{ route('talent.login') }}" class="font-bold text-rose-600 hover:text-rose-700 underline ml-1">
                        Masuk di sini
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

