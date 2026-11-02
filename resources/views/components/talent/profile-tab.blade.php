@props(['talent'])

<div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xs border border-slate-200/80 space-y-6 max-w-3xl">
    <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
        <div>
            <h2 class="text-base font-extrabold text-slate-900">Profil & Identitas Talent</h2>
            <p class="text-xs text-slate-500 mt-0.5">Perbarui biodata, foto profil, dan status ketersediaan publik kamu.</p>
        </div>
        <div class="w-9 h-9 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>
    </div>

    @if ($errors->has('name') || $errors->has('phone') || $errors->has('city') || $errors->has('bio') || $errors->has('avatar'))
        <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-700 space-y-1">
            @foreach (['avatar', 'name', 'phone', 'city', 'bio'] as $field)
                @error($field)
                    <p class="font-medium">• {{ $message }}</p>
                @enderror
            @endforeach
        </div>
    @endif

    <form action="{{ route('talent.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <!-- Unggah Foto Profil -->
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-2">Foto Profil Talent</label>
            <div class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                <div class="relative shrink-0">
                    <img id="avatar-preview"
                         src="{{ $talent->avatar_url ?? 'data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'64\' height=\'64\' fill=\'%23f43f5e\' viewBox=\'0 0 24 24\'><path d=\'M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z\'/></svg>' }}"
                         alt="Preview Foto"
                         class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover object-center border-2 border-white shadow-xs bg-white">
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <label for="avatar-input" class="inline-block px-3.5 py-2 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl border border-slate-200 cursor-pointer shadow-2xs transition active:scale-95">
                            Ganti Foto
                        </label>
                        <input type="file" name="avatar" id="avatar-input" accept="image/jpeg,image/png,image/webp" class="hidden">
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1.5">Format JPG, PNG, atau WebP (maksimal 2MB).</p>
                    @if ($talent->avatar)
                        <div class="mt-2">
                            <label class="inline-flex items-center gap-1.5 text-xs text-rose-600 font-semibold cursor-pointer">
                                <input type="checkbox" name="remove_avatar" value="1" class="rounded text-rose-600 focus:ring-rose-500">
                                <span>Hapus foto saat ini</span>
                            </label>
                        </div>
                    @endif
                </div>
            </div>
            @error('avatar')
                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">
                    Nama Panggung / Tampilan <span class="text-rose-600">*</span>
                </label>
                <input type="text"
                       name="name"
                       id="name"
                       required
                       value="{{ old('name', $talent->name) }}"
                       class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 @error('name') border-rose-400 bg-rose-50/40 @enderror">
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">
                    Email Akun Login (Permanen)
                </label>
                <input type="email"
                       id="email"
                       disabled
                       value="{{ $talent->email }}"
                       class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-500 cursor-not-allowed">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="phone" class="block text-xs font-bold text-slate-700 mb-1.5">
                    Nomor WhatsApp <span class="text-rose-600">*</span>
                </label>
                <input type="tel"
                       name="phone"
                       id="phone"
                       required
                       value="{{ old('phone', $talent->phone) }}"
                       placeholder="081234567890"
                       class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 @error('phone') border-rose-400 bg-rose-50/40 @enderror">
                <p class="text-[11px] text-slate-400 mt-1">Nomor untuk koordinasi pesanan pelanggan.</p>
            </div>

            <div>
                <label for="city" class="block text-xs font-bold text-slate-700 mb-1.5">
                    Daerah / Kota Domisili
                </label>
                <input type="text"
                       name="city"
                       id="city"
                       placeholder="Contoh: Jakarta Selatan, Bandung, Surabaya"
                       value="{{ old('city', $talent->city) }}"
                       class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500">
                <p class="text-[11px] text-slate-400 mt-1">Membantu calon pelanggan memilih talent terdekat.</p>
            </div>
        </div>

        <div>
            <label for="bio" class="block text-xs font-bold text-slate-700 mb-1.5">
                Deskripsi & Bio Singkat
            </label>
            <textarea name="bio"
                      id="bio"
                      rows="4"
                      placeholder="Ceritakan kepribadian, hobi, topik obrolan favorit, atau hal menyenangkan lainnya tentang dirimu..."
                      class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 leading-relaxed">{{ old('bio', $talent->bio) }}</textarea>
            <p class="text-[11px] text-slate-400 mt-1">Bio akan tampil di halaman publik detail talent kamu.</p>
        </div>

        <div class="pt-2">
            <label class="flex items-start gap-3 p-4 rounded-2xl bg-rose-50/60 border border-rose-100 cursor-pointer">
                <input type="checkbox"
                       name="is_active"
                       value="1"
                       {{ $talent->is_active ? 'checked' : '' }}
                       class="w-4 h-4 rounded text-rose-600 border-slate-300 focus:ring-rose-500 mt-0.5">
                <div>
                    <span class="text-xs font-bold text-slate-900 block">Status Ketersediaan Publik</span>
                    <span class="text-[11px] text-slate-500 leading-relaxed">Centang agar profilmu aktif tampil di katalog publik dan siap menerima pesanan dari pelanggan.</span>
                </div>
            </label>
        </div>

        <div class="pt-3 border-t border-slate-100 flex justify-end">
            <button type="submit"
                    class="w-full sm:w-auto px-6 py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white text-xs font-bold rounded-xl shadow-md shadow-rose-600/20 transition flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span>Simpan Perubahan Profil</span>
            </button>
        </div>
    </form>
</div>

