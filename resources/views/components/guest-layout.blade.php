<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'RentalPacar - Teman Cerita & Kencan Online' }}</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white text-slate-800 font-sans antialiased flex flex-col selection:bg-rose-500 selection:text-white">
    <!-- Navbar Responsif (Desktop & Seluruh Layar Mobile) -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-rose-100/80 shadow-xs">
        <div class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-2">
            <!-- Brand Logo & Name -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 group focus:outline-none focus:ring-2 focus:ring-rose-500 rounded-lg p-1 shrink-0">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-gradient-to-tr from-rose-600 to-rose-500 flex items-center justify-center text-white shadow-md shadow-rose-600/20 group-hover:scale-105 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 sm:w-5 sm:h-5 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                </div>
                <div class="flex items-center gap-1">
                    <span class="text-base sm:text-lg font-extrabold tracking-tight text-slate-900 group-hover:text-rose-600 transition-colors">RentalPacar</span>
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-rose-600 fill-current shrink-0" viewBox="0 0 24 24" aria-label="Terverifikasi" role="img">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
            </a>

            <!-- Desktop Navigation Links (md:flex) -->
            <nav class="hidden md:flex items-center gap-5" aria-label="Navigasi Desktop">
                <a href="{{ route('home') }}" class="text-sm font-semibold {{ request()->routeIs('home') ? 'text-rose-600' : 'text-slate-600 hover:text-rose-600' }} transition-colors">
                    Beranda
                </a>
                <a href="{{ route('talents.index') }}" class="text-sm font-semibold {{ request()->routeIs('talents.*') ? 'text-rose-600' : 'text-slate-600 hover:text-rose-600' }} transition-colors">
                    Daftar Talent
                </a>
                <a href="{{ route('top-talents.index') }}" class="text-xs font-bold px-3 py-1.5 rounded-full flex items-center gap-1.5 transition {{ request()->routeIs('top-talents.*') ? 'bg-rose-600 text-white shadow-xs' : 'bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100' }}">
                    <span>🏆</span>
                    <span>Top Talent</span>
                </a>
                <a href="{{ route('testimonials.index') }}" class="text-sm font-semibold {{ request()->routeIs('testimonials.*') ? 'text-rose-600' : 'text-slate-600 hover:text-rose-600' }} transition-colors">
                    Testimoni
                </a>

                @auth
                    @php
                        $dashboardRoute = auth()->user()->isAdmin() ? route('admin.dashboard') : route('talent.dashboard');
                        $dashboardLabel = auth()->user()->isAdmin() ? 'Dashboard Admin' : 'Dashboard Talent';
                    @endphp
                    <a href="{{ $dashboardRoute }}" class="text-xs font-bold px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white transition flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>{{ $dashboardLabel }}</span>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-xs font-semibold px-3 py-2 rounded-xl text-slate-600 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 transition">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('talent.register') }}" class="text-xs font-semibold px-3.5 py-2 rounded-xl text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition">
                        Daftar Jadi Talent
                    </a>
                    <a href="{{ route('talent.login') }}" class="text-xs font-semibold px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white transition">
                        Masuk
                    </a>
                @endauth
            </nav>

            <!-- Mobile Action Controls & Menu Toggle (md:hidden) -->
            <div class="flex items-center gap-1.5 sm:gap-2 md:hidden">
                <!-- Quick Top Talent Badge on Mobile -->
                <a href="{{ route('top-talents.index') }}"
                   class="text-[11px] sm:text-xs font-bold px-2.5 py-1.5 rounded-full flex items-center gap-1 transition shrink-0 {{ request()->routeIs('top-talents.*') ? 'bg-rose-600 text-white shadow-xs' : 'bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100' }}">
                    <span>🏆</span>
                    <span>Top</span>
                </a>

                @auth
                    @php
                        $dashboardRoute = auth()->user()->isAdmin() ? route('admin.dashboard') : route('talent.dashboard');
                    @endphp
                    <a href="{{ $dashboardRoute }}"
                       class="text-[11px] sm:text-xs font-bold px-2.5 py-1.5 rounded-xl bg-slate-900 text-white hover:bg-slate-800 transition shrink-0">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('talent.login') }}"
                       class="text-[11px] sm:text-xs font-semibold px-2.5 py-1.5 rounded-xl bg-slate-100 text-slate-800 hover:bg-slate-200 transition shrink-0">
                        Masuk
                    </a>
                @endauth

                <!-- Hamburger Menu Button -->
                <button type="button"
                        id="mobile-menu-button"
                        class="p-1.5 sm:p-2 rounded-xl text-slate-700 hover:text-slate-900 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-rose-500 transition shrink-0"
                        aria-label="Buka menu navigasi"
                        aria-expanded="false"
                        aria-controls="mobile-menu-drawer">
                    <svg id="hamburger-icon" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg id="close-icon" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer / Dropdown Panel -->
        <div id="mobile-menu-drawer" class="hidden md:hidden border-t border-slate-100 bg-white px-4 pt-3 pb-5 space-y-3 shadow-lg">
            <nav class="flex flex-col space-y-1" aria-label="Menu Mobile Lengkap">
                <a href="{{ route('home') }}"
                   class="px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition flex items-center justify-between {{ request()->routeIs('home') ? 'bg-rose-50 text-rose-600 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                    <span>Beranda</span>
                    <span class="text-xs text-slate-400">→</span>
                </a>
                <a href="{{ route('talents.index') }}"
                   class="px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition flex items-center justify-between {{ request()->routeIs('talents.*') ? 'bg-rose-50 text-rose-600 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                    <span>Daftar Talent</span>
                    <span class="text-xs text-slate-400">→</span>
                </a>
                <a href="{{ route('top-talents.index') }}"
                   class="px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition flex items-center justify-between {{ request()->routeIs('top-talents.*') ? 'bg-rose-50 text-rose-600 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                    <span class="flex items-center gap-1.5">
                        <span>🏆</span>
                        <span>Top Talent Mingguan</span>
                    </span>
                    <span class="text-xs text-slate-400">→</span>
                </a>
                <a href="{{ route('testimonials.index') }}"
                   class="px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition flex items-center justify-between {{ request()->routeIs('testimonials.*') ? 'bg-rose-50 text-rose-600 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                    <span>Testimoni Pelanggan</span>
                    <span class="text-xs text-slate-400">→</span>
                </a>
            </nav>

            <div class="pt-3 border-t border-slate-100 flex flex-col gap-2">
                @auth
                    @php
                        $dashboardRoute = auth()->user()->isAdmin() ? route('admin.dashboard') : route('talent.dashboard');
                        $dashboardLabel = auth()->user()->isAdmin() ? 'Dashboard Admin' : 'Dashboard Talent';
                    @endphp
                    <a href="{{ $dashboardRoute }}"
                       class="w-full py-2.5 px-4 rounded-xl text-center text-xs font-bold bg-slate-900 text-white hover:bg-slate-800 transition">
                        Buka {{ $dashboardLabel }} ({{ auth()->user()->name }})
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full py-2 px-4 rounded-xl text-center text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition">
                            Keluar dari Akun (Logout)
                        </button>
                    </form>
                @else
                    <a href="{{ route('talent.register') }}"
                       class="w-full py-2.5 px-4 rounded-xl text-center text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition">
                        Daftar Jadi Talent
                    </a>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('talent.login') }}"
                           class="py-2 px-3 rounded-xl text-center text-xs font-semibold bg-slate-900 text-white hover:bg-slate-800 transition">
                            Login Talent
                        </a>
                        <a href="{{ route('admin.login') }}"
                           class="py-2 px-3 rounded-xl text-center text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                            Login Admin
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 w-full bg-white">
        {{ $slot }}
    </main>

    <!-- Footer Bersih & Sederhana -->
    <footer class="w-full bg-slate-50 border-t border-slate-100 py-8 mt-auto">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-full bg-rose-600 flex items-center justify-center text-white font-bold text-xs shadow-xs">
                        RP
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm font-bold text-slate-800">RentalPacar</p>
                        <p class="text-[11px] text-slate-500">Platform Teman Cerita & Kencan Online Terpercaya</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-center gap-4 sm:gap-6 text-xs text-slate-600">
                    <a href="{{ route('home') }}" class="hover:text-rose-600 transition">Beranda</a>
                    <a href="{{ route('talents.index') }}" class="hover:text-rose-600 transition">Pilih Talent</a>
                    <a href="{{ route('top-talents.index') }}" class="hover:text-rose-600 transition">Top Talent</a>
                    <a href="{{ route('testimonials.index') }}" class="hover:text-rose-600 transition">Testimoni</a>
                </div>

                <p class="text-[11px] sm:text-xs text-slate-400">
                    &copy; {{ date('Y') }} RentalPacar Official. Hak cipta dilindungi.
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
