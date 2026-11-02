<x-guest-layout title="Dashboard Talent - RentalPacar">
    <div class="w-full bg-white py-8 sm:py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Top Dashboard Header -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xs border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="relative shrink-0">
                        @if ($talent->avatar_url)
                            <img src="{{ $talent->avatar_url }}" alt="{{ $talent->name }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover object-center border-2 border-rose-100 shadow-sm">
                        @else
                            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100 text-2xl font-bold">
                                {{ strtoupper(substr($talent->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1.5">
                            <span class="px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-700 text-[11px] font-bold">
                                Portal Talent
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $talent->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $talent->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                                <span>{{ $talent->is_active ? 'Tersedia' : 'Nonaktif' }}</span>
                            </span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Halo, {{ $talent->name }}!</h1>
                        <p class="text-xs text-slate-500 mt-0.5">Kelola foto profil, tarif layanan, dan pantau pesanan masuk.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('talents.show', $talent->id) }}" target="_blank"
                       class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold rounded-xl border border-rose-200 transition flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <span>Lihat Profil Publik</span>
                    </a>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl border border-slate-200 transition">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>

            @if (session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-600 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Ringkasan Statistik Talent -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-xs">
                    <span class="text-xs font-semibold text-slate-500 block">Status Ketersediaan</span>
                    <span class="text-lg font-extrabold {{ $talent->is_active ? 'text-emerald-600' : 'text-slate-500' }} mt-1 block">
                        {{ $talent->is_active ? 'Tersedia untuk Dipesan' : 'Sedang Istirahat' }}
                    </span>
                    <span class="text-[11px] text-slate-400 mt-1 block">Bisa diubah sewaktu-waktu</span>
                </div>

                <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-xs">
                    <span class="text-xs font-semibold text-slate-500 block">Layanan Aktif</span>
                    <span class="text-2xl font-extrabold text-rose-600 mt-1 block">
                        {{ $services->where('is_active', true)->count() }} Paket
                    </span>
                    <span class="text-[11px] text-slate-400 mt-1 block">Dari total {{ $services->count() }} paket</span>
                </div>

                <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-xs">
                    <span class="text-xs font-semibold text-slate-500 block">Total Pesanan Masuk</span>
                    <span class="text-2xl font-extrabold text-slate-900 mt-1 block">
                        {{ $orders->count() }} Pesanan
                    </span>
                    <span class="text-[11px] text-slate-400 mt-1 block">Diteruskan ke WhatsApp</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Kolom Kiri: Profil Saya (5 Kolom) -->
                <div class="lg:col-span-5 bg-white rounded-3xl p-6 shadow-xs border border-slate-200/80 space-y-4">
                    <div class="border-b border-slate-100 pb-3">
                        <h2 class="text-base font-bold text-slate-900">Profil Saya</h2>
                        <p class="text-xs text-slate-500">Perbarui identitas dan ketersediaan layananmu.</p>
                    </div>

                    <form action="{{ route('talent.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <!-- Unggah Foto Profil -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Foto Profil</label>
                            <div class="flex items-center gap-3.5 p-3 rounded-2xl bg-slate-50 border border-slate-200">
                                <div class="relative shrink-0">
                                    <img id="avatar-preview"
                                         src="{{ $talent->avatar_url ?? 'data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'64\' height=\'64\' fill=\'%23f43f5e\' viewBox=\'0 0 24 24\'><path d=\'M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z\'/></svg>' }}"
                                         alt="Preview Foto"
                                         class="w-16 h-16 rounded-2xl object-cover object-center border border-slate-200 bg-white">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <label for="avatar-input" class="inline-block px-3 py-1.5 bg-white hover:bg-slate-100 text-slate-700 text-xs font-semibold rounded-lg border border-slate-200 cursor-pointer shadow-xs transition">
                                        Pilih Foto
                                    </label>
                                    <input type="file" name="avatar" id="avatar-input" accept="image/jpeg,image/png,image/webp" class="hidden">
                                    <p class="text-[11px] text-slate-400 mt-1">Format JPG, PNG, WebP (maks 2MB).</p>
                                    @if ($talent->avatar)
                                        <div class="mt-2">
                                            <label class="inline-flex items-center gap-1.5 text-[11px] text-rose-600 cursor-pointer">
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

                        <div>
                            <label for="name" class="block text-xs font-semibold text-slate-700 mb-1">Nama Panggung / Tampilan</label>
                            <input type="text" name="name" id="name" required value="{{ old('name', $talent->name) }}"
                                   class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500">
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">Email (Akun Login)</label>
                            <input type="email" id="email" disabled value="{{ $talent->email }}"
                                   class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-100 bg-slate-50 text-slate-500">
                        </div>

                        <div>
                            <label for="phone" class="block text-xs font-semibold text-slate-700 mb-1">Nomor WhatsApp</label>
                            <input type="tel" name="phone" id="phone" required value="{{ old('phone', $talent->phone) }}"
                                   class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500">
                        </div>

                        <div>
                            <label for="bio" class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi & Bio Singkat</label>
                            <textarea name="bio" id="bio" rows="3"
                                      placeholder="Ceritakan hobi, kepribadian, atau topik obrolan favoritmu..."
                                      class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500">{{ old('bio', $talent->bio) }}</textarea>
                        </div>

                        <div class="pt-2">
                            <label class="flex items-center gap-2.5 p-3 rounded-xl bg-rose-50/60 border border-rose-100 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" {{ $talent->is_active ? 'checked' : '' }}
                                       class="w-4 h-4 rounded text-rose-600 border-slate-300 focus:ring-rose-500">
                                <div>
                                    <span class="text-xs font-bold text-slate-800 block">Status Ketersediaan Publik</span>
                                    <span class="text-[11px] text-slate-500">Centang agar profilmu tampil di katalog dan dapat dipesan pelanggan.</span>
                                </div>
                            </label>
                        </div>

                        <button type="submit"
                                class="w-full py-3 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white text-xs font-bold rounded-xl shadow-md shadow-rose-600/20 transition">
                            Simpan Perubahan Profil
                        </button>
                    </form>
                </div>

                <!-- Kolom Kanan: Manajemen Paket Layanan & Tarif (7 Kolom) -->
                <div class="lg:col-span-7 space-y-6">
                    <!-- Form Tambah Paket Layanan -->
                    <div class="bg-white rounded-3xl p-6 shadow-xs border border-slate-200/80">
                        <div class="border-b border-slate-100 pb-3 mb-4">
                            <h2 class="text-base font-bold text-slate-900">Tambah Paket Layanan Baru</h2>
                            <p class="text-xs text-slate-500">Tentukan nama layanan, durasi, dan tarif yang kamu inginkan.</p>
                        </div>

                        <form action="{{ route('talent.services.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="service_name" class="block text-xs font-semibold text-slate-700 mb-1">Nama Layanan</label>
                                    <input type="text" name="service_name" id="service_name" required placeholder="Contoh: Chat Seharian, Voice Call 1 Jam"
                                           class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500">
                                </div>

                                <div>
                                    <label for="duration" class="block text-xs font-semibold text-slate-700 mb-1">Durasi</label>
                                    <input type="text" name="duration" id="duration" required placeholder="Contoh: 60 Menit, 1 Hari, 2 Match"
                                           class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="price" class="block text-xs font-semibold text-slate-700 mb-1">Tarif (Rupiah)</label>
                                    <input type="number" name="price" id="price" required min="5000" step="5000" placeholder="Contoh: 35000"
                                           class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500">
                                </div>

                                <div>
                                    <label for="description" class="block text-xs font-semibold text-slate-700 mb-1">Keterangan Singkat (Opsional)</label>
                                    <input type="text" name="description" id="description" placeholder="Penjelasan singkat paket..."
                                           class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500">
                                </div>
                            </div>

                            <button type="submit"
                                    class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition">
                                + Tambahkan Paket Layanan
                            </button>
                        </form>
                    </div>

                    <!-- Daftar Layanan Yang Ada -->
                    <div class="bg-white rounded-3xl p-6 shadow-xs border border-slate-200/80">
                        <div class="border-b border-slate-100 pb-3 mb-4 flex items-center justify-between">
                            <div>
                                <h2 class="text-base font-bold text-slate-900">Daftar Paket Layanan Milikmu</h2>
                                <p class="text-xs text-slate-500">Kelola status aktif atau nonaktif paket layanan.</p>
                            </div>
                            <span class="text-xs font-bold text-slate-600">{{ $services->count() }} Paket</span>
                        </div>

                        @if ($services->isNotEmpty())
                            <div class="space-y-3">
                                @foreach ($services as $srv)
                                    <div class="p-4 rounded-2xl border {{ $srv->is_active ? 'border-slate-200/80 bg-white' : 'border-slate-100 bg-slate-50/70 opacity-70' }} flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h3 class="text-xs font-bold text-slate-900">{{ $srv->service_name }}</h3>
                                                <span class="text-[10px] px-2 py-0.5 rounded-md font-bold {{ $srv->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                                    {{ $srv->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                                <span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md">
                                                    {{ $srv->duration }}
                                                </span>
                                            </div>
                                            @if ($srv->description)
                                                <p class="text-[11px] text-slate-500 mt-1">{{ $srv->description }}</p>
                                            @endif
                                            <span class="text-xs font-extrabold text-rose-600 mt-1 block">
                                                Rp {{ number_format($srv->price, 0, ',', '.') }}
                                            </span>
                                        </div>

                                        <form action="{{ route('talent.services.toggle', $srv->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="text-xs font-bold px-3 py-1.5 rounded-lg transition {{ $srv->is_active ? 'bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200' }}">
                                                {{ $srv->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-8 rounded-2xl bg-slate-50 border border-dashed border-slate-200 text-center">
                                <p class="text-xs font-semibold text-slate-700">Kamu belum menambahkan paket layanan.</p>
                                <p class="text-[11px] text-slate-500 mt-1">Gunakan formulir di atas untuk mulai menambahkan tarif layananmu.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Pesanan Masuk Terbaru Untuk Talent Ini -->
            <div class="bg-white rounded-3xl p-6 shadow-xs border border-slate-200/80">
                <div class="border-b border-slate-100 pb-3 mb-4">
                    <h2 class="text-base font-bold text-slate-900">Riwayat Pesanan Masuk</h2>
                    <p class="text-xs text-slate-500">Daftar pelanggan yang telah mengisi form pemesanan layananmu.</p>
                </div>

                @if ($orders->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="text-slate-400 border-b border-slate-100 pb-2">
                                    <th class="py-2.5 font-semibold">Kode Pesanan</th>
                                    <th class="py-2.5 font-semibold">Nama Pemesan</th>
                                    <th class="py-2.5 font-semibold">Paket Layanan</th>
                                    <th class="py-2.5 font-semibold">Jadwal Booking</th>
                                    <th class="py-2.5 font-semibold">Waktu Masuk</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($orders as $order)
                                    <tr>
                                        <td class="py-3 font-mono font-bold text-rose-600">{{ $order->order_code }}</td>
                                        <td class="py-3 font-semibold text-slate-800">{{ $order->customer_name }}</td>
                                        <td class="py-3 text-slate-700">{{ $order->service_name_snapshot }}</td>
                                        <td class="py-3 text-slate-600">{{ $order->booking_schedule->translatedFormat('d M Y, H:i') }} WIB</td>
                                        <td class="py-3 text-slate-400 text-[11px]">{{ $order->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                        <p class="text-xs font-semibold text-slate-700">Belum ada pesanan masuk.</p>
                        <p class="text-[11px] text-slate-500 mt-1">Pesanan yang disubmit oleh pelanggan akan otomatis tercatat di sini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('avatar-input');
            const preview = document.getElementById('avatar-preview');
            if (input && preview) {
                input.addEventListener('change', (e) => {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (event) => {
                            preview.src = event.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
</x-guest-layout>
