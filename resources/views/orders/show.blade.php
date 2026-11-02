<x-guest-layout title="Detail Pesanan {{ $order->order_code }} - RentalPacar">
    <div class="w-full bg-white py-10 sm:py-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Breadcrumb Navigasi -->
            <nav class="flex items-center gap-2 text-xs text-slate-500 mb-2" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-rose-600 transition">Beranda</a>
                <span class="text-slate-300">/</span>
                <a href="{{ route('talents.index') }}" class="hover:text-rose-600 transition">Katalog Talent</a>
                <span class="text-slate-300">/</span>
                <a href="{{ route('talents.show', $order->talent_id) }}" class="hover:text-rose-600 transition">{{ $order->talent->name }}</a>
                <span class="text-slate-300">/</span>
                <span class="font-bold text-slate-800">Detail Pesanan</span>
            </nav>

            @if (session('just_created'))
                <!-- Flash Notification Sukses Pembuatan Pesanan -->
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs sm:text-sm text-emerald-800 flex items-start gap-3 shadow-xs animate-in fade-in">
                    <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0 text-xs font-bold mt-0.5">
                        ✓
                    </div>
                    <div>
                        <p class="font-bold text-emerald-900">Pesanan Berhasil Dicatat di Sistem!</p>
                        <p class="text-xs text-emerald-700 mt-0.5">
                            Satu langkah lagi: silakan konfirmasi rincian pesanan berikut ke Admin melalui tombol WhatsApp di bawah ini agar jadwal segera diamankan.
                        </p>
                    </div>
                </div>
            @endif

            <!-- Hero Card: Kode Pesanan & Status -->
            <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-rose-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
                <div class="absolute -right-12 -bottom-12 w-48 h-48 rounded-full bg-rose-500/15 blur-2xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-white/10">
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-400/20 text-amber-300 border border-amber-400/30 text-xs font-bold mb-3">
                            <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                            <span>Menunggu Konfirmasi WhatsApp</span>
                        </span>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-white tracking-tight">Rincian Tiket Pemesanan</h1>
                        <p class="text-xs text-slate-300 mt-1">
                            Simpan kode pesanan ini sebagai bukti reservasi kencan dan teman cerita.
                        </p>
                    </div>

                    <div class="text-left sm:text-right">
                        <span class="text-[11px] text-slate-400 block">Waktu Dibuat:</span>
                        <span class="text-xs font-semibold text-slate-200">{{ $order->created_at ? $order->created_at->translatedFormat('d M Y, H:i') . ' WIB' : 'Baru saja' }}</span>
                    </div>
                </div>

                <!-- Box Kode Pesanan Besar -->
                <div class="relative z-10 mt-6 pt-2 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 bg-white/10 border border-white/15 rounded-2xl p-4 sm:p-5 backdrop-blur-sm">
                    <div>
                        <span class="text-[11px] font-bold text-rose-300 uppercase tracking-wider block">
                            Kode Pesanan Resmi
                        </span>
                        <span id="order-code-text" class="text-2xl sm:text-3xl font-mono font-extrabold text-white tracking-widest mt-1 block">
                            {{ $order->order_code }}
                        </span>
                    </div>

                    <button type="button"
                            id="copy-code-btn"
                            class="px-4 py-2.5 rounded-xl bg-white/20 hover:bg-white/30 text-white font-bold text-xs flex items-center justify-center gap-2 border border-white/20 transition active:scale-95 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <span id="copy-code-label">Salin Kode</span>
                    </button>
                </div>
            </div>

            <!-- Detail Spesifikasi Pesanan -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                    <div>
                        <h2 class="text-base sm:text-lg font-extrabold text-slate-900">Informasi Lengkap Pemesanan</h2>
                        <p class="text-xs text-slate-500">Periksa kembali data diri dan jadwal sebelum mengirim ke WhatsApp.</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-semibold">
                        ID: #{{ $order->id }}
                    </span>
                </div>

                <!-- Profil Talent Terpilih -->
                <div class="flex items-center gap-4 p-4 rounded-2xl bg-rose-50/50 border border-rose-100">
                    <div class="w-14 h-14 rounded-2xl overflow-hidden bg-rose-100 border border-rose-200 shrink-0">
                        @if ($order->talent->avatar_url)
                            <img src="{{ $order->talent->avatar_url }}" alt="{{ $order->talent->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-rose-600 font-extrabold text-xl">
                                {{ strtoupper(substr($order->talent->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-rose-600 block">Talent Pendamping</span>
                        <h3 class="text-base font-extrabold text-slate-900 truncate">{{ $order->talent->name }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $order->talent->city ?? 'Indonesia' }}</span>
                        </p>
                    </div>
                    <a href="{{ route('talents.show', $order->talent_id) }}" target="_blank"
                       class="hidden sm:inline-flex items-center gap-1 text-xs font-bold text-rose-600 hover:text-rose-700 hover:underline">
                        <span>Lihat Profil</span>
                        <span>↗</span>
                    </a>
                </div>

                <!-- Grid Data Pemesanan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <!-- Paket Layanan -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                        <span class="text-slate-400 text-[11px] block mb-1">Paket Jasa Dipilih:</span>
                        <p class="font-extrabold text-slate-900 text-sm">{{ $order->service_name_snapshot }}</p>
                        @if ($duration)
                            <p class="text-slate-500 text-[11px] mt-0.5">Durasi: {{ $duration }}</p>
                        @endif
                    </div>

                    <!-- Total Tarif -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                        <span class="text-slate-400 text-[11px] block mb-1">Total Biaya / Tarif:</span>
                        <p class="font-extrabold text-rose-600 text-base sm:text-lg">{{ $priceFormatted }}</p>
                        <p class="text-slate-500 text-[11px] mt-0.5">Tarif resmi sesuai paket layanan</p>
                    </div>

                    <!-- Jadwal Booking -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                        <span class="text-slate-400 text-[11px] block mb-1">Jadwal Tanggal & Waktu:</span>
                        <p class="font-extrabold text-slate-900 text-xs sm:text-sm">{{ $scheduleFormatted }} WIB</p>
                        <p class="text-slate-500 text-[11px] mt-0.5">Pastikan kamu siap pada jam tersebut</p>
                    </div>

                    <!-- Data Pemesan -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                        <span class="text-slate-400 text-[11px] block mb-1">Data Pelanggan:</span>
                        <p class="font-extrabold text-slate-900">{{ $order->customer_name }}</p>
                        <p class="text-slate-600 font-mono text-[11px] mt-0.5">WhatsApp: {{ $order->customer_whatsapp }}</p>
                    </div>
                </div>

                <!-- Catatan Tambahan -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs">
                    <span class="text-slate-400 text-[11px] block mb-1">Catatan Tambahan untuk Talent:</span>
                    @if ($order->notes)
                        <p class="text-slate-800 leading-relaxed italic bg-white p-3 rounded-xl border border-slate-200">
                            "{{ $order->notes }}"
                        </p>
                    @else
                        <p class="text-slate-400 italic">Tidak ada catatan tambahan yang disertakan.</p>
                    @endif
                </div>

                <!-- Action CTA: Konfirmasi ke WhatsApp -->
                <div class="pt-4 border-t border-slate-100 space-y-3">
                    <a href="{{ $waUrl }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="w-full py-4 px-6 bg-emerald-600 hover:bg-emerald-700 active:scale-[0.99] text-white font-extrabold text-sm sm:text-base rounded-2xl shadow-lg shadow-emerald-600/25 flex items-center justify-center gap-3 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 fill-current text-white shrink-0" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                        </svg>
                        <span>Konfirmasi & Kirim Data ke WhatsApp Admin</span>
                    </a>

                    <p class="text-xs text-center text-slate-500 leading-relaxed">
                        Saat tombol di atas diklik, aplikasi WhatsApp Anda akan terbuka otomatis dengan format pesan konfirmasi pesanan lengkap ke Admin resmi RentalPacar.
                    </p>
                </div>

                <!-- Tombol Pendukung: Salin Rincian & Navigasi -->
                <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                    <button type="button"
                            id="copy-summary-btn"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold transition active:scale-95 cursor-pointer">
                        <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span id="copy-summary-label">Salin Teks Pesanan</span>
                    </button>

                    <div class="flex items-center gap-3 w-full sm:w-auto justify-center sm:justify-end">
                        <a href="{{ route('talents.index') }}"
                           class="text-xs font-semibold text-rose-600 hover:text-rose-700 hover:underline">
                            ← Pilih Talent Lain
                        </a>
                        <span class="text-slate-300">•</span>
                        <a href="{{ route('home') }}"
                           class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
                            Halaman Utama
                        </a>
                    </div>
                </div>

            </div>

            <!-- Petunjuk Langkah Setelah Pemesanan -->
            <div class="p-5 rounded-3xl bg-slate-50 border border-slate-200/80 text-xs text-slate-600 space-y-3">
                <p class="font-extrabold text-slate-900 text-sm">Langkah Selanjutnya:</p>
                <ol class="list-decimal pl-5 space-y-1.5 leading-relaxed text-slate-600">
                    <li>Klik tombol hijau <strong>"Konfirmasi & Kirim Data ke WhatsApp Admin"</strong> di atas.</li>
                    <li>Kirim pesan WhatsApp yang sudah terisi otomatis ke admin kami tanpa mengubah kode pesanan.</li>
                    <li>Admin kami akan memverifikasi ketersediaan talent dan mengirimkan rekening pembayaran resmi.</li>
                    <li>Setelah pembayaran dikonfirmasi, Anda akan dihubungkan dengan talent sesuai jadwal yang dipilih.</li>
                </ol>
            </div>

        </div>
    </div>

    <!-- Hidden Raw Message Text for Copying -->
    <textarea id="raw-wa-message" class="hidden">{{ $message }}</textarea>

    <!-- JavaScript Copy to Clipboard -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Copy Order Code
            const copyCodeBtn = document.getElementById('copy-code-btn');
            const copyCodeLabel = document.getElementById('copy-code-label');
            const orderCode = "{{ $order->order_code }}";

            copyCodeBtn?.addEventListener('click', async () => {
                try {
                    await navigator.clipboard.writeText(orderCode);
                    copyCodeLabel.textContent = 'Kode Tersalin!';
                    copyCodeBtn.classList.add('bg-emerald-500/30', 'text-emerald-200');
                    setTimeout(() => {
                        copyCodeLabel.textContent = 'Salin Kode';
                        copyCodeBtn.classList.remove('bg-emerald-500/30', 'text-emerald-200');
                    }, 2500);
                } catch (e) {
                    // Fallback
                    const temp = document.createElement('textarea');
                    temp.value = orderCode;
                    document.body.appendChild(temp);
                    temp.select();
                    document.execCommand('copy');
                    document.body.removeChild(temp);
                    copyCodeLabel.textContent = 'Kode Tersalin!';
                    setTimeout(() => { copyCodeLabel.textContent = 'Salin Kode'; }, 2500);
                }
            });

            // Copy Summary Text
            const copySummaryBtn = document.getElementById('copy-summary-btn');
            const copySummaryLabel = document.getElementById('copy-summary-label');
            const rawMessage = document.getElementById('raw-wa-message')?.value || '';

            copySummaryBtn?.addEventListener('click', async () => {
                try {
                    await navigator.clipboard.writeText(rawMessage);
                    copySummaryLabel.textContent = 'Teks Tersalin!';
                    copySummaryBtn.classList.add('border-emerald-500', 'text-emerald-700', 'bg-emerald-50');
                    setTimeout(() => {
                        copySummaryLabel.textContent = 'Salin Teks Pesanan';
                        copySummaryBtn.classList.remove('border-emerald-500', 'text-emerald-700', 'bg-emerald-50');
                    }, 2500);
                } catch (e) {
                    const temp = document.createElement('textarea');
                    temp.value = rawMessage;
                    document.body.appendChild(temp);
                    temp.select();
                    document.execCommand('copy');
                    document.body.removeChild(temp);
                    copySummaryLabel.textContent = 'Teks Tersalin!';
                    setTimeout(() => { copySummaryLabel.textContent = 'Salin Teks Pesanan'; }, 2500);
                }
            });
        });
    </script>
</x-guest-layout>

