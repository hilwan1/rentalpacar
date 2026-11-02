<x-guest-layout title="Dashboard Talent - RentalPacar">
    <div class="w-full bg-white py-8 sm:py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Top Dashboard Header / Talent Profile Summary -->
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
                        <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                            <span class="px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-700 text-[11px] font-bold">
                                Portal Talent
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $talent->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $talent->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                                <span>{{ $talent->is_active ? 'Tersedia' : 'Nonaktif' }}</span>
                            </span>
                            @if ($talent->city)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700">
                                    <svg class="w-3 h-3 text-rose-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ $talent->city }}</span>
                                </span>
                            @endif
                        </div>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Halo, {{ $talent->name }}!</h1>
                        <p class="text-xs text-slate-500 mt-0.5">Kelola foto profil, tarif layanan, dan pantau pesanan masuk.</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
                    <a href="{{ route('talents.show', $talent->id) }}" target="_blank"
                       class="px-3.5 sm:px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold rounded-xl border border-rose-200 transition flex items-center gap-1.5 active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <span>Lihat Profil Publik</span>
                    </a>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3.5 sm:px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl border border-slate-200 transition active:scale-95">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>

            @if (session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 flex items-center gap-2 shadow-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-600 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Ringkasan Statistik Talent -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-xs">
                    <span class="text-xs font-semibold text-slate-500 block">Status Ketersediaan</span>
                    <span class="text-lg font-extrabold {{ $talent->is_active ? 'text-emerald-600' : 'text-slate-500' }} mt-1 block">
                        {{ $talent->is_active ? 'Tersedia untuk Dipesan' : 'Sedang Istirahat' }}
                    </span>
                    <span class="text-[11px] text-slate-400 mt-1 block">Bisa diubah sewaktu-waktu di tab profil</span>
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
                        {{ $totalOrdersCount ?? $orders->count() }} Pesanan
                    </span>
                    <span class="text-[11px] text-slate-400 mt-1 block">Diteruskan ke WhatsApp pelanggan</span>
                </div>
            </div>

            <!-- Tab Navigation Bar (Grid 2x2 di Mobile, Flex 1 Baris di Layar Lebar) -->
            <div class="bg-slate-100/90 p-1.5 rounded-2xl border border-slate-200/80 grid grid-cols-2 sm:flex sm:items-center gap-1.5" role="tablist">
                <!-- Tab 1: Paket Layanan -->
                <button type="button"
                        id="tab-btn-services"
                        data-tab-target="services"
                        role="tab"
                        aria-selected="true"
                        aria-controls="tab-pane-services"
                        class="talent-tab-btn w-full sm:flex-1 min-h-[44px] flex items-center justify-center gap-1.5 sm:gap-2 px-2.5 sm:px-4 py-2 sm:py-2.5 rounded-xl text-xs transition-all duration-150 cursor-pointer active:scale-95 bg-white text-slate-900 font-extrabold shadow-2xs border border-slate-200/80">
                    <svg class="w-4 h-4 text-rose-600 tab-icon shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span class="truncate">Paket Layanan</span>
                    <span class="tab-badge px-1.5 sm:px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 shrink-0">
                        {{ $services->count() }}
                    </span>
                </button>

                <!-- Tab 2: Pesanan Masuk -->
                <button type="button"
                        id="tab-btn-orders"
                        data-tab-target="orders"
                        role="tab"
                        aria-selected="false"
                        aria-controls="tab-pane-orders"
                        class="talent-tab-btn w-full sm:flex-1 min-h-[44px] flex items-center justify-center gap-1.5 sm:gap-2 px-2.5 sm:px-4 py-2 sm:py-2.5 rounded-xl text-xs transition-all duration-150 cursor-pointer active:scale-95 text-slate-600 hover:text-slate-900 hover:bg-white/60 font-semibold border border-transparent">
                    <svg class="w-4 h-4 text-slate-400 tab-icon shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <span class="truncate">Pesanan Masuk</span>
                    <span class="tab-badge px-1.5 sm:px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-200/80 text-slate-600 shrink-0">
                        {{ $totalOrdersCount ?? $orders->count() }}
                    </span>
                </button>

                <!-- Tab 3: Profil Saya -->
                <button type="button"
                        id="tab-btn-profile"
                        data-tab-target="profile"
                        role="tab"
                        aria-selected="false"
                        aria-controls="tab-pane-profile"
                        class="talent-tab-btn w-full sm:flex-1 min-h-[44px] flex items-center justify-center gap-1.5 sm:gap-2 px-2.5 sm:px-4 py-2 sm:py-2.5 rounded-xl text-xs transition-all duration-150 cursor-pointer active:scale-95 text-slate-600 hover:text-slate-900 hover:bg-white/60 font-semibold border border-transparent">
                    <svg class="w-4 h-4 text-slate-400 tab-icon shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="truncate">Profil Saya</span>
                </button>

                <!-- Tab 4: Keamanan & Password -->
                <button type="button"
                        id="tab-btn-security"
                        data-tab-target="security"
                        role="tab"
                        aria-selected="false"
                        aria-controls="tab-pane-security"
                        class="talent-tab-btn w-full sm:flex-1 min-h-[44px] flex items-center justify-center gap-1.5 sm:gap-2 px-2.5 sm:px-4 py-2 sm:py-2.5 rounded-xl text-xs transition-all duration-150 cursor-pointer active:scale-95 text-slate-600 hover:text-slate-900 hover:bg-white/60 font-semibold border border-transparent">
                    <svg class="w-4 h-4 text-slate-400 tab-icon shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <span class="truncate">Keamanan</span>
                </button>
            </div>

            <!-- Tab Content Panels -->
            <div class="tab-panels-wrapper">
                <!-- Pane 1: Paket Layanan -->
                <div id="tab-pane-services" role="tabpanel" aria-labelledby="tab-btn-services" class="talent-tab-pane">
                    <x-talent.services-tab :talent="$talent" :services="$services" />
                </div>

                <!-- Pane 2: Pesanan Masuk -->
                <div id="tab-pane-orders" role="tabpanel" aria-labelledby="tab-btn-orders" class="talent-tab-pane hidden">
                    <x-talent.orders-tab :talent="$talent" :orders="$orders" :total-orders-count="$totalOrdersCount ?? $orders->count()" />
                </div>

                <!-- Pane 3: Profil Saya -->
                <div id="tab-pane-profile" role="tabpanel" aria-labelledby="tab-btn-profile" class="talent-tab-pane hidden">
                    <x-talent.profile-tab :talent="$talent" />
                </div>

                <!-- Pane 4: Keamanan & Password -->
                <div id="tab-pane-security" role="tabpanel" aria-labelledby="tab-btn-security" class="talent-tab-pane hidden">
                    <x-talent.security-tab :talent="$talent" />
                </div>
            </div>

        </div>
    </div>

    <!-- Script Tab Switching & Avatar Preview -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tabButtons = document.querySelectorAll('.talent-tab-btn');
            const tabPanes = document.querySelectorAll('.talent-tab-pane');

            function activateTab(targetTabId) {
                const targetBtn = document.querySelector(`[data-tab-target="${targetTabId}"]`);
                const targetPane = document.getElementById(`tab-pane-${targetTabId}`);

                if (!targetBtn || !targetPane) return;

                // Deactivate all buttons
                tabButtons.forEach(btn => {
                    btn.classList.remove('bg-white', 'text-slate-900', 'font-extrabold', 'shadow-2xs', 'border-slate-200/80');
                    btn.classList.add('text-slate-600', 'font-semibold', 'border-transparent');
                    btn.setAttribute('aria-selected', 'false');

                    const icon = btn.querySelector('.tab-icon');
                    if (icon) {
                        icon.classList.remove('text-rose-600');
                        icon.classList.add('text-slate-400');
                    }

                    const badge = btn.querySelector('.tab-badge');
                    if (badge) {
                        badge.classList.remove('bg-rose-50', 'text-rose-700');
                        badge.classList.add('bg-slate-200/80', 'text-slate-600');
                    }
                });

                // Deactivate all panes
                tabPanes.forEach(pane => {
                    pane.classList.add('hidden');
                });

                // Activate selected button
                targetBtn.classList.remove('text-slate-600', 'font-semibold', 'border-transparent');
                targetBtn.classList.add('bg-white', 'text-slate-900', 'font-extrabold', 'shadow-2xs', 'border-slate-200/80');
                targetBtn.setAttribute('aria-selected', 'true');

                const activeIcon = targetBtn.querySelector('.tab-icon');
                if (activeIcon) {
                    activeIcon.classList.remove('text-slate-400');
                    activeIcon.classList.add('text-rose-600');
                }

                const activeBadge = targetBtn.querySelector('.tab-badge');
                if (activeBadge) {
                    activeBadge.classList.remove('bg-slate-200/80', 'text-slate-600');
                    activeBadge.classList.add('bg-rose-50', 'text-rose-700');
                }

                // Show selected pane
                targetPane.classList.remove('hidden');

                // Update hash without jumping
                if (history.replaceState) {
                    history.replaceState(null, null, `#${targetTabId}`);
                }
            }

            // Click listener
            tabButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const target = btn.getAttribute('data-tab-target');
                    if (target) activateTab(target);
                });
            });

            // Initial Tab Selection based on Server Errors or URL
            @if ($errors->has('current_password') || $errors->has('password'))
                activateTab('security');
            @elseif ($errors->has('name') || $errors->has('phone') || $errors->has('city') || $errors->has('bio') || $errors->has('avatar'))
                activateTab('profile');
            @elseif ($errors->has('service_name') || $errors->has('duration') || $errors->has('price') || $errors->has('description'))
                activateTab('services');
            @else
                const urlParams = new URLSearchParams(window.location.search);
                const queryTab = urlParams.get('tab');
                const hashTab = window.location.hash.replace('#', '');
                const validTabs = ['services', 'orders', 'profile', 'security'];

                if (queryTab && validTabs.includes(queryTab)) {
                    activateTab(queryTab);
                } else if (hashTab && validTabs.includes(hashTab)) {
                    activateTab(hashTab);
                } else {
                    activateTab('services');
                }
            @endif

            // Photo Preview Handler (Profile Tab)
            const avatarInput = document.getElementById('avatar-input');
            const avatarPreview = document.getElementById('avatar-preview');
            if (avatarInput && avatarPreview) {
                avatarInput.addEventListener('change', (e) => {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (event) => {
                            avatarPreview.src = event.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
</x-guest-layout>
