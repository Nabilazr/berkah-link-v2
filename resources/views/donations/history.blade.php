@extends('layouts.app')

@section('title', 'Riwayat Donasi Saya — Berkah-Link')

@section('content')
<div class="min-h-screen bg-stone-50">

    {{-- HEADER --}}
    <div class="bg-gradient-to-br from-emerald-950 via-emerald-900 to-teal-800 relative overflow-hidden">
        <div class="absolute inset-0 opacity-[0.07]"
             style="background-image: radial-gradient(circle at 1px 1px, #fff 1px, transparent 0); background-size: 32px 32px;"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10 relative">
            <div class="flex items-center gap-2 text-emerald-300 text-sm mb-4">
                <a href="{{ route('campaigns.index') }}" class="hover:text-amber-300 transition-colors">Beranda</a>
                <span class="text-emerald-600">›</span>
                <span class="text-emerald-100">Riwayat Donasi</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white mb-1" style="font-family: 'Playfair Display', serif;">
                Riwayat Donasi Saya
            </h1>
            <p class="text-emerald-300 text-sm">Semua kebaikan yang pernah kamu bagikan.</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8">

        @if (session('success'))
            <div class="mb-6 flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3.5 text-sm font-medium">
                <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- SUMMARY --}}
        @php
            $totalNominal = $donations->sum('amount');
            $totalPaid    = $donations->where('payment_status', 'paid')->sum('amount');
            $totalPending = $donations->where('payment_status', 'pending')->count();
        @endphp
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-2xl border border-stone-100 shadow-sm px-4 py-4 text-center">
                <p class="text-2xl font-bold text-emerald-800" style="font-family: 'Playfair Display', serif;">{{ $donations->total() }}</p>
                <p class="text-xs text-stone-400 mt-1">Total Donasi</p>
            </div>
            <div class="bg-white rounded-2xl border border-stone-100 shadow-sm px-4 py-4 text-center">
                <p class="text-lg font-bold text-amber-600 leading-tight" style="font-family: 'Playfair Display', serif;">
                    Rp {{ number_format($totalNominal / 1000, 0, ',', '.') }}rb
                </p>
                <p class="text-xs text-stone-400 mt-1">Total Nominal</p>
            </div>
            <div class="bg-white rounded-2xl border border-stone-100 shadow-sm px-4 py-4 text-center">
                <p class="text-lg font-bold text-emerald-600 leading-tight" style="font-family: 'Playfair Display', serif;">
                    Rp {{ number_format($totalPaid / 1000, 0, ',', '.') }}rb
                </p>
                <p class="text-xs text-stone-400 mt-1">Sudah Dibayar</p>
            </div>
            <div class="bg-white rounded-2xl border border-stone-100 shadow-sm px-4 py-4 text-center">
                <p class="text-2xl font-bold text-amber-500" style="font-family: 'Playfair Display', serif;">{{ $totalPending }}</p>
                <p class="text-xs text-stone-400 mt-1">Menunggu Bayar</p>
            </div>
        </div>

        {{-- LIST --}}
        @if ($donations->isEmpty())
            <div class="bg-white rounded-2xl border border-stone-100 shadow-sm py-16 text-center">
                <p class="text-5xl mb-4">💛</p>
                <p class="text-stone-600 font-semibold mb-1">Belum ada donasi</p>
                <p class="text-stone-400 text-sm mb-6">Yuk mulai berbagi kebaikan!</p>
                <a href="{{ route('campaigns.index') }}"
                   class="inline-flex items-center gap-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition-colors">
                    Lihat Campaign
                </a>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($donations as $donation)
                    @php
                        $statusStyle = match($donation->payment_status) {
                            'paid'    => 'bg-emerald-100 text-emerald-700',
                            'pending' => 'bg-amber-100 text-amber-700',
                            'failed'  => 'bg-red-100 text-red-700',
                            default   => 'bg-stone-100 text-stone-500',
                        };
                        $statusIcon = match($donation->payment_status) {
                            'paid'    => '✓',
                            'pending' => '⏳',
                            'failed'  => '✗',
                            default   => '?',
                        };
                    @endphp
                    <div class="bg-white rounded-2xl border border-stone-100 shadow-sm px-5 py-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-start gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                </div>
                                <div class="min-w-0">
                                    @if ($donation->campaign)
                                        <a href="{{ route('campaigns.show', $donation->campaign) }}"
                                           class="text-sm font-semibold text-emerald-800 hover:text-emerald-600 transition-colors line-clamp-1">
                                            {{ $donation->campaign->title }}
                                        </a>
                                    @else
                                        <p class="text-sm text-stone-400 italic">Campaign telah dihapus</p>
                                    @endif
                                    <div class="flex items-center gap-3 mt-1 flex-wrap">
                                        <p class="text-xs text-stone-400">
                                            Atas nama: <span class="text-stone-600 font-medium">{{ $donation->donor_name }}</span>
                                        </p>
                                        <p class="text-xs text-stone-400">
                                            📱 {{ $donation->donor_phone }}
                                        </p>
                                    </div>
                                    @if ($donation->message)
                                        <p class="text-xs text-stone-400 mt-1.5 italic line-clamp-1">
                                            "{{ $donation->message }}"
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="text-right shrink-0">
                                <p class="text-sm font-bold text-emerald-700">
                                    Rp {{ number_format($donation->amount, 0, ',', '.') }}
                                </p>
                                <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full mt-1 {{ $statusStyle }}">
                                    {{ $statusIcon }} {{ ucfirst($donation->payment_status) }}
                                </span>
                                <p class="text-[11px] text-stone-300 mt-1.5">
                                    {{ $donation->created_at->translatedFormat('d M Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 flex justify-center">
                {{ $donations->links() }}
            </div>
        @endif

    </div>
</div>
@endsection