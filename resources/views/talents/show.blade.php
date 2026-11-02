<x-guest-layout title="{{ $talent->name }} - RentalPacar">
    <div class="w-full bg-white py-8 sm:py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Top Navigation Bar -->
            <div class="flex items-center gap-2 mb-6 pb-4 border-b border-slate-100">
                <a href="{{ route('talents.index') }}"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-full transition focus:outline-none focus:ring-2 focus:ring-rose-500"
                   aria-label="Kembali ke daftar talent">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span>Daftar Talent</span>
                </a>
                <span class="text-xs text-slate-400">/</span>
                <span class="text-xs font-medium text-slate-500">{{ $talent->name }}</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
                <!-- Kolom Kiri: Profil Talent (Desktop: 5 Kolom) -->
                <div class="md:col-span-5 space-y-4 md:sticky md:top-24">
                    <article class="bg-white rounded-3xl overflow-hidden shadow-xs border border-slate-200/80">
                        <div class="relative">
                            @if ($talent->avatar)
                                <img src="{{ asset('storage/' . $talent->avatar) }}" alt="{{ $talent->name }}" class="w-full h-80 object-cover object-center" loading="lazy">
                            @else
                                <div class="w-full h-80 bg-gradient-to-tr from-rose-100 to-rose-50 flex items-center justify-center text-rose-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 opacity-60" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                    </svg>
                                </div>
                            @endif

                            <div class="absolute top-4 right-4 bg-white/95 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-emerald-700 shadow-sm flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span>Tersedia</span>
                            </div>
                        </div>

                        <div class="p-6">
                            <h1 class="text-2xl font-extrabold text-slate-900">{{ $talent->name }}</h1>
                            <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
                                {{ $talent->bio ?? 'Siap menemani harimu dengan obrolan yang menyenangkan, mendengarkan ceritamu, atau bermain game bersama.' }}
                            </p>

                            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center gap-2 text-xs text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span>Terverifikasi resmi RentalPacar</span>
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Kolom Kanan: Form Pemesanan & Pilihan Tarif (Desktop: 7 Kolom) -->
                <div class="md:col-span-7">
                    <form action="{{ route('orders.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="talent_id" value="{{ $talent->id }}">

                        <!-- 1. Pilihan Layanan -->
                        <div class="bg-white rounded-3xl p-6 shadow-xs border border-slate-200/80">
                            <h2 class="text-sm font-bold text-slate-900 mb-3 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-rose-600 text-white flex items-center justify-center text-xs">1</span>
                                <span>Pilih Paket Layanan</span>
                            </h2>

                            @error('service_id')
                                <p class="text-xs text-rose-600 mb-3 font-medium">{{ $message }}</p>
                            @enderror

                            @if ($talent->services->isNotEmpty())
                                <div class="space-y-3">
                                    @foreach ($talent->services as $service)
                                        <label class="relative block cursor-pointer">
                                            <input type="radio" name="service_id" value="{{ $service->id }}" class="peer sr-only" {{ $loop->first ? 'checked' : '' }} required>
                                            <div class="p-4 rounded-2xl border-2 border-slate-200 bg-white hover:border-rose-200 peer-checked:border-rose-600 peer-checked:bg-rose-50/40 peer-checked:shadow-xs transition flex items-center justify-between gap-4">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2">
                                                        <h3 class="text-sm font-bold text-slate-800">{{ $service->service_name }}</h3>
                                                        <span class="text-[11px] bg-slate-100 text-slate-600 px-2.5 py-0.5 rounded-full font-semibold">
                                                            {{ $service->duration }}
                                                        </span>
                                                    </div>
                                                    @if ($service->description)
                                                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $service->description }}</p>
                                                    @endif
                                                </div>

                                                <div class="text-right">
                                                    <span class="text-sm font-extrabold text-rose-600">
                                                        Rp {{ number_format($service->price, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-6 rounded-2xl bg-slate-50 border border-dashed border-slate-200 text-center text-xs text-slate-500">
                                    Talent ini belum memiliki paket layanan aktif.
                                </div>
                            @endif
                        </div>

                        <!-- 2. Form Biodata Pemesan -->
                        <div class="bg-white rounded-3xl p-6 shadow-xs border border-slate-200/80 space-y-4">
                            <h2 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-rose-600 text-white flex items-center justify-center text-xs">2</span>
                                <span>Lengkapi Data Pemesanan</span>
                            </h2>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="customer_name" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                        Nama / Panggilan Kamu <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="customer_name" id="customer_name" required
                                           placeholder="Contoh: Alex atau Rian"
                                           value="{{ old('customer_name') }}"
                                           class="w-full text-xs px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition">
                                    @error('customer_name')
                                        <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="customer_whatsapp" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                        Nomor WhatsApp Kamu <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="tel" name="customer_whatsapp" id="customer_whatsapp" required
                                           placeholder="Contoh: 081234567890"
                                           value="{{ old('customer_whatsapp') }}"
                                           class="w-full text-xs px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition">
                                    @error('customer_whatsapp')
                                        <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="booking_schedule" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    Jadwal Tanggal & Jam Booking <span class="text-rose-500">*</span>
                                </label>
                                <input type="datetime-local" name="booking_schedule" id="booking_schedule" required
                                       value="{{ old('booking_schedule') }}"
                                       class="w-full text-xs px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition">
                                @error('booking_schedule')
                                    <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="notes" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    Catatan Tambahan (Opsional)
                                </label>
                                <textarea name="notes" id="notes" rows="3"
                                          placeholder="Topik obrolan yang disukai, game yang ingin dimainkan, atau hal penting lainnya."
                                          class="w-full text-xs px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <!-- Submit CTA -->
                        <div class="pt-2">
                            <button type="submit"
                                    class="w-full py-4 px-6 bg-rose-600 hover:bg-rose-700 active:scale-[0.99] text-white font-semibold text-sm rounded-2xl shadow-xl shadow-rose-600/30 flex items-center justify-center gap-3 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-300 fill-current" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                                </svg>
                                <span>Kirim Pesanan ke WhatsApp</span>
                            </button>
                            <p class="text-xs text-center text-slate-500 mt-2.5">
                                Kode pesanan unik akan otomatis dibuat dan diteruskan ke chat admin WhatsApp resmi kami.
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
