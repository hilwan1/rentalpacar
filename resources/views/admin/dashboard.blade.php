<x-admin-layout title="Dashboard Admin - RentalPacar" header="Dashboard Ringkasan">
    <div class="space-y-6">
        <!-- Banner Sambutan Admin -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-900 text-white text-xs font-bold mb-2">
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                    <span>Panel Administrator</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Halo, {{ auth()->user()->name }}!
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Kelola data pesanan masuk, daftar talent aktif, serta pantau pertumbuhan RentalPacar.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <span class="px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-600">
                    {{ now()->translatedFormat('l, d F Y') }}
                </span>
            </div>
        </div>

        <!-- 4 Kotak Statistik Ringkas -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Total Pesanan -->
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-slate-500">Total Pesanan</span>
                    <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                    {{ number_format($totalOrders, 0, ',', '.') }}
                </div>
                <div class="mt-2 flex items-center gap-1.5 text-[11px] text-slate-500">
                    <span class="font-bold text-rose-600">+{{ $weeklyOrders }}</span>
                    <span>pesanan minggu ini</span>
                </div>
            </div>

            <!-- Talent Terdaftar -->
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-slate-500">Talent Terdaftar</span>
                    <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                    {{ $totalTalents }}
                </div>
                <div class="mt-2 flex items-center gap-1.5 text-[11px] text-slate-500">
                    <span class="inline-flex items-center gap-1 text-emerald-600 font-bold">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        {{ $activeTalents }} aktif
                    </span>
                    <span>siap dipesan publik</span>
                </div>
            </div>

            <!-- Testimoni Pelanggan -->
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-slate-500">Testimoni Publik</span>
                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                    {{ $totalTestimonials }}
                </div>
                <div class="mt-2 text-[11px] text-slate-500">
                    Ulasan kepuasan pelanggan
                </div>
            </div>

            <!-- Status Sistem -->
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-slate-500">Status Server</span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
                <div class="text-lg font-extrabold text-emerald-600">
                    Aktif Normal
                </div>
                <div class="mt-2 text-[11px] text-slate-500">
                    Database MySQL Terhubung
                </div>
            </div>
        </div>

        <!-- Bagian Konten: Tabel Pesanan & Daftar Talent -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Kolom Kiri: Pesanan Masuk Terbaru (8 Kolom) -->
            <section id="section-pesanan" class="lg:col-span-8 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Pesanan Masuk Terbaru</h2>
                        <p class="text-xs text-slate-500">Daftar transaksi pemesanan yang diteruskan pelanggan ke WhatsApp.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 text-[11px] font-bold">
                            {{ $recentOrders->count() }} Data Terbaru
                        </span>
                        <a href="{{ route('admin.orders.index') }}" class="text-xs text-rose-600 hover:text-rose-700 font-bold hover:underline transition">
                            Lihat Semua →
                        </a>
                    </div>
                </div>

                @if ($recentOrders->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="text-slate-400 border-b border-slate-100 pb-2">
                                    <th class="py-2.5 font-semibold">Kode</th>
                                    <th class="py-2.5 font-semibold">Pemesan</th>
                                    <th class="py-2.5 font-semibold">Talent</th>
                                    <th class="py-2.5 font-semibold">Layanan & Tarif</th>
                                    <th class="py-2.5 font-semibold">Jadwal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($recentOrders as $order)
                                    <tr>
                                        <td class="py-3 font-mono font-bold text-rose-600">{{ $order->order_code }}</td>
                                        <td class="py-3">
                                            <span class="font-semibold text-slate-800 block">{{ $order->customer_name }}</span>
                                            <span class="text-[11px] text-slate-400">{{ $order->customer_whatsapp }}</span>
                                        </td>
                                        <td class="py-3 font-medium text-slate-700">
                                            {{ $order->talent->name ?? 'Talent' }}
                                        </td>
                                        <td class="py-3">
                                            <span class="text-slate-800 font-medium block">{{ $order->service_name_snapshot }}</span>
                                            <span class="text-[11px] text-rose-600 font-bold">Rp {{ number_format($order->price_snapshot, 0, ',', '.') }}</span>
                                        </td>
                                        <td class="py-3 text-slate-600">
                                            <span>{{ $order->booking_schedule->translatedFormat('d M Y, H:i') }}</span>
                                            <span class="text-[10px] text-slate-400 block">{{ $order->created_at->diffForHumans() }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                        <p class="text-xs font-semibold text-slate-700">Belum ada pesanan masuk di sistem.</p>
                        <p class="text-[11px] text-slate-400 mt-1">Saat pelanggan mengonfirmasi order di halaman detail talent, pesanan akan otomatis tercatat di sini.</p>
                    </div>
                @endif
            </section>

            <!-- Kolom Kanan: Ringkasan Talent (4 Kolom) -->
            <section id="section-talent" class="lg:col-span-4 space-y-6">
                <!-- Daftar Talent Aktif -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h2 class="text-base font-bold text-slate-900">Talent Terdaftar</h2>
                        <a href="{{ route('admin.talents.index') }}" class="text-xs font-semibold text-rose-600 hover:underline">
                            Kelola Semua &rarr;
                        </a>
                    </div>

                    @if ($talents->isNotEmpty())
                        <div class="space-y-3">
                            @foreach ($talents as $t)
                                <div class="flex items-center justify-between gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-100">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center font-bold text-xs text-rose-600">
                                            @if ($t->avatar_url)
                                                <img src="{{ $t->avatar_url }}" alt="{{ $t->name }}" class="w-full h-full object-cover">
                                            @else
                                                {{ strtoupper(substr($t->name, 0, 1)) }}
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-xs font-bold text-slate-900 truncate">{{ $t->name }}</p>
                                            <p class="text-[11px] text-slate-500 truncate">
                                                @if ($t->city)
                                                    📍 {{ $t->city }} &bull;
                                                @endif
                                                {{ $t->orders_count }} order
                                            </p>
                                        </div>
                                    </div>

                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold shrink-0 {{ $t->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600' }}">
                                        {{ $t->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-slate-400 text-center py-4">Belum ada akun talent terdaftar.</p>
                    @endif
                </div>

                <!-- Pintasan Cepat Menu Admin -->
                <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl p-6 text-white space-y-3 shadow-md">
                    <span class="text-[10px] font-bold text-rose-400 uppercase tracking-wider block">Pintasan Akses</span>
                    <h3 class="text-sm font-bold">Pantau Top Talent Mingguan</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Lihat talent teratas minggu ini berdasarkan akumulasi pesanan masuk pelanggan.
                    </p>
                    <a href="{{ route('top-talents.index') }}" target="_blank"
                       class="inline-block mt-2 px-4 py-2 bg-rose-600 hover:bg-rose-500 active:scale-95 text-white text-xs font-semibold rounded-xl transition">
                        Buka Leaderboard Top Talent 🏆
                    </a>
                </div>
            </section>
        </div>
    </div>
</x-admin-layout>
