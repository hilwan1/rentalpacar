<x-admin-layout title="Tambah Testimoni Baru - RentalPacar Admin" header="Tambah Testimoni">
    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Breadcrumb & Judul -->
        <div class="flex items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="text-xs text-slate-400 hover:text-rose-600 transition">Dashboard</a>
                    <span class="text-xs text-slate-300">/</span>
                    <a href="{{ route('admin.testimonials.index') }}" class="text-xs text-slate-400 hover:text-rose-600 transition">Testimoni</a>
                    <span class="text-xs text-slate-300">/</span>
                    <span class="text-xs font-bold text-rose-600">Tambah Baru</span>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Tambah Testimoni Pelanggan</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Masukkan ulasan nyata dari pelanggan untuk meningkatkan kepercayaan calon pengguna lain.
                </p>
            </div>

            <a href="{{ route('admin.testimonials.index') }}"
               class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition inline-flex items-center gap-1.5 shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali</span>
            </a>
        </div>

        @if ($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-700 shadow-xs">
                <div class="font-bold mb-1">Harap perbaiki kesalahan berikut:</div>
                <ul class="list-disc list-inside space-y-0.5 text-[11px]">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Card Form Tambah Testimoni -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
            <form action="{{ route('admin.testimonials.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Nama / Inisial Pelanggan -->
                <div>
                    <label for="customer_alias" class="block text-xs font-bold text-slate-800 mb-1.5">
                        Nama / Inisial Pelanggan <span class="text-rose-600">*</span>
                    </label>
                    <input type="text"
                           id="customer_alias"
                           name="customer_alias"
                           value="{{ old('customer_alias') }}"
                           required
                           placeholder="Contoh: Rian (27, Jakarta) atau K*** (Surabaya)"
                           class="w-full px-4 py-3 text-xs bg-slate-50 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:bg-white text-slate-800 transition @error('customer_alias') border-rose-400 bg-rose-50/30 @enderror">
                    <p class="text-[11px] text-slate-400 mt-1">
                        Demi menjaga privasi pelanggan, disarankan menggunakan inisial atau kombinasi nama depan dengan domisili.
                    </p>
                    @error('customer_alias')
                        <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Talent Terkait -->
                <div>
                    <label for="talent_id" class="block text-xs font-bold text-slate-800 mb-1.5">
                        Talent yang Bersangkutan <span class="text-slate-400 font-normal">(Opsional)</span>
                    </label>
                    <select id="talent_id"
                            name="talent_id"
                            class="w-full px-4 py-3 text-xs bg-slate-50 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:bg-white text-slate-800 transition cursor-pointer @error('talent_id') border-rose-400 bg-rose-50/30 @enderror">
                        <option value="">-- Layanan Umum / Tanpa Talent Khusus --</option>
                        @foreach ($talents as $talent)
                            <option value="{{ $talent->id }}" {{ old('talent_id') == $talent->id ? 'selected' : '' }}>
                                {{ $talent->name }} ({{ $talent->city ?? 'Domisili Belum Diisi' }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-slate-400 mt-1">
                        Pilih talent jika testimoni ini ditujukan untuk pelayanan seorang talent tertentu.
                    </p>
                    @error('talent_id')
                        <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rating Bintang -->
                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-2">
                        Rating Kepuasan <span class="text-rose-600">*</span>
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        @for ($r = 5; $r >= 1; $r--)
                            <label class="cursor-pointer">
                                <input type="radio"
                                       name="rating"
                                       value="{{ $r }}"
                                       class="sr-only peer"
                                       {{ old('rating', '5') == $r ? 'checked' : '' }}>
                                <div class="px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 peer-checked:bg-amber-50 peer-checked:border-amber-300 peer-checked:text-amber-800 text-slate-600 flex items-center gap-1.5 transition text-xs font-semibold">
                                    <span class="text-amber-500 flex items-center">
                                        @for ($j = 1; $j <= $r; $j++)
                                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                            </svg>
                                        @endfor
                                    </span>
                                    <span>{{ $r }} Bintang</span>
                                </div>
                            </label>
                        @endfor
                    </div>
                    @error('rating')
                        <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Isi Ulasan -->
                <div>
                    <label for="content" class="block text-xs font-bold text-slate-800 mb-1.5">
                        Isi Cerita / Ulasan Testimoni <span class="text-rose-600">*</span>
                    </label>
                    <textarea id="content"
                              name="content"
                              rows="4"
                              required
                              placeholder="Tulis ulasan jujur atau pesan kesan pelanggan di sini..."
                              class="w-full px-4 py-3 text-xs bg-slate-50 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:bg-white text-slate-800 transition leading-relaxed @error('content') border-rose-400 bg-rose-50/30 @enderror">{{ old('content') }}</textarea>
                    <p class="text-[11px] text-slate-400 mt-1">
                        Sampaikan pengalaman yang berkesan (misal keramahan suara, kehangatan obrolan, keseruan mabar, dsb).
                    </p>
                    @error('content')
                        <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Publikasi -->
                <div class="pt-2 border-t border-slate-100">
                    <label class="relative flex items-start gap-3 cursor-pointer select-none">
                        <div class="flex items-center h-5">
                            <input type="checkbox"
                                   name="is_published"
                                   value="1"
                                   {{ old('is_published', '1') ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-slate-300 text-rose-600 focus:ring-rose-500 cursor-pointer">
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-900 block">Langsung Tampilkan di Halaman Testimoni Publik</span>
                            <span class="text-[11px] text-slate-500 block mt-0.5">
                                Jika diaktifkan, ulasan ini akan langsung muncul bagi pengunjung di halaman publik <a href="{{ route('testimonials.index') }}" target="_blank" class="text-rose-600 underline">/testimonials</a>.
                            </span>
                        </div>
                    </label>
                </div>

                <!-- Tombol Aksi Simpan -->
                <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-end gap-3">
                    <a href="{{ route('admin.testimonials.index') }}"
                       class="w-full sm:w-auto px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition text-center">
                        Batal
                    </a>
                    <button type="submit"
                            class="w-full sm:w-auto px-6 py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white text-xs font-bold rounded-xl shadow-md shadow-rose-600/25 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Simpan & Terbitkan Testimoni</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
