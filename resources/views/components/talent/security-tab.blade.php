@props(['talent'])

<div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xs border border-slate-200/80 space-y-6 max-w-2xl">
    <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
        <div>
            <h2 class="text-base font-extrabold text-slate-900">Keamanan & Password</h2>
            <p class="text-xs text-slate-500 mt-0.5">Perbarui kata sandi untuk mengamankan akses akun login talent kamu.</p>
        </div>
        <div class="w-9 h-9 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
    </div>

    @if ($errors->has('current_password') || $errors->has('password'))
        <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-700 space-y-1">
            @error('current_password')
                <p class="font-medium">• {{ $message }}</p>
            @enderror
            @error('password')
                <p class="font-medium">• {{ $message }}</p>
            @enderror
        </div>
    @endif

    <form action="{{ route('talent.password.update') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="current_password" class="block text-xs font-bold text-slate-700 mb-1.5">
                Password Saat Ini <span class="text-rose-600">*</span>
            </label>
            <div class="relative">
                <input type="password"
                       name="current_password"
                       id="current_password"
                       required
                       placeholder="Masukkan password saat ini"
                       class="w-full text-xs pl-3.5 pr-11 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 @error('current_password') border-rose-400 bg-rose-50/40 @enderror">
                <button type="button"
                        onclick="toggleVisibility('current_password', this)"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-700 focus:outline-none transition cursor-pointer"
                        aria-label="Tampilkan atau sembunyikan kata sandi">
                    <svg class="w-4 h-4 icon-open" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg class="w-4 h-4 icon-closed hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                    </svg>
                </button>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Dibutuhkan verifikasi password saat ini demi keamanan.</p>
        </div>

        <div>
            <label for="new_password" class="block text-xs font-bold text-slate-700 mb-1.5">
                Password Baru <span class="text-rose-600">*</span>
            </label>
            <div class="relative">
                <input type="password"
                       name="password"
                       id="new_password"
                       required
                       placeholder="Minimal 6 karakter"
                       class="w-full text-xs pl-3.5 pr-11 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 @error('password') border-rose-400 bg-rose-50/40 @enderror">
                <button type="button"
                        onclick="toggleVisibility('new_password', this)"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-700 focus:outline-none transition cursor-pointer"
                        aria-label="Tampilkan atau sembunyikan kata sandi">
                    <svg class="w-4 h-4 icon-open" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg class="w-4 h-4 icon-closed hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                    </svg>
                </button>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Gunakan kombinasi minimal 6 karakter yang unik.</p>
        </div>

        <div>
            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1.5">
                Konfirmasi Password Baru <span class="text-rose-600">*</span>
            </label>
            <input type="password"
                   name="password_confirmation"
                   id="password_confirmation"
                   required
                   placeholder="Ulangi password baru"
                   class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500">
        </div>

        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-[11px] text-slate-600 space-y-1">
            <p class="font-bold text-slate-800">Tips Keamanan Akun:</p>
            <p>Pastikan kamu mengingat kata sandi baru dan tidak membagikannya kepada pihak mana pun.</p>
        </div>

        <div class="pt-3 border-t border-slate-100 flex justify-end">
            <button type="submit"
                    class="w-full sm:w-auto px-6 py-2.5 bg-slate-900 hover:bg-slate-800 active:scale-95 text-white text-xs font-bold rounded-xl transition shadow-xs">
                Perbarui Password
            </button>
        </div>
    </form>
</div>

<script>
    function toggleVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        const openIcon = btn.querySelector('.icon-open');
        const closedIcon = btn.querySelector('.icon-closed');
        if (openIcon && closedIcon) {
            openIcon.classList.toggle('hidden', isPassword);
            closedIcon.classList.toggle('hidden', !isPassword);
        }
    }
</script>

