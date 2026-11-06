@props(['talent', 'services'])

<div class="space-y-6">
    <!-- Form Tambah Paket Layanan Baru -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xs border border-slate-200/80">
        <div class="border-b border-slate-100 pb-4 mb-5 flex items-center justify-between">
            <div>
                <h2 class="text-base font-extrabold text-slate-900">Tambah Paket Layanan Baru</h2>
                <p class="text-xs text-slate-500 mt-0.5">Tentukan nama layanan, durasi waktu, dan tarif harga yang kamu tawarkan.</p>
            </div>
            <div class="w-9 h-9 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
            </div>
        </div>

        @if ($errors->has('service_name') || $errors->has('category') || $errors->has('duration') || $errors->has('price') || $errors->has('description'))
            <div class="mb-5 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-700 space-y-1">
                @foreach (['service_name', 'category', 'duration', 'price', 'description'] as $field)
                    @error($field)
                        <p class="font-medium">• {{ $message }}</p>
                    @enderror
                @endforeach
            </div>
        @endif

        <form action="{{ route('talent.services.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Kategori Paket Layanan <span class="text-rose-600">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="relative flex items-center gap-3 p-3 rounded-xl border border-slate-200 cursor-pointer hover:border-slate-300 has-[:checked]:border-rose-600 has-[:checked]:bg-rose-50/40 transition">
                        <input type="radio" name="category" value="basic" {{ old('category', 'basic') === 'basic' ? 'checked' : '' }} class="text-rose-600 focus:ring-rose-500">
                        <div>
                            <span class="text-xs font-bold text-slate-900 block">Paket Basic</span>
                            <span class="text-[11px] text-slate-500 block">Chat seharian, curhat, voice call reguler</span>
                        </div>
                    </label>

                    <label class="relative flex items-center gap-3 p-3 rounded-xl border border-slate-200 cursor-pointer hover:border-slate-300 has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50/40 transition">
                        <input type="radio" name="category" value="premium" {{ old('category') === 'premium' ? 'checked' : '' }} class="text-amber-600 focus:ring-amber-500">
                        <div>
                            <span class="text-xs font-bold text-slate-900 flex items-center gap-1">
                                <span>Paket Premium</span>
                                <span class="text-[11px] text-amber-600 font-extrabold">⭐</span>
                            </span>
                            <span class="text-[11px] text-slate-500 block">Video call, date virtual, mabar prioritas</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="service_name" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Nama Layanan <span class="text-rose-600">*</span>
                    </label>
                    <input type="text"
                           name="service_name"
                           id="service_name"
                           required
                           value="{{ old('service_name') }}"
                           placeholder="Contoh: Chat Seharian, Voice Call 1 Jam"
                           class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 @error('service_name') border-rose-400 bg-rose-50/40 @enderror">
                </div>

                <div>
                    <label for="duration" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Durasi <span class="text-rose-600">*</span>
                    </label>
                    <input type="text"
                           name="duration"
                           id="duration"
                           required
                           value="{{ old('duration') }}"
                           placeholder="Contoh: 60 Menit, 1 Hari, 2 Match"
                           class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 @error('duration') border-rose-400 bg-rose-50/40 @enderror">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="price" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Tarif (Rupiah) <span class="text-rose-600">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs font-bold text-slate-400 pointer-events-none">Rp</span>
                        <input type="number"
                               name="price"
                               id="price"
                               required
                               min="5000"
                               step="5000"
                               value="{{ old('price') }}"
                               placeholder="35000"
                               class="w-full text-xs pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 @error('price') border-rose-400 bg-rose-50/40 @enderror">
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Keterangan Singkat (Opsional)
                    </label>
                    <input type="text"
                           name="description"
                           id="description"
                           value="{{ old('description') }}"
                           placeholder="Contoh: Fast response, ngobrol santai..."
                           class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500">
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit"
                        class="w-full sm:w-auto px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition active:scale-95 shadow-xs flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>+ Tambahkan Paket Layanan</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Daftar Paket Layanan Milik Talent -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xs border border-slate-200/80 space-y-4">
        <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
            <div>
                <h2 class="text-base font-extrabold text-slate-900">Daftar Paket Layanan Milikmu</h2>
                <p class="text-xs text-slate-500 mt-0.5">Kelola status aktif atau nonaktif paket layanan yang tampil di profilmu.</p>
            </div>
            <span class="text-xs font-bold px-3 py-1 rounded-full bg-slate-100 text-slate-700">
                {{ $services->count() }} Paket
            </span>
        </div>

        @if ($services->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($services as $srv)
                    <div class="p-4 rounded-2xl border transition-all duration-150 flex flex-col justify-between {{ $srv->is_active ? ($srv->isPremium() ? 'border-amber-200/90 bg-gradient-to-br from-amber-50/25 via-white to-white hover:border-amber-300 shadow-2xs' : 'border-slate-200 bg-white hover:border-rose-200 shadow-2xs') : 'border-slate-100 bg-slate-50/70 opacity-75' }}">
                        <div class="space-y-2">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1.5 flex-wrap mb-1">
                                        @if ($srv->isPremium())
                                            <span class="inline-flex items-center gap-1 text-[10px] px-2 py-0.5 rounded-md font-extrabold bg-amber-50 text-amber-800 border border-amber-300">
                                                <span>⭐</span>
                                                <span>Premium</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center text-[10px] px-2 py-0.5 rounded-md font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                                Basic
                                            </span>
                                        @endif
                                        <span class="text-[10px] px-2 py-0.5 rounded-md font-bold shrink-0 {{ $srv->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-200 text-slate-600' }}">
                                            {{ $srv->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </div>
                                    <h3 class="text-xs sm:text-sm font-bold text-slate-900 leading-snug">{{ $srv->service_name }}</h3>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md font-medium">
                                    ⏱ {{ $srv->duration }}
                                </span>
                                <span class="text-xs font-extrabold text-rose-600">
                                    Rp {{ number_format($srv->price, 0, ',', '.') }}
                                </span>
                            </div>

                            @if ($srv->description)
                                <p class="text-[11px] text-slate-500 leading-relaxed">{{ $srv->description }}</p>
                            @endif
                        </div>

                        <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                            <form action="{{ route('talent.services.toggle', $srv->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="text-xs font-bold px-3 py-1.5 rounded-xl transition active:scale-95 {{ $srv->is_active ? 'bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200' }}">
                                    {{ $srv->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>

                            <form action="{{ route('talent.services.destroy', $srv->id) }}" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin menghapus paket layanan {{ $srv->service_name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="p-1.5 rounded-xl border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 transition active:scale-95"
                                        title="Hapus paket layanan">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-8 rounded-2xl bg-slate-50 border border-dashed border-slate-200 text-center">
                <div class="w-12 h-12 rounded-2xl bg-white text-slate-400 flex items-center justify-center mx-auto mb-3 shadow-2xs border border-slate-100">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <p class="text-xs font-bold text-slate-700">Kamu belum menambahkan paket layanan.</p>
                <p class="text-[11px] text-slate-500 mt-1">Gunakan formulir di atas untuk mulai menambahkan tarif layananmu.</p>
            </div>
        @endif
    </div>
</div>

