@extends('layouts.app')

@section('title', 'Semua Campaign — Berkah-Link')

@section('content')
<div class="min-h-screen bg-stone-50">

    {{-- HERO --}}
    <section class="relative bg-gradient-to-br from-emerald-950 via-emerald-900 to-teal-800 overflow-hidden">
        <div class="absolute inset-0 opacity-[0.07]"
             style="background-image: radial-gradient(circle at 1px 1px, #fff 1px, transparent 0); background-size: 32px 32px;"></div>
        <div class="absolute -top-24 -right-24 w-72 h-72 bg-teal-500 rounded-full blur-3xl opacity-10"></div>

        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 py-14">
            <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2 leading-snug"
                style="font-family: 'Playfair Display', serif;">
                Mari Bersama <span class="text-amber-300">Berbagi Kebaikan</span>
            </h1>
            <p class="text-emerald-300 text-base mb-8 max-w-lg">
                Platform penggalangan dana terpercaya untuk membantu sesama dengan mudah dan transparan.
            </p>

            {{-- Stats --}}
            <div class="grid grid-cols-3 gap-3 max-w-xl">
                <div class="bg-white/10 backdrop-blur border border-white/15 rounded-xl px-4 py-3">
                    <p class="text-amber-300 font-bold text-2xl" style="font-family: 'Playfair Display', serif;">
                        {{ $campaigns->total() }}
                    </p>
                    <p class="text-emerald-300 text-xs mt-0.5">Total Campaign</p>
                </div>
                <div class="bg-white/10 backdrop-blur border border-white/15 rounded-xl px-4 py-3">
                    <p class="text-amber-300 font-bold text-2xl" style="font-family: 'Playfair Display', serif;">
                        {{ $campaigns->where('status', 'active')->count() }}
                    </p>
                    <p class="text-emerald-300 text-xs mt-0.5">Campaign Aktif</p>
                </div>
                <div class="bg-white/10 backdrop-blur border border-white/15 rounded-xl px-4 py-3">
                    <p class="text-amber-300 font-bold text-xl leading-tight" style="font-family: 'Playfair Display', serif;">
                        Rp {{ number_format($campaigns->sum('current_amount') / 1e6, 1) }}Jt
                    </p>
                    <p class="text-emerald-300 text-xs mt-0.5">Dana Terkumpul</p>
                </div>
            </div>
        </div>

        {{-- Wave --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 40" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path d="M0 40L60 34.7C120 29.3 240 18.7 360 16C480 13.3 600 18.7 720 21.3C840 24 960 24 1080 21.3C1200 18.7 1320 13.3 1380 10.7L1440 8V40H0Z" fill="#fafaf9"/>
            </svg>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 py-10">

        @if (session('success'))
            <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3 text-sm font-medium">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm font-medium">
                <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Toolbar --}}
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-emerald-950" style="font-family: 'Playfair Display', serif;">
                Semua Campaign
            </h2>
            @auth
                <a href="{{ route('campaigns.create') }}"
                   class="inline-flex items-center gap-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold px-4 py-2.5 rounded-xl text-sm transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Buat Campaign
                </a>
            @endauth
        </div>

        {{-- Empty State --}}
        @if ($campaigns->isEmpty())
            <div class="text-center py-20">
                <p class="text-5xl mb-4">🌱</p>
                <p class="text-stone-500 font-medium mb-1">Belum ada campaign.</p>
                <p class="text-stone-400 text-sm mb-6">Jadilah yang pertama membuat campaign!</p>
                @auth
                    <a href="{{ route('campaigns.create') }}"
                       class="inline-flex items-center gap-2 bg-emerald-700 text-white font-semibold px-5 py-2.5 rounded-xl text-sm hover:bg-emerald-800 transition-colors">
                        Buat Campaign Sekarang
                    </a>
                @endauth
            </div>

        {{-- Campaign Grid --}}
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($campaigns as $campaign)
                    @php
                        $pct = $campaign->target_amount > 0
                            ? min(100, ($campaign->current_amount / $campaign->target_amount) * 100)
                            : 0;
                        $badgeMap = [
                            'active'    => 'bg-emerald-100 text-emerald-700',
                            'draft'     => 'bg-stone-100 text-stone-500',
                            'completed' => 'bg-amber-100 text-amber-700',
                            'cancelled' => 'bg-red-100 text-red-600',
                        ];
                    @endphp
                    <a href="{{ route('campaigns.show', $campaign) }}"
                       class="group bg-white rounded-2xl border border-stone-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-200 overflow-hidden flex flex-col">
                        <div class="h-1.5 bg-gradient-to-r from-emerald-600 via-teal-400 to-amber-400"></div>
                        <div class="p-5 flex flex-col flex-1">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full {{ $badgeMap[$campaign->status] ?? 'bg-stone-100 text-stone-500' }}">
                                    {{ ucfirst($campaign->status) }}
                                </span>
                                <span class="text-xs text-stone-400">{{ $campaign->donations_count }} donatur</span>
                            </div>

                            <h3 class="font-bold text-stone-800 text-base leading-snug mb-2 group-hover:text-emerald-800 transition-colors line-clamp-2"
                                style="font-family: 'Playfair Display', serif;">
                                {{ $campaign->title }}
                            </h3>

                            <p class="text-stone-400 text-sm leading-relaxed line-clamp-2 mb-4 flex-1">
                                {{ $campaign->description }}
                            </p>

                            {{-- Progress --}}
                            <div>
                                <div class="flex justify-between text-xs font-semibold mb-1.5">
                                    <span class="text-emerald-700">Rp {{ number_format($campaign->current_amount, 0, ',', '.') }}</span>
                                    <span class="text-stone-400">{{ number_format($pct, 0) }}%</span>
                                </div>
                                <div class="w-full bg-stone-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-400"
                                         style="width: {{ $pct }}%"></div>
                                </div>
                                <p class="text-[11px] text-stone-400 mt-1.5">
                                    dari Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}
                                </p>
                            </div>

                            {{-- Author --}}
                            <div class="flex items-center gap-2 mt-4 pt-4 border-t border-stone-100">
                                <div class="w-6 h-6 rounded-full bg-emerald-700 text-white text-[10px] font-bold flex items-center justify-center shrink-0">
                                    {{ strtoupper(substr($campaign->user?->name ?? 'A', 0, 1)) }}
                                </div>
                                <span class="text-xs text-stone-400">{{ $campaign->user?->name ?? 'Anonim' }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8 flex justify-center">
                {{ $campaigns->links() }}
            </div>
        @endif

    </section>
</div>
@endsection