@extends('layouts.app')

@section('title', 'Dashboard — Berkah-Link')

@section('content')
<div class="min-h-screen bg-stone-100">

    {{-- Header --}}
    <div class="bg-gradient-to-r from-emerald-950 to-teal-800 px-6 py-8">
        <div class="max-w-5xl mx-auto">
            <p class="text-emerald-400 text-sm mb-1">Selamat datang kembali 👋</p>
            <h1 class="text-white font-bold text-2xl" style="font-family:'Playfair Display',serif;">
                {{ Auth::user()->name }}
            </h1>
            @if(Auth::user()->is_admin)
                <span class="inline-flex items-center gap-1 bg-amber-400/20 text-amber-300 text-xs font-semibold px-3 py-1 rounded-full mt-2 border border-amber-400/30">
                    🛡 Administrator
                </span>
            @endif
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8 space-y-6">

        {{-- Flash --}}
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- {{-- Quick Actions --}}
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('campaigns.create') }}"
               class="inline-flex items-center gap-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Buat Campaign Baru
            </a>
            @if(Auth::user()->is_admin)
                <a href="{{ route('admin.index') }}"
                   class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition-colors shadow-sm">
                    🛡 Admin Panel
                </a>
            @endif
            <a href="{{ route('campaigns.index') }}"
               class="inline-flex items-center gap-2 bg-white hover:bg-stone-50 text-stone-700 font-semibold px-5 py-2.5 rounded-xl text-sm transition-colors shadow-sm border border-stone-200">
                Lihat Semua Campaign
            </a>
        </div> -->

        {{-- Stats --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-4 text-center">
                <p class="text-2xl font-bold text-emerald-800" style="font-family:'Playfair Display',serif;">{{ $stats['total_campaigns'] }}</p>
                <p class="text-xs text-stone-400 mt-1">Total Campaign</p>
            </div>
            <div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-4 text-center">
                <p class="text-2xl font-bold text-emerald-600" style="font-family:'Playfair Display',serif;">{{ $stats['active_campaigns'] }}</p>
                <p class="text-xs text-stone-400 mt-1">Campaign Aktif</p>
            </div>
            <div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-4 text-center">
                <p class="text-2xl font-bold text-amber-600" style="font-family:'Playfair Display',serif;">{{ $stats['total_donations'] }}</p>
                <p class="text-xs text-stone-400 mt-1">Total Donasi</p>
            </div>
            <div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-4 text-center">
                <p class="text-lg font-bold text-emerald-700 leading-tight" style="font-family:'Playfair Display',serif;">
                    Rp {{ number_format($stats['total_amount'] / 1000, 0, ',', '.') }}rb
                </p>
                <p class="text-xs text-stone-400 mt-1">Dana Terkumpul</p>
            </div>
        </div>

        {{-- Campaign Saya --}}
        <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-stone-100 flex items-center justify-between">
                <h2 class="font-bold text-slate-800">Campaign Saya</h2>
                <a href="{{ route('campaigns.create') }}" class="text-emerald-700 text-sm font-semibold hover:underline">+ Buat Baru</a>
            </div>
            @forelse($campaigns as $campaign)
                @php
                    $pct = $campaign->target_amount > 0
                        ? min(100, ($campaign->current_amount / $campaign->target_amount) * 100)
                        : 0;
                    $badgeColor = match($campaign->status) {
                        'active'    => 'bg-emerald-100 text-emerald-700',
                        'draft'     => 'bg-stone-100 text-stone-500',
                        'completed' => 'bg-blue-100 text-blue-700',
                        'cancelled' => 'bg-red-100 text-red-600',
                        default     => 'bg-stone-100 text-stone-500',
                    };
                @endphp
                <div class="px-6 py-4 border-b border-stone-100 last:border-0 hover:bg-stone-50 transition-colors">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $badgeColor }}">
                                    {{ ucfirst($campaign->status) }}
                                </span>
                                <span class="text-xs text-stone-400">{{ $campaign->donations_count }} donatur</span>
                            </div>
                            <a href="{{ route('campaigns.show', $campaign) }}"
                               class="font-semibold text-slate-800 hover:text-emerald-700 transition-colors text-sm">
                                {{ $campaign->title }}
                            </a>
                            <div class="mt-2">
                                <div class="flex justify-between text-xs text-stone-400 mb-1">
                                    <span>Rp {{ number_format($campaign->current_amount, 0, ',', '.') }}</span>
                                    <span>{{ number_format($pct, 0) }}%</span>
                                </div>
                                <div class="w-full bg-stone-100 rounded-full h-1.5">
                                    <div class="h-full rounded-full bg-emerald-500" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2 shrink-0">
                            <a href="{{ route('campaigns.edit', $campaign) }}"
                               class="text-xs font-semibold text-stone-500 hover:text-emerald-700 bg-stone-100 hover:bg-emerald-50 px-3 py-1.5 rounded-lg transition-colors">
                                Edit
                            </a>
                            <a href="{{ route('campaigns.show', $campaign) }}"
                               class="text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-lg transition-colors">
                                Lihat
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center">
                    <p class="text-3xl mb-2">🌱</p>
                    <p class="text-stone-500 font-medium text-sm mb-1">Belum ada campaign</p>
                    <p class="text-stone-400 text-xs mb-4">Mulai galang dana untuk hal yang kamu pedulikan</p>
                    <a href="{{ route('campaigns.create') }}"
                       class="inline-flex items-center gap-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold px-4 py-2 rounded-xl text-sm transition-colors">
                        Buat Campaign Pertama
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Donasi Masuk --}}
        <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-stone-100">
                <h2 class="font-bold text-slate-800">Donasi Masuk Terbaru</h2>
                <p class="text-stone-400 text-xs mt-0.5">Donasi ke semua campaign kamu</p>
            </div>
            @forelse($recentDonations as $donation)
                <div class="px-6 py-3.5 border-b border-stone-100 last:border-0 hover:bg-stone-50 transition-colors">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 font-bold text-xs flex items-center justify-center shrink-0">
                                {{ strtoupper(substr($donation->donor_name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-slate-700 truncate">{{ $donation->donor_name }}</p>
                                <p class="text-xs text-stone-400 truncate">{{ $donation->campaign?->title ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-sm font-bold text-emerald-700">Rp {{ number_format($donation->amount, 0, ',', '.') }}</p>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                                {{ $donation->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $donation->payment_status === 'paid' ? '✓ Lunas' : '⏳ Pending' }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-10 text-center text-stone-400 text-sm">
                    Belum ada donasi masuk.
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection