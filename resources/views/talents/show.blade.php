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
                            @if ($talent->avatar_url)
                                <img src="{{ $talent->avatar_url }}" alt="{{ $talent->name }}" class="w-full h-80 object-cover object-center" loading="lazy">
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
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <h1 class="text-2xl font-extrabold text-slate-900">{{ $talent->name }}</h1>
                                @if ($talent->city)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                        <svg class="w-3.5 h-3.5 text-rose-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>{{ $talent->city }}</span>
                                    </span>
                                @endif
                            </div>
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

                        <!-- 1. Pilihan Layanan (Kategori Basic & Premium dengan Tab Switcher) -->
                        <div class="bg-white rounded-3xl p-6 shadow-xs border border-slate-200/80 space-y-5">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-rose-600 text-white flex items-center justify-center text-xs">1</span>
                                    <span>Pilih Paket Layanan</span>
                                </h2>
                                <span class="text-[11px] text-slate-500 font-medium">Pilih salah satu paket</span>
                            </div>

                            @error('service_id')
                                <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                            @enderror

                            @php
                                $activeServices = $talent->services->where('is_active', true);
                                $basicServices = $activeServices->filter(fn($s) => $s->isBasic());
                                $premiumServices = $activeServices->filter(fn($s) => $s->isPremium());
                                $selectedServiceId = (int) old('service_id', $activeServices->first()?->id);
                                $hasBothCategories = $basicServices->isNotEmpty() && $premiumServices->isNotEmpty();
                                
                                // Tentukan tab aktif awal berdasarkan service yang terpilih
                                $initialCategory = 'basic';
                                if ($premiumServices->contains('id', $selectedServiceId) || ($basicServices->isEmpty() && $premiumServices->isNotEmpty())) {
                                    $initialCategory = 'premium';
                                }
                            @endphp

                            @if ($activeServices->isNotEmpty())
                                @if ($hasBothCategories)
                                    <!-- Tab Pill Switcher Antara Paket Basic & Paket Premium -->
                                    <div class="grid grid-cols-2 p-1.5 bg-slate-100/90 rounded-2xl border border-slate-200/80 gap-1.5" role="tablist">
                                        <button type="button"
                                                id="tab-btn-service-basic"
                                                data-service-tab="basic"
                                                role="tab"
                                                aria-selected="{{ $initialCategory === 'basic' ? 'true' : 'false' }}"
                                                class="service-tab-btn flex items-center justify-center gap-1.5 sm:gap-2 py-2.5 px-3 rounded-xl text-xs transition-all duration-150 cursor-pointer active:scale-95 {{ $initialCategory === 'basic' ? 'bg-white text-slate-900 shadow-2xs border border-slate-200/80 font-extrabold' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60 font-semibold border border-transparent' }}">
                                            <span class="w-2 h-2 rounded-full bg-rose-600 tab-selected-dot shrink-0 {{ $basicServices->contains('id', $selectedServiceId) ? 'inline-block' : 'hidden' }}"></span>
                                            <span>Paket Basic</span>
                                            <span class="text-[10px] px-2 py-0.5 rounded-full font-bold bg-slate-100 text-slate-700 shrink-0">
                                                {{ $basicServices->count() }}
                                            </span>
                                        </button>

                                        <button type="button"
                                                id="tab-btn-service-premium"
                                                data-service-tab="premium"
                                                role="tab"
                                                aria-selected="{{ $initialCategory === 'premium' ? 'true' : 'false' }}"
                                                class="service-tab-btn flex items-center justify-center gap-1.5 sm:gap-2 py-2.5 px-3 rounded-xl text-xs transition-all duration-150 cursor-pointer active:scale-95 {{ $initialCategory === 'premium' ? 'bg-white text-slate-900 shadow-2xs border border-amber-300 font-extrabold' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60 font-semibold border border-transparent' }}">
                                            <span class="w-2 h-2 rounded-full bg-amber-500 tab-selected-dot shrink-0 {{ $premiumServices->contains('id', $selectedServiceId) ? 'inline-block' : 'hidden' }}"></span>
                                            <span class="flex items-center gap-1">
                                                <span class="text-amber-500">⭐</span>
                                                <span>Paket Premium</span>
                                            </span>
                                            <span class="text-[10px] px-2 py-0.5 rounded-full font-bold bg-amber-100 text-amber-800 shrink-0">
                                                {{ $premiumServices->count() }}
                                            </span>
                                        </button>
                                    </div>
                                @endif

                                <!-- Panel Paket Basic -->
                                @if ($basicServices->isNotEmpty())
                                    <div id="service-panel-basic" class="service-panel space-y-3 {{ $initialCategory === 'basic' ? 'block' : 'hidden' }}">
                                        <div class="flex items-center justify-between text-xs text-slate-500">
                                            <span>Layanan obrolan, teman cerita santai, dan voice call</span>
                                            <span class="text-[11px] text-slate-400 font-medium">{{ $basicServices->count() }} paket tersedia</span>
                                        </div>

                                        <div class="space-y-2.5">
                                            @foreach ($basicServices as $service)
                                                <label class="relative block cursor-pointer group">
                                                    <input type="radio"
                                                           name="service_id"
                                                           value="{{ $service->id }}"
                                                           data-category="basic"
                                                           class="peer sr-only service-radio-item"
                                                           {{ $selectedServiceId === $service->id ? 'checked' : '' }}
                                                           required>
                                                    <div class="p-4 rounded-2xl border-2 border-slate-200 bg-white hover:border-slate-300 peer-checked:border-rose-600 peer-checked:bg-rose-50/40 peer-checked:ring-2 peer-checked:ring-rose-500/20 peer-checked:shadow-xs transition-all flex items-center justify-between gap-3 sm:gap-4">
                                                        <div class="flex items-center gap-3 flex-1 min-w-0">
                                                            <!-- Indikator Radio Lingkaran -->
                                                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 group-has-[:checked]:border-rose-600 group-has-[:checked]:bg-rose-600 flex items-center justify-center shrink-0 transition-colors">
                                                                <span class="w-2 h-2 rounded-full bg-white hidden group-has-[:checked]:block"></span>
                                                            </div>

                                                            <div class="flex-1 min-w-0">
                                                                <div class="flex items-center gap-2 flex-wrap">
                                                                    <h3 class="text-sm font-bold text-slate-800 group-has-[:checked]:text-rose-950">{{ $service->service_name }}</h3>
                                                                    <span class="text-[10px] bg-slate-100 text-slate-600 px-2.5 py-0.5 rounded-full font-semibold">
                                                                        ⏱ {{ $service->duration }}
                                                                    </span>
                                                                </div>
                                                                @if ($service->description)
                                                                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $service->description }}</p>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="text-right shrink-0">
                                                            <span class="text-sm font-extrabold text-slate-900 block group-has-[:checked]:text-rose-600">
                                                                Rp {{ number_format($service->price, 0, ',', '.') }}
                                                            </span>
                                                            <span class="hidden group-has-[:checked]:inline-flex items-center gap-1 text-[10px] font-bold text-rose-700 bg-rose-100 px-2 py-0.5 rounded-full mt-1">
                                                                ✓ Terpilih
                                                            </span>
                                                        </div>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Panel Paket Premium -->
                                @if ($premiumServices->isNotEmpty())
                                    <div id="service-panel-premium" class="service-panel space-y-3 {{ $initialCategory === 'premium' ? 'block' : 'hidden' }}">
                                        <div class="flex items-center justify-between text-xs text-amber-700">
                                            <span>Layanan temu langsung VIP & kencan spesial lebih eksklusif</span>
                                            <span class="text-[11px] text-amber-600/80 font-medium">{{ $premiumServices->count() }} paket tersedia</span>
                                        </div>

                                        <div class="space-y-2.5">
                                            @foreach ($premiumServices as $service)
                                                <label class="relative block cursor-pointer group">
                                                    <input type="radio"
                                                           name="service_id"
                                                           value="{{ $service->id }}"
                                                           data-category="premium"
                                                           class="peer sr-only service-radio-item"
                                                           {{ $selectedServiceId === $service->id ? 'checked' : '' }}
                                                           required>
                                                    <div class="p-4 rounded-2xl border-2 border-amber-200/90 bg-white hover:border-amber-300 peer-checked:border-amber-500 peer-checked:bg-amber-50/40 peer-checked:ring-2 peer-checked:ring-amber-400/30 peer-checked:shadow-xs transition-all flex items-center justify-between gap-3 sm:gap-4">
                                                        <div class="flex items-center gap-3 flex-1 min-w-0">
                                                            <!-- Indikator Radio Lingkaran -->
                                                            <div class="w-5 h-5 rounded-full border-2 border-amber-300 group-has-[:checked]:border-amber-500 group-has-[:checked]:bg-amber-500 flex items-center justify-center shrink-0 transition-colors">
                                                                <span class="w-2 h-2 rounded-full bg-white hidden group-has-[:checked]:block"></span>
                                                            </div>

                                                            <div class="flex-1 min-w-0">
                                                                <div class="flex items-center gap-2 flex-wrap">
                                                                    <h3 class="text-sm font-bold text-slate-900 group-has-[:checked]:text-amber-950">{{ $service->service_name }}</h3>
                                                                    <span class="text-[10px] bg-amber-100/60 text-amber-800 px-2 py-0.5 rounded-full font-semibold">
                                                                        ⏱ {{ $service->duration }}
                                                                    </span>
                                                                </div>
                                                                @if ($service->description)
                                                                    <p class="text-xs text-slate-600 mt-1 leading-relaxed">{{ $service->description }}</p>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="text-right shrink-0">
                                                            <span class="text-sm sm:text-base font-extrabold text-slate-900 block group-has-[:checked]:text-amber-700">
                                                                Rp {{ number_format($service->price, 0, ',', '.') }}
                                                            </span>
                                                            <span class="hidden group-has-[:checked]:inline-flex items-center gap-1 text-[10px] font-bold text-amber-800 bg-amber-100 border border-amber-200 px-2 py-0.5 rounded-full mt-1">
                                                                ✓ Terpilih
                                                            </span>
                                                        </div>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
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
                                    class="w-full py-4 px-6 bg-rose-600 hover:bg-rose-700 active:scale-[0.99] text-white font-bold text-sm rounded-2xl shadow-xl shadow-rose-600/30 flex items-center justify-center gap-2.5 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <span>Pesan</span>
                            </button>
                            <p class="text-xs text-center text-slate-500 mt-2.5">
                                Kode pesanan akan dibuat otomatis dan kamu dapat memeriksa rincian tiket sebelum konfirmasi ke WhatsApp.
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabBtns = document.querySelectorAll('.service-tab-btn');
            const panels = document.querySelectorAll('.service-panel');
            const radioInputs = document.querySelectorAll('.service-radio-item');

            function updateTabSelectedDots() {
                const checkedRadio = document.querySelector('.service-radio-item:checked');
                if (!checkedRadio) return;

                const category = checkedRadio.getAttribute('data-category');
                tabBtns.forEach(btn => {
                    const btnCategory = btn.getAttribute('data-service-tab');
                    const dot = btn.querySelector('.tab-selected-dot');
                    if (dot) {
                        if (btnCategory === category) {
                            dot.classList.remove('hidden');
                            dot.classList.add('inline-block');
                        } else {
                            dot.classList.remove('inline-block');
                            dot.classList.add('hidden');
                        }
                    }
                });
            }

            tabBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const targetTab = this.getAttribute('data-service-tab');

                    tabBtns.forEach(b => {
                        const isTarget = b === btn;
                        b.setAttribute('aria-selected', isTarget ? 'true' : 'false');
                        if (isTarget) {
                            b.classList.add('bg-white', 'text-slate-900', 'shadow-2xs', 'font-extrabold');
                            b.classList.remove('text-slate-600', 'hover:text-slate-900', 'hover:bg-white/60', 'font-semibold', 'border-transparent');
                            if (targetTab === 'premium') {
                                b.classList.add('border-amber-300');
                                b.classList.remove('border-slate-200/80');
                            } else {
                                b.classList.add('border-slate-200/80');
                                b.classList.remove('border-amber-300');
                            }
                        } else {
                            b.classList.remove('bg-white', 'text-slate-900', 'shadow-2xs', 'border-slate-200/80', 'border-amber-300', 'font-extrabold');
                            b.classList.add('text-slate-600', 'hover:text-slate-900', 'hover:bg-white/60', 'font-semibold', 'border-transparent');
                        }
                    });

                    panels.forEach(panel => {
                        if (panel.id === `service-panel-${targetTab}`) {
                            panel.classList.remove('hidden');
                            panel.classList.add('block');
                        } else {
                            panel.classList.remove('block');
                            panel.classList.add('hidden');
                        }
                    });
                });
            });

            radioInputs.forEach(input => {
                input.addEventListener('change', updateTabSelectedDots);
            });

            updateTabSelectedDots();
        });
    </script>
    @endpush
</x-guest-layout>
