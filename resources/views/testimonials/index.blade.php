<x-guest-layout title="Testimoni Pelanggan - RentalPacar">
    <div class="w-full bg-white py-8 sm:py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Halaman -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 mb-8 border-b border-slate-100">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <a href="{{ route('home') }}"
                           class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-full transition focus:outline-none focus:ring-2 focus:ring-rose-500"
                           aria-label="Kembali ke halaman utama">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                            <span>Menu Utama</span>
                        </a>
                        <span class="text-xs text-slate-400">/</span>
                        <span class="text-xs font-medium text-slate-500">Testimoni</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Testimoni Pelanggan</h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-lg">
                        Cerita dan pengalaman jujur dari teman-teman yang telah memesan layanan teman kencan virtual kami.
                    </p>
                </div>

                <!-- Tombol Buka Form Testimoni -->
                <button type="button"
                        onclick="toggleTestimonialForm()"
                        class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white text-xs font-bold rounded-2xl shadow-md shadow-rose-600/20 transition flex items-center justify-center gap-2 shrink-0 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Tulis Testimoni</span>
                </button>
            </div>

            <!-- Notifikasi Flash Sukses -->
            @if (session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 flex items-start gap-3 shadow-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h3 class="font-bold text-emerald-900">Ulasan Berhasil Dikirim!</h3>
                        <p class="text-[11px] text-emerald-700 mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Notifikasi Error Validasi -->
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-700 shadow-xs">
                    <div class="font-bold mb-1">Gagal mengirim ulasan, silakan periksa:</div>
                    <ul class="list-disc list-inside space-y-0.5 text-[11px]">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Card Form Tambah Testimoni (Mode Guest & Pengguna Umum) -->
            <div id="testimonial-form-card" class="{{ $errors->any() ? 'block' : 'hidden' }} mb-8 bg-white rounded-3xl p-6 sm:p-8 border-2 border-rose-100 shadow-sm transition-all duration-200">
                <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-sm font-bold">✍️</span>
                            <h2 class="text-base sm:text-lg font-bold text-slate-900">Tulis Testimoni Pengalaman Kamu</h2>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Bagikan kesan jujur kamu selama mengobrol atau menggunakan layanan RentalPacar.</p>
                    </div>
                    <button type="button" onclick="toggleTestimonialForm()" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100 transition" title="Tutup Form">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form action="{{ route('testimonials.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Nama / Inisial -->
                        <div>
                            <label for="customer_alias" class="block text-xs font-bold text-slate-800 mb-1.5">
                                Nama / Panggilan Kamu <span class="text-rose-600">*</span>
                            </label>
                            <input type="text"
                                   id="customer_alias"
                                   name="customer_alias"
                                   value="{{ old('customer_alias') }}"
                                   required
                                   placeholder="Contoh: Rian (Jakarta) atau Alex"
                                   class="w-full px-4 py-2.5 text-xs bg-slate-50 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:bg-white text-slate-800 transition">
                            <p class="text-[11px] text-slate-400 mt-1">Demi privasi, kamu bebas mencantumkan inisial atau nama samaran.</p>
                            @error('customer_alias')
                                <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Talent yang Bersangkutan -->
                        <div>
                            <label for="talent_id" class="block text-xs font-bold text-slate-800 mb-1.5">
                                Talent yang Melayanimu <span class="text-slate-400 font-normal">(Opsional)</span>
                            </label>
                            <select id="talent_id"
                                    name="talent_id"
                                    class="w-full px-4 py-2.5 text-xs bg-slate-50 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:bg-white text-slate-800 transition cursor-pointer">
                                <option value="">-- Layanan Umum / Tanpa Talent Khusus --</option>
                                @if (isset($talents))
                                    @foreach ($talents as $talent)
                                        <option value="{{ $talent->id }}" {{ old('talent_id') == $talent->id ? 'selected' : '' }}>
                                            {{ $talent->name }} {{ $talent->city ? "({$talent->city})" : '' }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <p class="text-[11px] text-slate-400 mt-1">Pilih talent tertentu jika testimoni ini ditujukan khusus untuk talent tersebut.</p>
                            @error('talent_id')
                                <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Rating Kepuasan Bintang -->
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1.5">
                            Rating Kepuasan <span class="text-rose-600">*</span>
                        </label>
                        <div class="flex flex-wrap items-center gap-2">
                            @for ($r = 5; $r >= 1; $r--)
                                <label class="cursor-pointer">
                                    <input type="radio"
                                           name="rating"
                                           value="{{ $r }}"
                                           class="sr-only peer"
                                           {{ old('rating', '5') == $r ? 'checked' : '' }}>
                                    <div class="px-3 py-1.5 rounded-xl border border-slate-200 bg-slate-50 peer-checked:bg-amber-50 peer-checked:border-amber-300 peer-checked:text-amber-800 text-slate-600 flex items-center gap-1.5 transition text-xs font-semibold">
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

                    <!-- Isi Cerita / Ulasan -->
                    <div>
                        <label for="content" class="block text-xs font-bold text-slate-800 mb-1.5">
                            Cerita / Ulasan Pengalaman Kamu <span class="text-rose-600">*</span>
                        </label>
                        <textarea id="content"
                                  name="content"
                                  rows="3"
                                  required
                                  placeholder="Tuliskan pengalaman serumu di sini, misalnya keramahan ngobrol, teman curhat yang baik, keseruan main bareng, dll..."
                                  class="w-full px-4 py-2.5 text-xs bg-slate-50 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:bg-white text-slate-800 transition leading-relaxed">{{ old('content') }}</textarea>
                        @error('content')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit & Batal Buttons -->
                    <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-slate-100">
                        <button type="button"
                                onclick="toggleTestimonialForm()"
                                class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white text-xs font-bold rounded-xl shadow-md shadow-rose-600/25 transition flex items-center gap-1.5 cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            <span>Kirim Testimoni</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Daftar Kartu Testimoni (Grid 2 Kolom di Desktop, 1 Kolom di HP) -->
            @if ($testimonials->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
                    @foreach ($testimonials as $item)
                        <article class="bg-white rounded-2xl p-5 shadow-xs border border-slate-200/80 hover:border-rose-200 hover:shadow-md transition-all flex flex-col justify-between">
                            <div>
                                <div class="flex items-start justify-between gap-3 mb-3">
                                    <div>
                                        <h2 class="text-sm font-bold text-slate-800">{{ $item->customer_alias }}</h2>
                                        @if ($item->talent)
                                            <p class="text-xs text-rose-600 font-semibold mt-0.5">
                                                Layanan bersama {{ $item->talent->name }}
                                            </p>
                                        @endif
                                    </div>

                                    <!-- Rating Bintang Kuning -->
                                    <div class="flex items-center gap-0.5 bg-amber-50 px-2 py-1 rounded-lg border border-amber-100" aria-label="Rating {{ $item->rating }} dari 5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-3.5 h-3.5 {{ $i <= $item->rating ? 'text-amber-400 fill-current' : 'text-slate-200 fill-current' }}" viewBox="0 0 24 24" aria-hidden="true">
                                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>

                                <!-- Isi Ulasan -->
                                <blockquote class="text-xs sm:text-sm text-slate-700 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100 mb-3">
                                    "{{ $item->content }}"
                                </blockquote>

                                <!-- Screenshot Percakapan (Jika Ada) -->
                                @if ($item->screenshot_url)
                                    <div class="mt-2 mb-3">
                                        <a href="{{ $item->screenshot_url }}" target="_blank" rel="noopener noreferrer" class="block overflow-hidden rounded-xl border border-slate-200 hover:opacity-95 transition">
                                            <img src="{{ $item->screenshot_url }}" alt="Bukti chat testimoni {{ $item->customer_alias }}" class="w-full h-auto max-h-56 object-cover object-top" loading="lazy">
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="text-[11px] text-slate-400 pt-2 border-t border-slate-100 flex items-center justify-between">
                                <span class="inline-flex items-center gap-1 text-emerald-600 font-medium">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Terverifikasi Admin</span>
                                </span>
                                <span>{{ $item->created_at->translatedFormat('d F Y') }}</span>
                            </div>
                        </article>
                    @endforeach
                </div>

                @if ($testimonials->hasPages())
                    <div class="pt-4">
                        {{ $testimonials->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="bg-slate-50 rounded-3xl p-10 sm:p-14 border border-slate-100 text-center max-w-lg mx-auto flex flex-col items-center">
                    <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-800 mb-2">Belum Ada Ulasan</h2>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed mb-6">
                        Belum ada testimoni publik. Kamu bisa menjadi yang pertama membagikan pengalaman menyenangkan bersama talent kami!
                    </p>
                    <div class="flex flex-wrap items-center justify-center gap-3">
                        <button type="button"
                                onclick="toggleTestimonialForm()"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-md shadow-rose-600/20 transition cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span>Tulis Testimoni Pertama</span>
                        </button>
                        <a href="{{ route('talents.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white hover:bg-slate-100 text-slate-700 text-xs sm:text-sm font-semibold rounded-xl border border-slate-200 transition">
                            <span>Lihat Daftar Talent</span>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleTestimonialForm() {
            const formCard = document.getElementById('testimonial-form-card');
            if (formCard) {
                formCard.classList.toggle('hidden');
                if (!formCard.classList.contains('hidden')) {
                    formCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    document.getElementById('customer_alias')?.focus();
                }
            }
        }
    </script>
    @endpush
</x-guest-layout>
