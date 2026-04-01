@extends('layouts.app')

@section('title', $campaign->title . ' — Berkah-Link')

@section('content')
@php
    $pct = $campaign->target_amount > 0
        ? min(100, ($campaign->current_amount / $campaign->target_amount) * 100)
        : 0;
    $paidDonations = $campaign->donations->where('payment_status', 'paid');
@endphp

<div class="min-h-screen bg-stone-50">

    {{-- HERO BANNER --}}
    <div class="bg-gradient-to-br from-emerald-950 via-emerald-900 to-teal-800 relative overflow-hidden">
        <div class="absolute inset-0 opacity-[0.07]"
             style="background-image: radial-gradient(circle at 1px 1px, #fff 1px, transparent 0); background-size: 32px 32px;"></div>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-10 relative">
            <div class="flex items-center gap-2 text-emerald-300 text-sm mb-5">
                <a href="{{ route('campaigns.index') }}" class="hover:text-amber-300 transition-colors">Campaign</a>
                <span class="text-emerald-600">›</span>
                <span class="text-emerald-100 truncate max-w-xs">{{ Str::limit($campaign->title, 40) }}</span>
            </div>

            @php
                $badgeMap = [
                    'active'    => 'bg-emerald-400/20 text-emerald-200 ring-1 ring-emerald-400/40',
                    'draft'     => 'bg-white/10 text-white/60 ring-1 ring-white/20',
                    'completed' => 'bg-amber-400/20 text-amber-200 ring-1 ring-amber-400/40',
                    'cancelled' => 'bg-red-400/20 text-red-200 ring-1 ring-red-400/40',
                ];
            @endphp
            <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $badgeMap[$campaign->status] ?? '' }}">
                {{ ucfirst($campaign->status) }}
            </span>

            <h1 class="text-2xl sm:text-3xl font-bold text-white leading-snug max-w-2xl mt-3 mb-3"
                style="font-family: 'Playfair Display', serif;">
                {{ $campaign->title }}
            </h1>

            <div class="flex flex-wrap items-center gap-4 text-sm text-emerald-300">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ $campaign->created_at->translatedFormat('d M Y') }}
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                    {{ $paidDonations->count() }} donatur
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    {{ $campaign->user?->name ?? 'Anonim' }}
                </span>
            </div>
        </div>
    </div>

    {{-- MAIN --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">

        {{-- Flash messages --}}
        @if (session('success'))
            <div class="mb-6 flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3.5 text-sm font-medium">
                <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3.5 text-sm font-medium">
                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            {{-- ── LEFT ── --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Deskripsi --}}
                <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-6">
                    <h2 class="text-lg font-bold text-emerald-900 mb-4" style="font-family: 'Playfair Display', serif;">
                        Tentang Campaign Ini
                    </h2>
                    <p class="text-stone-700 leading-relaxed whitespace-pre-line text-[0.95rem]">{{ $campaign->description }}</p>
                </div>

                {{-- Donatur Terbaru --}}
                <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-6">
                    <h2 class="text-lg font-bold text-emerald-900 mb-1 flex items-center gap-2" style="font-family: 'Playfair Display', serif;">
                        ❤️ Donatur Terbaru
                    </h2>
                    <p class="text-stone-400 text-xs mb-5">{{ $paidDonations->count() }} orang sudah berdonasi</p>

                    @forelse ($paidDonations->sortByDesc('created_at')->take(10) as $donation)
                        <div class="flex items-start gap-3 py-3.5 border-b border-stone-100 last:border-0">
                            {{-- Avatar --}}
                            <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-700 font-bold text-sm flex items-center justify-center shrink-0">
                                {{ strtoupper(substr($donation->donor_name ?? 'H', 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-sm font-semibold text-stone-800 truncate">
                                        {{ $donation->donor_name ?? 'Hamba Allah' }}
                                    </p>
                                    <p class="text-sm font-bold text-emerald-700 shrink-0">
                                        Rp {{ number_format($donation->amount, 0, ',', '.') }}
                                    </p>
                                </div>
                                @if ($donation->message)
                                    <p class="text-xs text-stone-400 mt-1 italic line-clamp-2">
                                        "{{ $donation->message }}"
                                    </p>
                                @endif
                                <p class="text-[11px] text-stone-300 mt-1">{{ $donation->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-stone-400">
                            <p class="text-3xl mb-2">🌱</p>
                            <p class="text-sm">Belum ada donatur. Jadilah yang pertama!</p>
                        </div>
                    @endforelse
                </div>

            </div>

            {{-- ── RIGHT: Sticky Card ── --}}
            <div class="lg:col-span-1">
                <div class="sticky top-20 space-y-4">

                    {{-- Progress Card --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-stone-100 overflow-hidden">
                        <div class="bg-gradient-to-br from-emerald-900 to-teal-700 px-5 py-5">
                            <p class="text-emerald-300 text-xs font-semibold uppercase tracking-widest mb-1">Terkumpul</p>
                            <p class="text-amber-300 font-bold text-2xl" style="font-family: 'Playfair Display', serif;">
                                Rp {{ number_format($campaign->current_amount, 0, ',', '.') }}
                            </p>
                            <p class="text-emerald-400 text-xs mt-1">
                                dari target <span class="text-white font-semibold">Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</span>
                            </p>
                        </div>

                        <div class="px-5 py-4">
                            <div class="flex justify-between text-xs font-semibold text-stone-500 mb-1.5">
                                <span>Progress</span>
                                <span class="text-emerald-600">{{ number_format($pct, 0) }}%</span>
                            </div>
                            <div class="w-full bg-stone-100 rounded-full h-2.5 overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-400 transition-all duration-700"
                                     style="width: {{ $pct }}%"></div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 mt-4">
                                <div class="bg-stone-50 rounded-xl p-3 text-center">
                                    <p class="text-lg font-bold text-emerald-800">{{ $paidDonations->count() }}</p>
                                    <p class="text-[11px] text-stone-400 mt-0.5">Donatur</p>
                                </div>
                                <div class="bg-stone-50 rounded-xl p-3 text-center">
                                    <p class="text-lg font-bold text-emerald-800">{{ number_format($pct, 0) }}%</p>
                                    <p class="text-[11px] text-stone-400 mt-0.5">Tercapai</p>
                                </div>
                            </div>

                            {{-- Tombol Donasi --}}
                            @if ($campaign->status === 'active')
                                <button onclick="document.getElementById('modal-donasi').classList.remove('hidden')"
                                        class="mt-4 w-full bg-amber-400 hover:bg-amber-500 active:scale-95 text-emerald-950 font-bold py-3 rounded-xl transition-all duration-150 text-sm flex items-center justify-center gap-2 shadow-md shadow-amber-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    Donasi Sekarang
                                </button>
                            @else
                                <div class="mt-4 w-full bg-stone-100 text-stone-400 font-medium py-3 rounded-xl text-sm text-center">
                                    Campaign tidak aktif
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Owner Card --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-stone-100 px-5 py-4">
                        <p class="text-xs text-stone-400 font-semibold uppercase tracking-widest mb-3">Penggalang Dana</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-emerald-700 text-white font-bold flex items-center justify-center text-base shrink-0">
                                {{ strtoupper(substr($campaign->user?->name ?? 'A', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-stone-800 text-sm">{{ $campaign->user?->name ?? 'Anonim' }}</p>
                                <p class="text-xs text-stone-400">Penggalang dana</p>
                            </div>
                        </div>

                        @auth
                            @if (Auth::id() === $campaign->user_id)
                                <div class="flex gap-2 mt-4 pt-4 border-t border-stone-100">
                                    <a href="{{ route('campaigns.edit', $campaign) }}"
                                       class="flex-1 text-center text-sm font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 py-2 rounded-lg transition-colors">
                                        ✏️ Edit
                                    </a>
                                    <form action="{{ route('campaigns.destroy', $campaign) }}" method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus campaign ini?')" class="flex-1">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="w-full text-sm font-semibold text-red-600 bg-red-50 hover:bg-red-100 py-2 rounded-lg transition-colors">
                                            🗑 Hapus
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     MODAL FORM DONASI
══════════════════════════════════════ --}}
<div id="modal-donasi"
     class="hidden fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4"
     onclick="if(event.target===this) this.classList.add('hidden')">

    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">

        {{-- Modal Header --}}
        <div class="bg-gradient-to-br from-emerald-900 to-teal-700 px-6 py-5">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-white font-bold text-lg" style="font-family: 'Playfair Display', serif;">
                        💛 Form Donasi
                    </h3>
                    <p class="text-emerald-300 text-sm mt-0.5">{{ Str::limit($campaign->title, 45) }}</p>
                </div>
                <button onclick="document.getElementById('modal-donasi').classList.add('hidden')"
                        class="text-emerald-300 hover:text-white transition-colors mt-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Modal Body --}}
        <form action="{{ route('donations.store') }}" method="POST" class="px-6 py-5 space-y-4">
            @csrf
            <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">

            {{-- Nama --}}
            <div>
                <label class="block text-sm font-semibold text-stone-700 mb-1.5">
                    Nama <span class="text-red-500">*</span>
                </label>
                <input type="text" name="donor_name"
                       value="{{ old('donor_name') }}"
                       placeholder="Nama kamu atau 'Hamba Allah'"
                       class="w-full border rounded-xl px-4 py-2.5 text-sm text-stone-800 placeholder-stone-300 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition {{ $errors->has('donor_name') ? 'border-red-400 bg-red-50' : 'border-stone-200' }}">
                @error('donor_name')
                    <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Nomor WhatsApp --}}
            <div>
                <label class="block text-sm font-semibold text-stone-700 mb-1.5">
                    Nomor WhatsApp <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-stone-400 text-sm">📱</span>
                    <input type="tel" name="donor_phone"
                           value="{{ old('donor_phone') }}"
                           placeholder="08123456789"
                           class="w-full border rounded-xl pl-10 pr-4 py-2.5 text-sm text-stone-800 placeholder-stone-300 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition {{ $errors->has('donor_phone') ? 'border-red-400 bg-red-50' : 'border-stone-200' }}">
                </div>
                @error('donor_phone')
                    <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>
            {{-- Email --}}
            <div>
                <label class="block text-sm font-semibold text-stone-700 mb-1.5">
                    Email <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-stone-400 text-sm">✉️</span>
                    <input type="email" name="donor_email"
                        value="{{ old('donor_email') }}"
                        placeholder="emailkamu@gmail.com"
                        class="w-full border rounded-xl pl-10 pr-4 py-2.5 text-sm text-stone-800 placeholder-stone-300 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition {{ $errors->has('donor_email') ? 'border-red-400 bg-red-50' : 'border-stone-200' }}">
                </div>
                @error('donor_email')
                    <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Nominal --}}
            <div>
                <label class="block text-sm font-semibold text-stone-700 mb-1.5">
                    Nominal Donasi <span class="text-red-500">*</span>
                </label>

                {{-- Quick pick --}}
                <div class="grid grid-cols-3 gap-2 mb-2.5">
                    @foreach ([10000, 25000, 50000, 100000, 250000, 500000] as $nominal)
                        <button type="button"
                                onclick="document.getElementById('amount-input').value = {{ $nominal }}; document.querySelectorAll('.nominal-btn').forEach(b => b.classList.remove('ring-2','ring-emerald-400','bg-emerald-50','text-emerald-700')); this.classList.add('ring-2','ring-emerald-400','bg-emerald-50','text-emerald-700')"
                                class="nominal-btn text-xs font-semibold border border-stone-200 rounded-lg py-1.5 text-stone-500 hover:border-emerald-400 hover:text-emerald-700 transition-all">
                            Rp {{ number_format($nominal, 0, ',', '.') }}
                        </button>
                    @endforeach
                </div>

                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-stone-400 text-sm font-medium">Rp</span>
                    <input type="number" name="amount" id="amount-input"
                           value="{{ old('amount') }}"
                           placeholder="0" min="1000"
                           class="w-full border rounded-xl pl-10 pr-4 py-2.5 text-sm text-stone-800 placeholder-stone-300 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition {{ $errors->has('amount') ? 'border-red-400 bg-red-50' : 'border-stone-200' }}">
                </div>
                @error('amount')
                    <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
                <p class="text-stone-400 text-[11px] mt-1.5">Minimal donasi Rp 1.000</p>
            </div>

            {{-- Pesan (opsional) --}}
            <div>
                <label class="block text-sm font-semibold text-stone-700 mb-1.5">
                    Pesan <span class="text-stone-400 font-normal">(opsional)</span>
                </label>
                <textarea name="message" rows="2"
                          placeholder="Tulis semangat atau doa untuk campaign ini..."
                          maxlength="500"
                          class="w-full border border-stone-200 rounded-xl px-4 py-2.5 text-sm text-stone-800 placeholder-stone-300 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition resize-none {{ $errors->has('message') ? 'border-red-400 bg-red-50' : '' }}">{{ old('message') }}</textarea>
                @error('message')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full bg-amber-400 hover:bg-amber-500 active:scale-95 text-emerald-950 font-bold py-3 rounded-xl transition-all duration-150 text-sm flex items-center justify-center gap-2 shadow-md shadow-amber-200/60">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                Lanjut ke Pembayaran
            </button>

            <p class="text-center text-[11px] text-stone-400">
                🔒 Pembayaran aman diproses via Mayar
            </p>
        </form>
    </div>
</div>

{{-- Buka modal otomatis jika ada error validasi --}}
@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('modal-donasi').classList.remove('hidden');
    });
</script>
@endif

@endsection