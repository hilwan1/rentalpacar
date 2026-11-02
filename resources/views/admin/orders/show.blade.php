<x-admin-layout title="Rincian Pesanan: {{ $order->order_code }} - RentalPacar Admin" header="Rincian Pesanan">
    @php
        $cleanCustPhone = preg_replace('/[^0-9]/', '', $order->customer_whatsapp);
        if (str_starts_with($cleanCustPhone, '0')) {
            $cleanCustPhone = '62' . substr($cleanCustPhone, 1);
        }

        $cleanTalentPhone = $order->talent ? preg_replace('/[^0-9]/', '', $order->talent->phone) : '';
        if (str_starts_with($cleanTalentPhone, '0')) {
            $cleanTalentPhone = '62' . substr($cleanTalentPhone, 1);
        }

        $isUpcoming = $order->booking_schedule > now();
    @endphp

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb & Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="text-xs text-slate-400 hover:text-rose-600 transition">Dashboard</a>
                    <span class="text-xs text-slate-300">/</span>
                    <a href="{{ route('admin.orders.index') }}" class="text-xs text-slate-400 hover:text-rose-600 transition">Pesanan Masuk</a>
                    <span class="text-xs text-slate-300">/</span>
                    <span class="text-xs font-bold text-rose-600 font-mono">{{ $order->order_code }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight font-mono">{{ $order->order_code }}</h1>
                    @if ($isUpcoming)
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 inline-flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                            <span>Jadwal Mendatang</span>
                        </span>
                    @else
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                            Selesai / Terlewat
                        </span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    Pesanan diterima sistem pada {{ $order->created_at->translatedFormat('l, d F Y - H:i') }} WIB.
                </p>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('admin.orders.index') }}"
                   class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali ke Daftar</span>
                </a>

                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan {{ $order->order_code }}?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-3.5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold rounded-xl border border-rose-200 transition inline-flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>Hapus</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- 2 Kolom Rincian Pesanan -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
            <!-- Kolom 1: Detail Paket Layanan & Jadwal -->
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs space-y-5">
                <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-900">Rincian Paket & Jadwal</h2>
                    <span class="text-xs font-bold text-rose-600">Snapshot Layanan</span>
                </div>

                <div class="space-y-4 text-xs">
                    <div>
                        <span class="text-[11px] font-semibold text-slate-400 block mb-0.5">Nama Paket Layanan</span>
                        <div class="text-sm font-bold text-slate-900">{{ $order->service_name_snapshot }}</div>
                    </div>

                    <div>
                        <span class="text-[11px] font-semibold text-slate-400 block mb-0.5">Tarif Layanan</span>
                        <div class="text-lg font-extrabold text-rose-600">
                            Rp {{ number_format($order->price_snapshot, 0, ',', '.') }}
                        </div>
                    </div>

                    <div>
                        <span class="text-[11px] font-semibold text-slate-400 block mb-0.5">Jadwal Tanggal & Jam Booking</span>
                        <div class="font-bold text-slate-800 text-sm">
                            {{ $order->booking_schedule ? $order->booking_schedule->translatedFormat('l, d F Y - H:i') . ' WIB' : '-' }}
                        </div>
                    </div>

                    <div>
                        <span class="text-[11px] font-semibold text-slate-400 block mb-0.5">Catatan Khusus Pelanggan</span>
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-slate-700 leading-relaxed italic">
                            {{ $order->notes ?: 'Tidak ada catatan khusus dari pelanggan.' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom 2: Identitas Pemesan & Talent -->
            <div class="space-y-6">
                <!-- Data Pemesan -->
                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs space-y-4">
                    <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                        <h2 class="text-sm font-bold text-slate-900">Pelanggan Pemesan</h2>
                        <span class="text-xs font-bold text-emerald-600">Kontak Pembeli</span>
                    </div>

                    <div class="space-y-3 text-xs">
                        <div>
                            <span class="text-[11px] font-semibold text-slate-400 block mb-0.5">Nama Pelanggan</span>
                            <div class="text-sm font-bold text-slate-900">{{ $order->customer_name }}</div>
                        </div>

                        <div>
                            <span class="text-[11px] font-semibold text-slate-400 block mb-0.5">Nomor WhatsApp</span>
                            <div class="flex items-center justify-between gap-3 mt-1">
                                <span class="font-mono font-bold text-slate-800 text-sm">{{ $order->customer_whatsapp }}</span>
                                <a href="https://wa.me/{{ $cleanCustPhone }}?text={{ rawurlencode('Halo ' . $order->customer_name . ', kami dari Admin RentalPacar mengenai pesanan Anda ' . $order->order_code) }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition inline-flex items-center gap-1.5 shadow-xs">
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                                    </svg>
                                    <span>Chat WA Pelanggan</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Talent -->
                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs space-y-4">
                    <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                        <h2 class="text-sm font-bold text-slate-900">Talent yang Dipesan</h2>
                        <span class="text-xs font-bold text-rose-600">Mitra Talent</span>
                    </div>

                    @if ($order->talent)
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl overflow-hidden bg-rose-100 text-rose-600 font-extrabold flex items-center justify-center shrink-0 border border-slate-200">
                                @if ($order->talent->avatar_url)
                                    <img src="{{ $order->talent->avatar_url }}" alt="{{ $order->talent->name }}" class="w-full h-full object-cover">
                                @else
                                    {{ strtoupper(substr($order->talent->name, 0, 2)) }}
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-bold text-slate-900 truncate">{{ $order->talent->name }}</h3>
                                <p class="text-xs text-slate-500 mt-0.5">📍 {{ $order->talent->city ?? 'Domisili Belum Diatur' }}</p>
                                <p class="text-xs text-slate-400 mt-0.5 truncate">{{ $order->talent->email }}</p>
                            </div>
                        </div>

                        <div class="pt-2 flex items-center justify-between gap-3 text-xs border-t border-slate-100">
                            <span class="font-mono text-slate-600 font-semibold">{{ $order->talent->phone }}</span>
                            @if ($cleanTalentPhone)
                                <a href="https://wa.me/{{ $cleanTalentPhone }}?text={{ rawurlencode('Halo ' . $order->talent->name . ', ada pesanan masuk ' . $order->order_code . ' dari ' . $order->customer_name . ' untuk jadwal ' . ($order->booking_schedule ? $order->booking_schedule->translatedFormat('d M Y, H:i') : '')) }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 fill-current text-emerald-600" viewBox="0 0 24 24">
                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                                    </svg>
                                    <span>Hubungi Talent</span>
                                </a>
                            @endif
                        </div>
                    @else
                        <p class="text-xs text-slate-400 italic">Data talent telah dihapus dari sistem.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

