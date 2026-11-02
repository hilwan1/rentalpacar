<x-admin-layout title="Pengaturan WhatsApp - RentalPacar Admin" header="Pengaturan WhatsApp">
    <div class="space-y-6 max-w-4xl">
        <!-- Top Header & Deskripsi -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <a href="{{ route('admin.dashboard') }}" class="text-xs text-slate-400 hover:text-rose-600 transition">Dashboard</a>
                    <span class="text-xs text-slate-300">/</span>
                    <span class="text-xs font-bold text-rose-600">Pengaturan WhatsApp</span>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Pengaturan Nomor WhatsApp</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Kelola nomor WhatsApp admin untuk tombol tanya-jawab di halaman beranda dan tujuan penerimaan form pemesanan pelanggan.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>WhatsApp Gateway Aktif</span>
                </span>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 flex items-center gap-2.5 shadow-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-600 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-700 shadow-xs">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulir Pengaturan Nomor WhatsApp -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
            <form action="{{ route('admin.settings.whatsapp.update') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Petunjuk Format Nomor -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-start gap-3">
                    <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 text-xs font-bold">
                        i
                    </div>
                    <div class="text-xs text-slate-600 leading-relaxed">
                        <p class="font-bold text-slate-800 mb-0.5">Format Nomor WhatsApp yang Didukung</p>
                        <p>Kamu dapat memasukkan nomor dengan awalan <strong>08...</strong>, <strong>628...</strong>, atau <strong>+62...</strong>. Sistem akan otomatis membersihkan tanda baca dan mengonversinya ke format standar internasional WhatsApp (62) agar tautan obrolan bekerja tanpa kendala.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- 1. Nomor WA Beranda (CS / Tanya Jawab) -->
                    <div class="p-5 rounded-2xl border border-slate-200 bg-white space-y-3">
                        <div class="flex items-center justify-between">
                            <label for="whatsapp_home_number" class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                <span>No. WA Beranda (CS / Tanya Jawab)</span>
                            </label>
                        </div>
                        <p class="text-[11px] text-slate-500 leading-relaxed">
                            Nomor ini dihubungi pengunjung saat mengklik tombol <em>"Hubungi WA RentalPacar"</em> di halaman utama untuk konsultasi umum.
                        </p>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-xs text-slate-400 font-bold">
                                📞
                            </span>
                            <input type="text"
                                   name="whatsapp_home_number"
                                   id="whatsapp_home_number"
                                   required
                                   value="{{ old('whatsapp_home_number', $whatsappHome) }}"
                                   placeholder="Contoh: 081234567890"
                                   class="w-full pl-9 pr-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 font-mono">
                        </div>
                        @if ($whatsappHome)
                            <div class="pt-1 flex items-center justify-between text-[11px]">
                                <span class="text-slate-400">Aktif: <code class="text-slate-700 font-bold">{{ $whatsappHome }}</code></span>
                                <a href="https://wa.me/{{ $whatsappHome }}?text={{ rawurlencode('Tes pesan tanya-jawab dari admin RentalPacar') }}"
                                   target="_blank"
                                   class="text-emerald-600 hover:underline font-semibold flex items-center gap-1">
                                    <span>Tes Buka WA</span>
                                    <span>↗</span>
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- 2. Nomor WA Pemesanan (Penerima Form Booking) -->
                    <div class="p-5 rounded-2xl border border-slate-200 bg-white space-y-3">
                        <div class="flex items-center justify-between">
                            <label for="whatsapp_order_number" class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>No. WA Pemesanan (Form Booking)</span>
                            </label>
                        </div>
                        <p class="text-[11px] text-slate-500 leading-relaxed">
                            Nomor ini yang akan menerima rincian kode booking (`RP-XXXX`), nama pelanggan, dan jadwal kencan saat form pemesanan dikirim.
                        </p>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-xs text-slate-400 font-bold">
                                💬
                            </span>
                            <input type="text"
                                   name="whatsapp_order_number"
                                   id="whatsapp_order_number"
                                   required
                                   value="{{ old('whatsapp_order_number', $whatsappOrder) }}"
                                   placeholder="Contoh: 081234567890"
                                   class="w-full pl-9 pr-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 font-mono">
                        </div>
                        @if ($whatsappOrder)
                            <div class="pt-1 flex items-center justify-between text-[11px]">
                                <span class="text-slate-400">Aktif: <code class="text-slate-700 font-bold">{{ $whatsappOrder }}</code></span>
                                <a href="https://wa.me/{{ $whatsappOrder }}?text={{ rawurlencode('Tes pesan pemesanan dari admin RentalPacar') }}"
                                   target="_blank"
                                   class="text-emerald-600 hover:underline font-semibold flex items-center gap-1">
                                    <span>Tes Buka WA</span>
                                    <span>↗</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Checkbox Sinkronisasi Cepat -->
                <div class="p-3.5 rounded-2xl bg-rose-50/60 border border-rose-100 flex items-center gap-2.5">
                    <input type="checkbox"
                           id="sync-wa-checkbox"
                           class="w-4 h-4 rounded text-rose-600 border-slate-300 focus:ring-rose-500 cursor-pointer">
                    <label for="sync-wa-checkbox" class="text-xs font-semibold text-slate-800 cursor-pointer select-none">
                        Samakan Nomor Pemesanan dengan Nomor Beranda
                    </label>
                </div>

                <div class="pt-2 flex items-center justify-end gap-3 border-t border-slate-100">
                    <a href="{{ route('admin.dashboard') }}"
                       class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white text-xs font-bold rounded-xl shadow-sm shadow-rose-600/20 transition">
                        Simpan Perubahan Nomor WhatsApp
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Sinkronisasi Checkbox -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const syncCheckbox = document.getElementById('sync-wa-checkbox');
            const homeInput = document.getElementById('whatsapp_home_number');
            const orderInput = document.getElementById('whatsapp_order_number');

            if (syncCheckbox && homeInput && orderInput) {
                // Periksa apakah kedua nomor sudah sama saat awal load
                if (homeInput.value && homeInput.value === orderInput.value) {
                    syncCheckbox.checked = true;
                }

                syncCheckbox.addEventListener('change', () => {
                    if (syncCheckbox.checked) {
                        orderInput.value = homeInput.value;
                    }
                });

                homeInput.addEventListener('input', () => {
                    if (syncCheckbox.checked) {
                        orderInput.value = homeInput.value;
                    }
                });
            }
        });
    </script>
</x-admin-layout>

