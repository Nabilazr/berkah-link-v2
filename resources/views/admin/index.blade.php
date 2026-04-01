@extends('layouts.app')

@section('title', 'Admin Dashboard — Berkah-Link')

@section('content')
<div class="min-h-screen bg-stone-100">

    {{-- Header --}}
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 px-6 py-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center gap-3 mb-1">
                <span class="text-2xl">🛡</span>
                <h1 class="text-white font-bold text-2xl" style="font-family:'Playfair Display',serif;">Admin Dashboard</h1>
            </div>
            <p class="text-slate-400 text-sm ml-10">Kelola campaign dan konfirmasi donasi</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 space-y-8">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('info'))
            <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-xl text-sm font-medium">
                {{ session('info') }}
            </div>
        @endif

        {{-- Stats Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @php
                $statCards = [
                    ['label' => 'Total Campaign',    'value' => $stats['total_campaigns'],   'icon' => '📋', 'color' => 'bg-blue-50 border-blue-100'],
                    ['label' => 'Campaign Aktif',    'value' => $stats['active_campaigns'],  'icon' => '✅', 'color' => 'bg-emerald-50 border-emerald-100'],
                    ['label' => 'Total Donasi',      'value' => $stats['total_donations'],   'icon' => '💛', 'color' => 'bg-amber-50 border-amber-100'],
                    ['label' => 'Dana Terkumpul',    'value' => 'Rp ' . number_format($stats['total_amount'], 0, ',', '.'), 'icon' => '💰', 'color' => 'bg-green-50 border-green-100'],
                    ['label' => 'Pending',           'value' => $stats['pending_donations'], 'icon' => '⏳', 'color' => 'bg-orange-50 border-orange-100'],
                    ['label' => 'Total User',        'value' => $stats['total_users'],       'icon' => '👥', 'color' => 'bg-purple-50 border-purple-100'],
                ];
            @endphp
            @foreach($statCards as $card)
                <div class="bg-white border {{ $card['color'] }} rounded-xl p-4 text-center shadow-sm">
                    <div class="text-2xl mb-2">{{ $card['icon'] }}</div>
                    <div class="font-bold text-slate-800 text-lg leading-tight">{{ $card['value'] }}</div>
                    <div class="text-slate-400 text-xs mt-1">{{ $card['label'] }}</div>
                </div>
            @endforeach
        </div>

        {{-- ── TABEL DONASI ── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-stone-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-stone-100 flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-slate-800 text-lg">Daftar Donasi</h2>
                    <p class="text-slate-400 text-xs mt-0.5">Klik "Tandai Lunas" setelah menerima bukti pembayaran dari donatur</p>
                </div>
                @if($stats['pending_donations'] > 0)
                    <span class="bg-orange-100 text-orange-700 text-xs font-bold px-3 py-1 rounded-full">
                        {{ $stats['pending_donations'] }} Pending
                    </span>
                @endif
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-stone-50 text-stone-500 text-xs uppercase tracking-wide">
                            <th class="px-4 py-3 text-left">Invoice</th>
                            <th class="px-4 py-3 text-left">Donatur</th>
                            <th class="px-4 py-3 text-left">Campaign</th>
                            <th class="px-4 py-3 text-right">Nominal</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-left">Pesan</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse($donations as $donation)
                            <tr class="hover:bg-stone-50 transition-colors {{ $donation->payment_status === 'pending' ? 'bg-amber-50/30' : '' }}">
                                <td class="px-4 py-3">
                                    <a href="{{ route('donations.invoice', $donation) }}" target="_blank"
                                       class="font-mono text-xs text-emerald-700 hover:underline font-semibold">
                                        #{{ str_pad($donation->id, 6, '0', STR_PAD_LEFT) }}
                                    </a>
                                    <div class="text-stone-400 text-xs">{{ $donation->created_at->format('d/m/y H:i') }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-slate-800">{{ $donation->donor_name }}</div>
                                    @if($donation->donor_email)
                                        <div class="text-stone-400 text-xs">{{ $donation->donor_email }}</div>
                                    @endif
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $donation->donor_phone) }}" target="_blank"
                                       class="text-emerald-600 text-xs hover:underline">
                                        {{ $donation->donor_phone }}
                                    </a>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-slate-700 text-xs max-w-[140px] truncate" title="{{ $donation->campaign?->title }}">
                                        {{ $donation->campaign?->title ?? '—' }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <span class="font-bold text-slate-800">Rp {{ number_format($donation->amount, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($donation->payment_status === 'paid')
                                        <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                            ✓ Lunas
                                        </span>
                                    @elseif($donation->payment_status === 'pending')
                                        <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                            ⏳ Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                            ✗ Gagal
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-stone-500 text-xs italic">
                                        {{ $donation->message ? '"' . Str::limit($donation->message, 40) . '"' : '—' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- Tombol Tandai Lunas --}}
                                        @if($donation->payment_status === 'pending')
                                            @php $invoiceNo = str_pad($donation->id, 6, '0', STR_PAD_LEFT); @endphp
                                            <form action="{{ route('admin.donations.markAsPaid', $donation) }}" method="POST"
                                                  onsubmit="return confirm('Tandai donasi #{{ $invoiceNo }} dari {{ $donation->donor_name }} sebagai LUNAS?\n\nEmail terima kasih akan dikirim otomatis ke donatur.')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-3 py-1.5 rounded-lg transition-colors whitespace-nowrap">
                                                    ✓ Tandai Lunas
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('admin.donations.destroy', $donation) }}" method="POST"
                                              onsubmit="return confirm('Hapus donasi ini permanen?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors border border-red-200">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-10 text-center text-stone-400 text-sm">
                                    Belum ada donasi masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($donations->hasPages())
                <div class="px-6 py-4 border-t border-stone-100">
                    {{ $donations->appends(request()->except('donations_page'))->links() }}
                </div>
            @endif
        </div>

        {{-- ── TABEL CAMPAIGN ── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-stone-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-stone-100">
                <h2 class="font-bold text-slate-800 text-lg">Daftar Campaign</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-stone-50 text-stone-500 text-xs uppercase tracking-wide">
                            <th class="px-4 py-3 text-left">Campaign</th>
                            <th class="px-4 py-3 text-left">Pembuat</th>
                            <th class="px-4 py-3 text-right">Target</th>
                            <th class="px-4 py-3 text-right">Terkumpul</th>
                            <th class="px-4 py-3 text-center">Donatur</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse($campaigns as $campaign)
                            <tr class="hover:bg-stone-50 transition-colors">
                                <td class="px-4 py-3">
                                    <a href="{{ route('campaigns.show', $campaign) }}" target="_blank"
                                       class="font-semibold text-slate-800 hover:text-emerald-700 transition-colors line-clamp-2 max-w-[180px] block">
                                        {{ $campaign->title }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-stone-500 text-xs">{{ $campaign->user?->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-right text-slate-700 text-xs">Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right">
                                    <span class="font-semibold text-emerald-700 text-xs">Rp {{ number_format($campaign->current_amount, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-4 py-3 text-center text-slate-600 font-semibold text-sm">{{ $campaign->donations_count }}</td>
                                <td class="px-4 py-3 text-center">
                                    <form action="{{ route('admin.campaigns.status', $campaign) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()"
                                                class="text-xs border border-stone-200 rounded-lg px-2 py-1.5 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 cursor-pointer
                                                {{ $campaign->status === 'active' ? 'text-emerald-700 bg-emerald-50' : '' }}
                                                {{ $campaign->status === 'draft' ? 'text-slate-600' : '' }}
                                                {{ $campaign->status === 'completed' ? 'text-blue-700 bg-blue-50' : '' }}
                                                {{ $campaign->status === 'cancelled' ? 'text-red-700 bg-red-50' : '' }}">
                                            <option value="draft"     {{ $campaign->status === 'draft'     ? 'selected' : '' }}>Draft</option>
                                            <option value="active"    {{ $campaign->status === 'active'    ? 'selected' : '' }}>Aktif</option>
                                            <option value="completed" {{ $campaign->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                            <option value="cancelled" {{ $campaign->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('campaigns.edit', $campaign) }}"
                                           class="bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.campaigns.destroy', $campaign) }}" method="POST"
                                              onsubmit="return confirm('Hapus campaign ini? Semua donasi terkait juga akan terhapus!')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors border border-red-200">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-10 text-center text-stone-400 text-sm">
                                    Belum ada campaign.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($campaigns->hasPages())
                <div class="px-6 py-4 border-t border-stone-100">
                    {{ $campaigns->appends(request()->except('campaigns_page'))->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection