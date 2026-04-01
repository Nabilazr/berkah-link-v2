<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Berkah-Link — Platform Donasi Terpercaya')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['Playfair Display', 'Georgia', 'serif'],
                        body:    ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50:  '#f0fdf4', 100: '#dcfce7', 200: '#bbf7d0',
                            300: '#86efac', 500: '#22c55e', 700: '#15803d',
                            800: '#166534', 900: '#14532d', 950: '#052e16',
                        },
                        gold: { 300: '#fcd34d', 400: '#fbbf24', 500: '#f59e0b' }
                    },
                    boxShadow: { 'nav': '0 1px 0 0 rgba(0,0,0,0.06), 0 4px 16px -4px rgba(0,0,0,0.08)' }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
        main { animation: fadeUp .35s ease both; }
        #mobile-menu { transition: max-height .25s ease, opacity .2s ease; max-height: 0; overflow: hidden; opacity: 0; }
        #mobile-menu.open { max-height: 500px; opacity: 1; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f0fdf4; }
        ::-webkit-scrollbar-thumb { background: #86efac; border-radius: 3px; }
    </style>
    @stack('styles')
</head>
<body class="bg-stone-50 text-stone-800 antialiased min-h-screen flex flex-col">

    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md shadow-nav border-b border-stone-100">
        <nav class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16">

                <a href="{{ route('campaigns.index') }}" class="flex items-center gap-2.5 group">
                    <div class="w-8 h-8 rounded-lg bg-brand-800 flex items-center justify-center shadow-sm group-hover:bg-brand-700 transition-colors">
                        <svg viewBox="0 0 24 24" fill="white" width="18" height="18"><path d="M12 21.593c-5.63-5.539-11-10.297-11-14.402 0-3.791 3.068-5.191 5.281-5.191 1.312 0 4.151.501 5.719 4.457 1.59-3.968 4.464-4.447 5.726-4.447 2.54 0 5.274 1.621 5.274 5.181 0 4.069-5.136 8.625-11 14.402z"/></svg>
                    </div>
                    <span class="font-display font-bold text-brand-900 text-lg leading-none tracking-tight">Berkah<span class="text-brand-700">-</span>Link</span>
                </a>

                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('campaigns.index') }}" class="px-3.5 py-2 rounded-lg text-sm font-medium text-stone-600 hover:text-brand-800 hover:bg-brand-50 transition-all {{ request()->routeIs('campaigns.index') ? 'text-brand-800 bg-brand-50 font-semibold' : '' }}">Home</a>
                    <a href="{{ route('campaigns.index') }}" class="px-3.5 py-2 rounded-lg text-sm font-medium text-stone-600 hover:text-brand-800 hover:bg-brand-50 transition-all">Campaign</a>
                    @auth
                        <a href="{{ route('donations.history') }}" class="px-3.5 py-2 rounded-lg text-sm font-medium text-stone-600 hover:text-brand-800 hover:bg-brand-50 transition-all {{ request()->routeIs('donations.history') ? 'text-brand-800 bg-brand-50 font-semibold' : '' }}">Riwayat Donasi</a>
                    @endauth
                    <div class="w-px h-5 bg-stone-200 mx-2"></div>
                    @guest
                        <a href="{{ route('login') }}" class="px-3.5 py-2 rounded-lg text-sm font-medium text-stone-600 hover:text-brand-800 hover:bg-brand-50 transition-all">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg text-sm font-semibold bg-brand-800 text-white hover:bg-brand-700 transition-colors shadow-sm">Daftar</a>
                        @endif
                    @else
                        <div class="relative" id="user-menu-wrapper">
                            <button id="user-menu-btn" class="flex items-center gap-2 pl-3 pr-2.5 py-1.5 rounded-lg hover:bg-brand-50 transition-all group">
                                <div class="w-7 h-7 rounded-full {{ Auth::user()->is_admin ? 'bg-slate-800' : 'bg-brand-800' }} text-white text-xs font-bold flex items-center justify-center">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="text-sm font-medium text-stone-700 group-hover:text-brand-800 max-w-[100px] truncate">
                                    {{ Auth::user()->name }}
                                    @if(Auth::user()->is_admin)<span class="text-[10px] text-slate-400 font-normal ml-0.5">(Admin)</span>@endif
                                </span>
                                <svg class="w-4 h-4 text-stone-400 transition-transform" id="dropdown-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div id="user-dropdown" class="hidden absolute right-0 mt-1.5 w-52 bg-white rounded-xl shadow-lg border border-stone-100 py-1.5 z-50">
                                <div class="px-3.5 py-2.5 border-b border-stone-100">
                                    <p class="text-xs font-semibold text-stone-800 truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-[11px] text-stone-400 truncate mt-0.5">{{ Auth::user()->email }}</p>
                                    @if(Auth::user()->is_admin)
                                        <span class="inline-block mt-1.5 text-[10px] font-semibold bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">🛡 Administrator</span>
                                    @endif
                                </div>

                                @if(Auth::user()->is_admin)
                                    <a href="{{ route('admin.index') }}" class="flex items-center gap-2.5 px-3.5 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition-colors {{ request()->routeIs('admin.*') ? 'bg-slate-50' : '' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                        Admin Panel
                                    </a>
                                    <div class="border-b border-stone-100 my-1"></div>
                                @endif

                                <a href="{{ route('campaigns.create') }}" class="flex items-center gap-2.5 px-3.5 py-2 text-sm text-stone-600 hover:bg-brand-50 hover:text-brand-800 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Buat Campaign
                                </a>
                                <a href="{{ route('donations.history') }}" class="flex items-center gap-2.5 px-3.5 py-2 text-sm text-stone-600 hover:bg-brand-50 hover:text-brand-800 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    Riwayat Donasi
                                </a>
                                <div class="border-t border-stone-100 mt-1 pt-1">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2.5 px-3.5 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors text-left">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('campaigns.create') }}" class="ml-1 px-4 py-2 rounded-lg text-sm font-semibold bg-brand-800 text-white hover:bg-brand-700 transition-colors shadow-sm flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            Buat Campaign
                        </a>
                    @endguest
                </div>

                <button id="mobile-toggle" class="md:hidden p-2 rounded-lg text-stone-500 hover:bg-stone-100 transition-colors"
                        onclick="const menu=document.getElementById('mobile-menu');menu.classList.toggle('open');document.getElementById('hamburger-icon').classList.toggle('rotate-90');">
                    <svg id="hamburger-icon" class="w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <div id="mobile-menu" class="md:hidden border-t border-stone-100">
                <div class="py-3 space-y-0.5">
                    <a href="{{ route('campaigns.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-stone-600 hover:bg-brand-50 hover:text-brand-800 transition-colors">Home</a>
                    <a href="{{ route('campaigns.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-stone-600 hover:bg-brand-50 hover:text-brand-800 transition-colors">Campaign</a>
                    @auth
                        @if(Auth::user()->is_admin)
                            <a href="{{ route('admin.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-100 transition-colors">
                                🛡 Admin Panel
                            </a>
                        @endif
                        <a href="{{ route('donations.history') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-stone-600 hover:bg-brand-50 hover:text-brand-800 transition-colors">Riwayat Donasi</a>
                        <a href="{{ route('campaigns.create') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-stone-600 hover:bg-brand-50 hover:text-brand-800 transition-colors">Buat Campaign</a>
                        <div class="px-4 pt-2 pb-1 border-t border-stone-100 mt-1">
                            <p class="text-xs text-stone-400 mb-2">Masuk sebagai <span class="font-semibold text-stone-600">{{ Auth::user()->name }}</span></p>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 rounded-lg text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 transition-colors">Keluar</button>
                            </form>
                        </div>
                    @else
                        <div class="flex gap-2 px-4 pt-2 pb-1 border-t border-stone-100 mt-1">
                            <a href="{{ route('login') }}" class="flex-1 text-center py-2 rounded-lg text-sm font-medium border border-stone-200 text-stone-600 hover:bg-stone-50 transition-colors">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="flex-1 text-center py-2 rounded-lg text-sm font-semibold bg-brand-800 text-white hover:bg-brand-700 transition-colors">Daftar</a>
                            @endif
                        </div>
                    @endguest
                </div>
            </div>
        </nav>
    </header>

    <main class="flex-1">@yield('content')</main>

    <footer class="bg-brand-950 text-stone-300 mt-auto">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center gap-2.5 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-brand-700 flex items-center justify-center">
                            <svg viewBox="0 0 24 24" fill="white" width="16" height="16"><path d="M12 21.593c-5.63-5.539-11-10.297-11-14.402 0-3.791 3.068-5.191 5.281-5.191 1.312 0 4.151.501 5.719 4.457 1.59-3.968 4.464-4.447 5.726-4.447 2.54 0 5.274 1.621 5.274 5.181 0 4.069-5.136 8.625-11 14.402z"/></svg>
                        </div>
                        <span class="font-display font-bold text-white text-lg">Berkah-Link</span>
                    </div>
                    <p class="text-stone-400 text-sm leading-relaxed">Platform penggalangan dana terpercaya untuk membantu sesama dengan mudah dan transparan.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold text-sm mb-3">Platform</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('campaigns.index') }}" class="text-stone-400 hover:text-white transition-colors">Semua Campaign</a></li>
                        @auth
                            <li><a href="{{ route('campaigns.create') }}" class="text-stone-400 hover:text-white transition-colors">Buat Campaign</a></li>
                            <li><a href="{{ route('donations.history') }}" class="text-stone-400 hover:text-white transition-colors">Riwayat Donasi</a></li>
                            @if(Auth::user()->is_admin)
                                <li><a href="{{ route('admin.index') }}" class="text-stone-400 hover:text-white transition-colors">Admin Panel</a></li>
                            @endif
                        @endauth
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold text-sm mb-3">Informasi</h4>
                    <ul class="space-y-2 text-sm">
                        <li><span class="text-stone-400">Pembayaran aman via Mayar</span></li>
                        <li><span class="text-stone-400">Dana tersalurkan 100%</span></li>
                        <li><span class="text-stone-400">Laporan transparan</span></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="border-t border-brand-900">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-2">
                <p class="text-stone-500 text-xs">&copy; {{ date('Y') }} <span class="text-stone-400 font-medium">Berkah-Link</span>. Dibuat dengan ❤️ untuk kebaikan.</p>
                <div class="flex items-center gap-1 text-xs text-stone-500">
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></div>
                    Semua transaksi diproses dengan aman
                </div>
            </div>
        </div>
    </footer>

    <script>
        const menuBtn  = document.getElementById('user-menu-btn');
        const dropdown = document.getElementById('user-dropdown');
        const chevron  = document.getElementById('dropdown-chevron');
        if (menuBtn) {
            menuBtn.addEventListener('click', () => {
                dropdown.classList.toggle('hidden');
                chevron.style.transform = dropdown.classList.contains('hidden') ? '' : 'rotate(180deg)';
            });
            document.addEventListener('click', (e) => {
                if (!menuBtn.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.add('hidden');
                    chevron.style.transform = '';
                }
            });
        }
    </script>
    @stack('scripts')
</body>
</html>