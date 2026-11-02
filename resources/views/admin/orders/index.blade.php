<x-admin-layout title="Pesanan Masuk - RentalPacar Admin" header="Pesanan Masuk">
    <div class="space-y-6">
        <!-- Top Header & Ringkasan -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <a href="{{ route('admin.dashboard') }}" class="text-xs text-slate-400 hover:text-rose-600 transition">Dashboard</a>
                    <span class="text-xs text-slate-300">/</span>
                    <span class="text-xs font-bold text-rose-600">Pesanan Masuk</span>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Daftar Pesanan Masuk</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Pantau seluruh riwayat transaksi pemesanan layanan talent yang dikirimkan pelanggan melalui sistem.
                </p>
            </div>

            <!-- Metrik Ringkas Pesanan -->
            <div class="flex flex-wrap items-center gap-2 shrink-0">
                <span class="px-3.5 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-semibold">
                    Total: <strong class="text-slate-900">{{ number_format($totalOrders, 0, ',', '.') }}</strong>
                </span>
                <span class="px-3.5 py-2 rounded-xl bg-rose-50 text-rose-700 border border-rose-200 text-xs font-semibold">
                    Hari Ini: <strong>{{ $todayOrders }}</strong>
                </span>
                <span class="px-3.5 py-2 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold">
                    Minggu Ini: <strong>{{ $weekOrders }}</strong>
                </span>
                <span class="px-3.5 py-2 rounded-xl bg-slate-900 text-white text-xs font-semibold">
                    Est. Omset: <strong class="text-rose-400">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</strong>
                </span>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 flex items-center gap-2 shadow-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-600 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Filter & Pencarian Pesanan -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
                <!-- Kolom Search -->
                <div class="relative flex-1 w-full">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text"
                           name="q"
                           value="{{ $search }}"
                           placeholder="Cari kode pesanan (RP-...), nama pemesan, no WA, atau nama talent..."
                           class="w-full pl-10 pr-4 py-2.5 text-xs bg-slate-50/50 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:bg-white transition">
                </div>

                <!-- Dropdown Filter Talent -->
                <div class="w-full sm:w-48 shrink-0">
                    <select name="talent_id"
                            onchange="this.form.submit()"
                            class="w-full px-3.5 py-2.5 text-xs bg-slate-50/50 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:bg-white text-slate-700 transition cursor-pointer">
                        <option value="">Semua Talent</option>
                        @foreach ($talents as $t)
                            <option value="{{ $t->id }}" {{ (string)$talentId === (string)$t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown Filter Waktu -->
                <div class="w-full sm:w-40 shrink-0">
                    <select name="date"
                            onchange="this.form.submit()"
                            class="w-full px-3.5 py-2.5 text-xs bg-slate-50/50 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:bg-white text-slate-700 transition cursor-pointer">
                        <option value="all" {{ $dateFilter === 'all' ? 'selected' : '' }}>Semua Waktu</option>
                        <option value="today" {{ $dateFilter === 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="this_week" {{ $dateFilter === 'this_week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="this_month" {{ $dateFilter === 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                    </select>
                </div>

                <!-- Tombol Submit & Reset -->
                <button type="submit"
                        class="w-full sm:w-auto px-5 py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white text-xs font-semibold rounded-xl shadow-xs transition shrink-0">
                    Cari
                </button>

                @if (!empty($search) || !empty($talentId) || $dateFilter !== 'all')
                    <a href="{{ route('admin.orders.index') }}"
                       class="w-full sm:w-auto px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition text-center shrink-0">
                        Reset Filter
                    </a>
                @endif
            </form>
        </div>

        <!-- Tabel Pesanan Masuk -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            @if ($orders->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50/70 text-slate-400 border-b border-slate-200/80">
                                <th class="py-3.5 px-5 font-semibold">Kode Pesanan</th>
                                <th class="py-3.5 px-4 font-semibold">Pelanggan Pemesan</th>
                                <th class="py-3.5 px-4 font-semibold">Talent yang Dipesan</th>
                                <th class="py-3.5 px-4 font-semibold">Paket Layanan</th>
                                <th class="py-3.5 px-4 font-semibold">Jadwal Booking</th>
                                <th class="py-3.5 px-4 font-semibold text-right">Tarif</th>
                                <th class="py-3.5 px-5 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($orders as $order)
                                @php
                                    $cleanCustPhone = preg_replace('/[^0-9]/', '', $order->customer_whatsapp);
                                    if (str_starts_with($cleanCustPhone, '0')) {
                                        $cleanCustPhone = '62' . substr($cleanCustPhone, 1);
                                    }
                                    $isUpcoming = $order->booking_schedule > now();
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition">
                                    <!-- Kode Pesanan & Waktu Submit -->
                                    <td class="py-4 px-5">
                                        <a href="{{ route('admin.orders.show', $order->id) }}"
                                           class="font-mono font-bold text-rose-600 hover:text-rose-700 hover:underline block text-xs">
                                            {{ $order->order_code }}
                                        </a>
                                        <span class="text-[11px] text-slate-400 block mt-0.5">
                                            {{ $order->created_at->translatedFormat('d M Y, H:i') }}
                                        </span>
                                    </td>

                                    <!-- Pelanggan & Kontak WhatsApp -->
                                    <td class="py-4 px-4">
                                        <div class="font-bold text-slate-900">{{ $order->customer_name }}</div>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <a href="https://wa.me/{{ $cleanCustPhone }}?text={{ rawurlencode('Halo ' . $order->customer_name . ', kami dari Admin RentalPacar mengenai pesanan ' . $order->order_code) }}"
                                               target="_blank"
                                               rel="noopener noreferrer"
                                               class="text-[11px] font-semibold text-emerald-600 hover:text-emerald-700 hover:underline inline-flex items-center gap-1">
                                                <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24">
                                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                                                </svg>
                                                <span>{{ $order->customer_whatsapp }}</span>
                                            </a>
                                        </div>
                                    </td>

                                    <!-- Talent yang Dipesan -->
                                    <td class="py-4 px-4">
                                        @if ($order->talent)
                                            <div class="flex items-center gap-2">
                                                <div class="w-7 h-7 rounded-full bg-rose-100 text-rose-600 font-bold flex items-center justify-center overflow-hidden shrink-0 text-[10px]">
                                                    @if ($order->talent->avatar_url)
                                                        <img src="{{ $order->talent->avatar_url }}" alt="{{ $order->talent->name }}" class="w-full h-full object-cover">
                                                    @else
                                                        {{ strtoupper(substr($order->talent->name, 0, 2)) }}
                                                    @endif
                                                </div>
                                                <div>
                                                    <span class="font-semibold text-slate-800 block">{{ $order->talent->name }}</span>
                                                    <span class="text-[10px] text-slate-400 block">{{ $order->talent->city ?? 'Indonesia' }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-slate-400 italic">Talent Dihapus</span>
                                        @endif
                                    </td>

                                    <!-- Paket Layanan -->
                                    <td class="py-4 px-4">
                                        <span class="font-medium text-slate-800 block">{{ $order->service_name_snapshot }}</span>
                                        @if ($order->notes)
                                            <span class="text-[11px] text-slate-400 line-clamp-1 italic mt-0.5" title="{{ $order->notes }}">
                                                "{{ $order->notes }}"
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Jadwal Booking -->
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        <div class="font-semibold text-slate-800">
                                            {{ $order->booking_schedule ? $order->booking_schedule->translatedFormat('d M Y, H:i') : '-' }}
                                        </div>
                                        <div class="mt-0.5">
                                            @if ($isUpcoming)
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                                    <span>Mendatang</span>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-[10px] font-medium text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">
                                                    <span>Selesai / Terlewat</span>
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Tarif Snapshot -->
                                    <td class="py-4 px-4 text-right whitespace-nowrap">
                                        <span class="font-extrabold text-slate-900 block text-xs">
                                            Rp {{ number_format($order->price_snapshot, 0, ',', '.') }}
                                        </span>
                                    </td>

                                    <!-- Aksi -->
                                    <td class="py-4 px-5 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <!-- Detail Button -->
                                            <a href="{{ route('admin.orders.show', $order->id) }}"
                                               class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 transition"
                                               title="Lihat rincian lengkap pesanan">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>

                                            <!-- Hubungi Pelanggan via WhatsApp -->
                                            <a href="https://wa.me/{{ $cleanCustPhone }}?text={{ rawurlencode('Halo Kak ' . $order->customer_name . ', kami dari RentalPacar mengonfirmasi pesanan Anda dengan kode ' . $order->order_code) }}"
                                               target="_blank"
                                               rel="noopener noreferrer"
                                               class="p-1.5 rounded-lg border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 transition"
                                               title="Chat WhatsApp Pelanggan">
                                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                                                </svg>
                                            </a>

                                            <!-- Hapus Pesanan -->
                                            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan pesanan {{ $order->order_code }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="p-1.5 rounded-lg border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 transition"
                                                        title="Hapus riwayat pesanan">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Halaman -->
                @if ($orders->hasPages())
                    <div class="p-4 sm:p-5 border-t border-slate-100 bg-slate-50/50">
                        {{ $orders->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="p-10 sm:p-14 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800 mb-1">Tidak Ada Pesanan Ditemukan</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto mb-5">
                        @if (!empty($search) || !empty($talentId) || $dateFilter !== 'all')
                            Tidak ada pesanan yang sesuai dengan filter pencarian Anda saat ini.
                        @else
                            Belum ada pesanan masuk dari pelanggan saat ini.
                        @endif
                    </p>
                    @if (!empty($search) || !empty($talentId) || $dateFilter !== 'all')
                        <a href="{{ route('admin.orders.index') }}"
                           class="inline-block px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-xl transition">
                            Reset Filter Pencarian
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>

