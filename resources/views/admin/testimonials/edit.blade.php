<x-admin-layout title="Edit Testimoni - RentalPacar Admin" header="Edit Testimoni">
    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Breadcrumb & Judul -->
        <div class="flex items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="text-xs text-slate-400 hover:text-rose-600 transition">Dashboard</a>
                    <span class="text-xs text-slate-300">/</span>
                    <a href="{{ route('admin.testimonials.index') }}" class="text-xs text-slate-400 hover:text-rose-600 transition">Testimoni</a>
                    <span class="text-xs text-slate-300">/</span>
                    <span class="text-xs font-bold text-rose-600">Edit Testimoni</span>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Edit Testimoni: {{ $testimonial->customer_alias }}</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Perbarui isi ulasan, rating, atau ganti bukti tangkapan layar percakapan.
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

        <!-- Card Form Edit Testimoni -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
            <form action="{{ route('admin.testimonials.update', $testimonial->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Nama / Inisial Pelanggan -->
                <div>
                    <label for="customer_alias" class="block text-xs font-bold text-slate-800 mb-1.5">
                        Nama / Inisial Pelanggan <span class="text-rose-600">*</span>
                    </label>
                    <input type="text"
                           id="customer_alias"
                           name="customer_alias"
                           value="{{ old('customer_alias', $testimonial->customer_alias) }}"
                           required
                           placeholder="Contoh: Rian (27, Jakarta)"
                           class="w-full px-4 py-3 text-xs bg-slate-50 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:bg-white text-slate-800 transition @error('customer_alias') border-rose-400 bg-rose-50/30 @enderror">
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
                            <option value="{{ $talent->id }}" {{ old('talent_id', $testimonial->talent_id) == $talent->id ? 'selected' : '' }}>
                                {{ $talent->name }} ({{ $talent->city ?? 'Domisili Belum Diisi' }})
                            </option>
                        @endforeach
                    </select>
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
                                       {{ old('rating', $testimonial->rating) == $r ? 'checked' : '' }}>
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
                              class="w-full px-4 py-3 text-xs bg-slate-50 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:bg-white text-slate-800 transition leading-relaxed @error('content') border-rose-400 bg-rose-50/30 @enderror">{{ old('content', $testimonial->content) }}</textarea>
                    @error('content')
                        <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bukti Screenshot Saat Ini & Form Upload Baru -->
                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1.5">
                        Bukti Screenshot Chat Percakapan <span class="text-slate-400 font-normal">(Opsional)</span>
                    </label>

                    @if ($testimonial->screenshot_url)
                        <div class="mb-4 p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <a href="{{ $testimonial->screenshot_url }}" target="_blank" rel="noopener noreferrer" class="shrink-0">
                                    <img src="{{ $testimonial->screenshot_url }}" alt="Screenshot Bukti" class="w-16 h-16 rounded-xl object-cover border border-slate-200">
                                </a>
                                <div>
                                    <p class="text-xs font-bold text-slate-800">Screenshot Saat Ini Terpasang</p>
                                    <a href="{{ $testimonial->screenshot_url }}" target="_blank" rel="noopener noreferrer" class="text-[11px] text-rose-600 hover:underline">
                                        Buka Gambar Penuh ↗
                                    </a>
                                </div>
                            </div>

                            <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 px-3 py-2 rounded-xl border border-rose-200 transition">
                                <input type="checkbox" name="remove_screenshot" value="1" class="w-4 h-4 rounded border-rose-300 text-rose-600 focus:ring-rose-500">
                                <span>Hapus Screenshot</span>
                            </label>
                        </div>
                    @endif

                    <div class="relative border-2 border-dashed border-slate-200 hover:border-rose-300 rounded-2xl p-5 text-center transition bg-slate-50/50">
                        <input type="file"
                               id="screenshot"
                               name="screenshot"
                               accept="image/jpeg,image/png,image/jpg,image/webp"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                               onchange="previewTestimonialImage(this)">

                        <div id="upload-placeholder" class="flex flex-col items-center justify-center py-2">
                            <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center mb-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="text-xs font-bold text-slate-700">
                                {{ $testimonial->screenshot_url ? 'Klik untuk mengganti dengan screenshot baru' : 'Pilih gambar screenshot chat' }}
                            </p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Format JPG, PNG, atau WEBP (Maksimal 2 MB)</p>
                        </div>

                        <!-- Live Preview Box -->
                        <div id="image-preview-wrapper" class="hidden relative max-w-sm mx-auto">
                            <img id="image-preview" src="#" alt="Pratinjau Screenshot" class="rounded-xl max-h-56 mx-auto object-cover border border-slate-200 shadow-xs">
                            <p id="file-name-text" class="text-[11px] text-slate-600 font-medium mt-2 truncate"></p>
                            <button type="button"
                                    onclick="clearTestimonialImage(event)"
                                    class="relative z-20 mt-2 px-3 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 text-[11px] font-semibold rounded-lg transition inline-flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span>Batalkan Pilihan Baru</span>
                            </button>
                        </div>
                    </div>

                    @error('screenshot')
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
                                   {{ old('is_published', $testimonial->is_published) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-slate-300 text-rose-600 focus:ring-rose-500 cursor-pointer">
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-900 block">Tampilkan di Halaman Testimoni Publik</span>
                            <span class="text-[11px] text-slate-500 block mt-0.5">
                                Aktifkan agar testimoni dapat dibaca oleh publik di <a href="{{ route('testimonials.index') }}" target="_blank" class="text-rose-600 underline">/testimonials</a>.
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
                        <span>Simpan Perubahan Testimoni</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Live Image Preview -->
    <script>
        function previewTestimonialImage(input) {
            const file = input.files?.[0];
            const placeholder = document.getElementById('upload-placeholder');
            const previewWrapper = document.getElementById('image-preview-wrapper');
            const preview = document.getElementById('image-preview');
            const fileNameText = document.getElementById('file-name-text');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    fileNameText.textContent = file.name + ' (' + Math.round(file.size / 1024) + ' KB)';
                    placeholder.classList.add('hidden');
                    previewWrapper.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        function clearTestimonialImage(e) {
            e.preventDefault();
            const input = document.getElementById('screenshot');
            const placeholder = document.getElementById('upload-placeholder');
            const previewWrapper = document.getElementById('image-preview-wrapper');
            const preview = document.getElementById('image-preview');
            const fileNameText = document.getElementById('file-name-text');

            input.value = '';
            preview.src = '#';
            fileNameText.textContent = '';
            previewWrapper.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }
    </script>
</x-admin-layout>

