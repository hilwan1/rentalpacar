<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin Dashboard - RentalPacar' }}</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 font-sans antialiased flex flex-col selection:bg-rose-500 selection:text-white">

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div id="admin-sidebar-backdrop" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-40 hidden md:hidden transition-opacity"></div>

    <!-- Sidebar Admin -->
    <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200 flex flex-col justify-between transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out shadow-lg md:shadow-none">
        <div>
            <!-- Sidebar Header & Brand -->
            <div class="h-16 px-5 border-b border-slate-100 flex items-center justify-between">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('logo.jpeg') }}" alt="Logo RentalPacar" class="w-9 h-9 rounded-full object-cover border border-rose-100 shadow-sm shadow-rose-600/10 shrink-0">
                    <div>
                        <span class="text-sm font-extrabold tracking-tight text-slate-900 block">RentalPacar</span>
                        <span class="text-[10px] font-bold text-rose-600 uppercase tracking-wider block">Panel Admin</span>
                    </div>
                </a>

                <!-- Mobile Close Button -->
                <button type="button" id="admin-sidebar-close" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 md:hidden" aria-label="Tutup Sidebar">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-6" aria-label="Menu Navigasi Admin">
                <div>
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-2">
                        Navigasi Utama
                    </span>
                    <div class="space-y-1">
                        <a href="{{ route('admin.dashboard') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-rose-50 text-rose-600 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-rose-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            <span>Dashboard Ringkasan</span>
                        </a>

                        <a href="{{ route('admin.orders.index') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.orders.*') ? 'bg-rose-50 text-rose-600 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.orders.*') ? 'text-rose-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            <span>Pesanan Masuk</span>
                        </a>

                        <a href="{{ route('admin.talents.index') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.talents.*') ? 'bg-rose-50 text-rose-600 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.talents.*') ? 'text-rose-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span>Daftar Akun Talent</span>
                        </a>

                        <a href="{{ route('admin.testimonials.index') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.testimonials.*') ? 'bg-rose-50 text-rose-600 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.testimonials.*') ? 'text-rose-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <span>Kelola Testimoni</span>
                        </a>

                        <a href="{{ route('admin.settings.whatsapp') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.settings.whatsapp*') ? 'bg-rose-50 text-rose-600 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.settings.whatsapp*') ? 'text-rose-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <span>Pengaturan WhatsApp</span>
                        </a>

                        <a href="{{ route('admin.profile.edit') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.profile.*') ? 'bg-rose-50 text-rose-600 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.profile.*') ? 'text-rose-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Profil & Password</span>
                        </a>
                    </div>
                </div>

                <div>
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-2">
                        Situs Utama
                    </span>
                    <div class="space-y-1">
                        <a href="{{ route('home') }}" target="_blank"
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition">
                            <span class="flex items-center gap-3">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                <span>Lihat Website</span>
                            </span>
                            <span class="text-[10px] text-slate-400">↗</span>
                        </a>

                        <a href="{{ route('talents.index') }}" target="_blank"
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition">
                            <span class="flex items-center gap-3">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span>Katalog Talent</span>
                            </span>
                            <span class="text-[10px] text-slate-400">↗</span>
                        </a>

                        <a href="{{ route('testimonials.index') }}" target="_blank"
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition">
                            <span class="flex items-center gap-3">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                <span>Lihat Testimoni Publik</span>
                            </span>
                            <span class="text-[10px] text-slate-400">↗</span>
                        </a>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Sidebar Footer / Admin Info & Logout -->
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            <a href="{{ route('admin.profile.edit') }}"
               class="group flex items-center gap-3 mb-3 p-2 -mx-2 rounded-xl hover:bg-white border border-transparent hover:border-slate-200 transition">
                <div class="w-9 h-9 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-xs shrink-0 group-hover:bg-rose-600 transition">
                    {{ strtoupper(substr(auth()->user()->name ?? 'AD', 0, 2)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold text-slate-900 truncate group-hover:text-rose-600 transition">{{ auth()->user()->name }}</p>
                    <p class="text-[11px] text-slate-500 truncate">{{ auth()->user()->email }}</p>
                </div>
                <svg class="w-4 h-4 text-slate-400 group-hover:text-rose-600 transition shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 py-2 px-3 rounded-xl bg-white hover:bg-rose-50 text-slate-700 hover:text-rose-600 border border-slate-200 text-xs font-semibold transition active:scale-95 shadow-2xs">
                    <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Keluar Akun</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Wrapper with Left Margin for Desktop Sidebar -->
    <div class="flex-1 md:pl-64 flex flex-col min-h-screen">
        <!-- Top Navbar -->
        <header class="h-16 bg-white border-b border-slate-200/80 sticky top-0 z-30 flex items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <!-- Hamburger Button (Mobile Only) -->
                <button type="button"
                        id="admin-sidebar-open"
                        class="p-2 rounded-xl text-slate-700 hover:text-slate-900 hover:bg-slate-100 md:hidden focus:outline-none focus:ring-2 focus:ring-rose-500 transition"
                        aria-label="Buka menu navigasi sidebar">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div>
                    <h1 class="text-sm sm:text-base font-extrabold text-slate-900">{{ $header ?? 'Dashboard' }}</h1>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}"
                   class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-rose-600 hover:bg-rose-50 rounded-lg border border-slate-200 transition">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    <span>Website Publik</span>
                </a>

                <div class="flex items-center gap-2 pl-2 border-l border-slate-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-medium text-slate-600 hidden sm:inline">Admin Online</span>
                </div>

                <a href="{{ route('admin.profile.edit') }}"
                   class="inline-flex items-center gap-2 p-1 sm:px-2.5 sm:py-1 rounded-full hover:bg-slate-100 border border-slate-200/80 transition group"
                   title="Kelola Profil Administrator">
                    <div class="w-6 h-6 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-[10px] group-hover:bg-rose-600 transition">
                        {{ strtoupper(substr(auth()->user()->name ?? 'AD', 0, 2)) }}
                    </div>
                    <span class="text-xs font-semibold text-slate-700 hidden sm:inline max-w-[120px] truncate group-hover:text-rose-600 transition">
                        {{ auth()->user()->name }}
                    </span>
                </a>
            </div>
        </header>

        <!-- Main Page Content -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            {{ $slot }}
        </main>

        <!-- Minimal Admin Footer -->
        <footer class="py-4 px-4 sm:px-6 lg:px-8 bg-white border-t border-slate-200/80 text-center sm:text-left flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-400">
            <p>&copy; {{ date('Y') }} RentalPacar Administrator. Hak cipta dilindungi.</p>
            <p>Versi Sistem: 1.0.0 (Laravel 12)</p>
        </footer>
    </div>

    <!-- Script Toggle Sidebar Mobile -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('admin-sidebar-backdrop');
            const openBtn = document.getElementById('admin-sidebar-open');
            const closeBtn = document.getElementById('admin-sidebar-close');

            function toggleSidebar(open) {
                if (!sidebar || !backdrop) return;
                if (open) {
                    sidebar.classList.remove('-translate-x-full');
                    backdrop.classList.remove('hidden');
                } else {
                    sidebar.classList.add('-translate-x-full');
                    backdrop.classList.add('hidden');
                }
            }

            openBtn?.addEventListener('click', () => toggleSidebar(true));
            closeBtn?.addEventListener('click', () => toggleSidebar(false));
            backdrop?.addEventListener('click', () => toggleSidebar(false));
        });
    </script>
</body>
</html>

