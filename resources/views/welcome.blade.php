@extends('layouts.app')

@section('title', 'Berkah-Link — Platform Donasi Terpercaya')

@section('content')

{{-- ═══════════════════════════════════════════════
     HERO SECTION
═══════════════════════════════════════════════ --}}
<section class="relative bg-brand-950 overflow-hidden">

    {{-- Background pattern --}}
    <div class="absolute inset-0 opacity-[0.07]"
         style="background-image: radial-gradient(circle at 1px 1px, #fff 1px, transparent 0); background-size: 32px 32px;"></div>

    {{-- Gradient orbs --}}
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-brand-700 rounded-full blur-3xl opacity-20"></div>
    <div class="absolute -bottom-24 -right-20 w-80 h-80 bg-teal-600 rounded-full blur-3xl opacity-15"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 pt-20 pb-24 sm:pt-28 sm:pb-32">
        <div class="max-w-3xl">

            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 bg-brand-900/80 border border-brand-700/60 rounded-full px-4 py-1.5 mb-6">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-emerald-300 text-xs font-semibold tracking-wide uppercase">Platform Donasi Terpercaya</span>
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-[1.1] mb-6"
                style="font-family: 'Playfair Display', serif;">
                Satu Langkah Kecil,<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 to-amber-400">
                    Dampak yang Besar
                </span>
            </h1>

            <p class="text-stone-300 text-lg leading-relaxed mb-10 max-w-xl">
                Berkah-Link menghubungkan mereka yang ingin berbagi dengan mereka yang membutuhkan.
                Donasi mudah, aman, dan transparan.
            </p>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('campaigns.index') }}"
                   class="inline-flex items-center gap-2 bg-amber-400 hover:bg-amber-300 text-brand-950 font-bold px-6 py-3 rounded-xl transition-all hover:-translate-y-0.5 shadow-lg shadow-amber-500/20 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    Mulai Berdonasi
                </a>
                @guest
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white font-semibold px-6 py-3 rounded-xl border border-white/20 transition-all hover:-translate-y-0.5 text-sm">
                        Buat Campaign
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                @else
                    <a href="{{ route('campaigns.create') }}"
                       class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white font-semibold px-6 py-3 rounded-xl border border-white/20 transition-all hover:-translate-y-0.5 text-sm">
                        Buat Campaign
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                @endguest
            </div>
        </div>
    </div>

    {{-- Wave divider --}}
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 48" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path d="M0 48L60 42.7C120 37.3 240 26.7 360 21.3C480 16 600 16 720 21.3C840 26.7 960 37.3 1080 40C1200 42.7 1320 37.3 1380 34.7L1440 32V48H1380C1320 48 1200 48 1080 48C960 48 840 48 720 48C600 48 480 48 360 48C240 48 120 48 60 48H0Z" fill="#fafaf9"/>
        </svg>
    </div>
</section>

{{-- ═══════════════════════════════════════════════
     STATS STRIP
═══════════════════════════════════════════════ --}}
<section class="bg-stone-50 border-b border-stone-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-3 divide-x divide-stone-200">
            @php
                $totalCampaigns = \App\Models\Campaign::count();
                $totalDonors    = \App\Models\Donation::where('payment_status','paid')->count();
                $totalFunds     = \App\Models\Donation::where('payment_status','paid')->sum('amount');
            @endphp
            <div class="py-6 px-4 sm:px-8 text-center">
                <p class="text-2xl sm:text-3xl font-bold text-brand-800" style="font-family: 'Playfair Display', serif;">
                    {{ $totalCampaigns }}
                </p>
                <p class="text-xs sm:text-sm text-stone-400 mt-1 font-medium">Campaign Aktif</p>
            </div>
            <div class="py-6 px-4 sm:px-8 text-center">
                <p class="text-2xl sm:text-3xl font-bold text-brand-800" style="font-family: 'Playfair Display', serif;">
                    {{ number_format($totalDonors) }}
                </p>
                <p class="text-xs sm:text-sm text-stone-400 mt-1 font-medium">Total Donatur</p>
            </div>
            <div class="py-6 px-4 sm:px-8 text-center">
                <p class="text-2xl sm:text-3xl font-bold text-amber-600" style="font-family: 'Playfair Display', serif;">
                    Rp {{ number_format($totalFunds / 1e6, 1) }}Jt
                </p>
                <p class="text-xs sm:text-sm text-stone-400 mt-1 font-medium">Dana Tersalurkan</p>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════
     CAMPAIGN TERBARU
═══════════════════════════════════════════════ --}}
<section class="py-16 sm:py-20 bg-stone-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">

        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="text-brand-700 text-sm font-semibold uppercase tracking-widest mb-2">Pilihan Terkini</p>
                <h2 class="text-3xl font-bold text-brand-950" style="font-family: 'Playfair Display', serif;">
                    Campaign Terbaru
                </h2>
            </div>
            <a href="{{ route('campaigns.index') }}"
               class="hidden sm:inline-flex items-center gap-1.5 text-sm font-semibold text-brand-700 hover:text-brand-900 transition-colors">
                Lihat Semua
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        @php
            $latestCampaigns = \App\Models\Campaign::with('user')
                ->withCount('donations')
                ->where('status', 'active')
                ->latest()
                ->take(3)
                ->get();
        @endphp

        @if ($latestCampaigns->isEmpty())
            <div class="text-center py-16 text-stone-400">
                <p class="text-4xl mb-3">🌱</p>
                <p class="font-medium">Belum ada campaign aktif saat ini.</p>
                @auth
                    <a href="{{ route('campaigns.create') }}" class="mt-4 inline-flex items-center gap-2 bg-brand-700 text-white font-semibold px-5 py-2.5 rounded-xl text-sm hover:bg-brand-800 transition-colors">
                        Buat Campaign Pertama
                    </a>
                @endauth
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($latestCampaigns as $campaign)
                    @php
                        $pct = $campaign->target_amount > 0
                            ? min(100, ($campaign->current_amount / $campaign->target_amount) * 100)
                            : 0;
                    @endphp
                    <a href="{{ route('campaigns.show', $campaign) }}"
                       class="group bg-white rounded-2xl border border-stone-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-200 overflow-hidden flex flex-col">

                        {{-- Color bar --}}
                        <div class="h-1.5 bg-gradient-to-r from-brand-600 via-teal-400 to-amber-400"></div>

                        <div class="p-5 flex flex-col flex-1">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-[11px] font-bold uppercase tracking-widest text-brand-600 bg-brand-50 px-2.5 py-1 rounded-full">
                                    Aktif
                                </span>
                                <span class="text-xs text-stone-400">{{ $campaign->donations_count }} donatur</span>
                            </div>

                            <h3 class="font-bold text-stone-800 text-base leading-snug mb-2 group-hover:text-brand-800 transition-colors line-clamp-2"
                                style="font-family: 'Playfair Display', serif;">
                                {{ $campaign->title }}
                            </h3>

                            <p class="text-stone-400 text-sm leading-relaxed line-clamp-2 mb-4 flex-1">
                                {{ $campaign->description }}
                            </p>

                            {{-- Progress --}}
                            <div>
                                <div class="flex justify-between text-xs font-semibold mb-1.5">
                                    <span class="text-brand-700">Rp {{ number_format($campaign->current_amount, 0, ',', '.') }}</span>
                                    <span class="text-stone-400">{{ number_format($pct, 0) }}%</span>
                                </div>
                                <div class="w-full bg-stone-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="h-full rounded-full bg-gradient-to-r from-brand-500 to-teal-400"
                                         style="width: {{ $pct }}%"></div>
                                </div>
                                <p class="text-[11px] text-stone-400 mt-1.5">
                                    dari Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}
                                </p>
                            </div>

                            {{-- Author --}}
                            <div class="flex items-center gap-2 mt-4 pt-4 border-t border-stone-100">
                                <div class="w-6 h-6 rounded-full bg-brand-700 text-white text-[10px] font-bold flex items-center justify-center">
                                    {{ strtoupper(substr($campaign->user->name ?? 'A', 0, 1)) }}
                                </div>
                                <span class="text-xs text-stone-400">{{ $campaign->user->name ?? 'Anonim' }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="text-center mt-8 sm:hidden">
                <a href="{{ route('campaigns.index') }}"
                   class="inline-flex items-center gap-2 text-sm font-semibold text-brand-700 hover:text-brand-900 transition-colors">
                    Lihat Semua Campaign
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        @endif

    </div>
</section>

{{-- ═══════════════════════════════════════════════
     HOW IT WORKS
═══════════════════════════════════════════════ --}}
<section class="py-16 sm:py-20 bg-white border-t border-stone-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">

        <div class="text-center mb-12">
            <p class="text-brand-700 text-sm font-semibold uppercase tracking-widest mb-2">Cara Kerja</p>
            <h2 class="text-3xl font-bold text-brand-950" style="font-family: 'Playfair Display', serif;">
                Mudah dalam 3 Langkah
            </h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
            @foreach ([
                ['icon' => '🔍', 'step' => '01', 'title' => 'Temukan Campaign', 'desc' => 'Jelajahi berbagai campaign yang membutuhkan dukunganmu.'],
                ['icon' => '💛', 'step' => '02', 'title' => 'Berikan Donasi',   'desc' => 'Pilih nominal dan selesaikan pembayaran dengan mudah via Mayar.'],
                ['icon' => '🌟', 'step' => '03', 'title' => 'Lihat Dampaknya',  'desc' => 'Pantau perkembangan campaign dan dampak kebaikanmu secara transparan.'],
            ] as $item)
                <div class="relative text-center group">
                    {{-- Connector line --}}
                    @if (!$loop->last)
                        <div class="hidden sm:block absolute top-8 left-[calc(50%+2.5rem)] right-[calc(-50%+2.5rem)] h-px bg-gradient-to-r from-stone-200 to-stone-100 z-0"></div>
                    @endif

                    <div class="relative z-10 inline-flex flex-col items-center">
                        <div class="w-16 h-16 rounded-2xl bg-brand-50 border border-brand-100 flex items-center justify-center text-3xl mb-4 group-hover:bg-brand-100 group-hover:scale-105 transition-all shadow-sm">
                            {{ $item['icon'] }}
                        </div>
                        <span class="text-[10px] font-black text-brand-300 tracking-widest uppercase mb-2">{{ $item['step'] }}</span>
                        <h3 class="font-bold text-stone-800 mb-2 text-base" style="font-family: 'Playfair Display', serif;">{{ $item['title'] }}</h3>
                        <p class="text-stone-400 text-sm leading-relaxed max-w-[200px]">{{ $item['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════
     CTA BOTTOM
═══════════════════════════════════════════════ --}}
@guest
<section class="py-16 bg-gradient-to-br from-brand-900 to-brand-950 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10"
         style="background-image: radial-gradient(circle at 1px 1px, #fff 1px, transparent 0); background-size: 28px 28px;"></div>
    <div class="relative max-w-2xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold text-white mb-4" style="font-family: 'Playfair Display', serif;">
            Siap Mulai Berbagi?
        </h2>
        <p class="text-stone-300 mb-8 leading-relaxed">
            Bergabunglah dengan ribuan orang yang sudah merasakan berkah berbagi melalui Berkah-Link.
        </p>
        <div class="flex flex-wrap justify-center gap-3">
            <a href="{{ route('register') }}"
               class="inline-flex items-center gap-2 bg-amber-400 hover:bg-amber-300 text-brand-950 font-bold px-6 py-3 rounded-xl transition-all hover:-translate-y-0.5 shadow-lg shadow-amber-500/20 text-sm">
                Daftar Gratis Sekarang
            </a>
            <a href="{{ route('campaigns.index') }}"
               class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white font-semibold px-6 py-3 rounded-xl border border-white/20 transition-all text-sm">
                Lihat Campaign
            </a>
        </div>
    </div>
</section>
@endguest

@endsection