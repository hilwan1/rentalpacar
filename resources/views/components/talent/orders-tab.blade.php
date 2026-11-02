@props(['talent', 'orders', 'totalOrdersCount' => null])

<div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xs border border-slate-200/80 space-y-4">
    <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
        <div>
            <h2 class="text-base font-extrabold text-slate-900">Riwayat Pesanan Masuk</h2>
            <p class="text-xs text-slate-500 mt-0.5">Menampilkan 5 pesanan terbaru yang masuk dari pelanggan.</p>
        </div>
        <span class="text-xs font-bold px-3 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-100">
            @if ($totalOrdersCount && $totalOrdersCount > $orders->count())
                {{ $orders->count() }} Terbaru dari {{ $totalOrdersCount }} Pesanan
            @else
                {{ $orders->count() }} Pesanan
            @endif
        </span>
    </div>

    @if ($orders->isNotEmpty())
        <!-- Tampilan Desktop (Table View) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-100 text-[11px] uppercase tracking-wider">
                        <th class="py-3 font-bold">Kode Pesanan</th>
                        <th class="py-3 font-bold">Nama Pemesan</th>
                        <th class="py-3 font-bold">Paket Layanan</th>
                        <th class="py-3 font-bold">Jadwal Booking</th>
                        <th class="py-3 font-bold">Waktu Masuk</th>
                        <th class="py-3 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($orders as $order)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3.5 font-mono font-bold text-rose-600">
                                <a href="{{ route('orders.show', $order->order_code) }}" target="_blank" class="hover:underline">
                                    {{ $order->order_code }}
                                </a>
                            </td>
                            <td class="py-3.5">
                                <div class="font-bold text-slate-800">{{ $order->customer_name }}</div>
                                <div class="text-[11px] text-slate-400 font-mono">{{ $order->customer_whatsapp }}</div>
                            </td>
                            <td class="py-3.5">
                                <div class="font-semibold text-slate-700">{{ $order->service_name_snapshot }}</div>
                                <div class="text-[11px] text-rose-600 font-bold">
                                    Rp {{ number_format($order->price_snapshot, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="py-3.5 text-slate-600">
                                <div class="font-medium">{{ $order->booking_schedule ? $order->booking_schedule->translatedFormat('d M Y, H:i') . ' WIB' : '-' }}</div>
                            </td>
                            <td class="py-3.5 text-slate-400 text-[11px]">
                                {{ $order->created_at ? $order->created_at->diffForHumans() : '-' }}
                            </td>
                            <td class="py-3.5 text-right">
                                @php
                                    $cleanPhone = preg_replace('/[^0-9]/', '', $order->customer_whatsapp);
                                    if (str_starts_with($cleanPhone, '0')) {
                                        $cleanPhone = '62' . substr($cleanPhone, 1);
                                    }
                                @endphp
                                <a href="https://wa.me/{{ $cleanPhone }}" target="_blank"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-bold transition active:scale-95">
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                                    </svg>
                                    <span>Chat WA</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Tampilan Mobile (Card View) -->
        <div class="block md:hidden space-y-3">
            @foreach ($orders as $order)
                @php
                    $cleanPhone = preg_replace('/[^0-9]/', '', $order->customer_whatsapp);
                    if (str_starts_with($cleanPhone, '0')) {
                        $cleanPhone = '62' . substr($cleanPhone, 1);
                    }
                @endphp
                <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs space-y-3">
                    <div class="flex items-center justify-between gap-2">
                        <a href="{{ route('orders.show', $order->order_code) }}" target="_blank"
                           class="font-mono font-bold text-xs text-rose-600 bg-rose-50 border border-rose-100 px-2 py-0.5 rounded-lg hover:underline inline-flex items-center gap-1">
                            <span>#{{ $order->order_code }}</span>
                            <svg class="w-3 h-3 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                        <span class="text-[10px] text-slate-400">
                            {{ $order->created_at ? $order->created_at->diffForHumans() : '-' }}
                        </span>
                    </div>

                    <div class="space-y-1">
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="text-xs font-bold text-slate-800">{{ $order->customer_name }}</span>
                            <span class="text-xs font-extrabold text-rose-600">Rp {{ number_format($order->price_snapshot, 0, ',', '.') }}</span>
                        </div>
                        <div class="text-[11px] text-slate-500 flex items-center justify-between gap-2">
                            <span>Paket: <strong class="text-slate-700 font-medium">{{ $order->service_name_snapshot }}</strong></span>
                            <span class="font-mono text-slate-400">{{ $order->customer_whatsapp }}</span>
                        </div>
                        <div class="text-[11px] text-slate-500 pt-0.5">
                            <span>Jadwal: </span>
                            <span class="font-semibold text-slate-700">{{ $order->booking_schedule ? $order->booking_schedule->translatedFormat('d M Y, H:i') . ' WIB' : '-' }}</span>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-slate-100 flex items-center gap-2">
                        <a href="{{ route('orders.show', $order->order_code) }}" target="_blank"
                           class="flex-1 text-center py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition active:scale-95">
                            Lihat Detail
                        </a>
                        <a href="https://wa.me/{{ $cleanPhone }}" target="_blank"
                           class="flex-1 text-center py-2 px-3 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-bold transition active:scale-95 flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                            </svg>
                            <span>Chat WA</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="p-10 rounded-2xl bg-slate-50 border border-dashed border-slate-200 text-center">
            <div class="w-12 h-12 rounded-2xl bg-white text-slate-400 flex items-center justify-center mx-auto mb-3 shadow-2xs border border-slate-100">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
            <p class="text-xs font-bold text-slate-700">Belum ada pesanan masuk.</p>
            <p class="text-[11px] text-slate-500 mt-1">Pesanan yang disubmit oleh pelanggan akan otomatis tercatat dan muncul di sini.</p>
        </div>
    @endif
</div>

