@extends('layouts.app')

@section('title', 'Invoice Donasi #' . str_pad($donation->id, 6, '0', STR_PAD_LEFT) . ' — Berkah-Link')

@section('content')
<div class="min-h-screen bg-stone-100 py-10 pb-16">
    <div class="max-w-lg mx-auto px-4 sm:px-6">

        {{-- Status Banner --}}
        <div class="text-center mb-6">
            @if($donation->payment_status === 'paid')
                <div class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-800 font-semibold text-sm px-4 py-2 rounded-full border border-emerald-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Pembayaran Berhasil
                </div>
            @else
                <div class="inline-flex items-center gap-2 bg-amber-100 text-amber-800 font-semibold text-sm px-4 py-2 rounded-full border border-amber-200">
                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Menunggu Konfirmasi Pembayaran
                </div>
            @endif
        </div>

        {{-- Invoice Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-stone-200 overflow-hidden" id="invoice-card">

            {{-- Header --}}
            <div class="bg-gradient-to-br from-emerald-950 via-emerald-900 to-teal-800 px-6 py-6 relative overflow-hidden">
                <div class="absolute inset-0 opacity-[0.07]"
                     style="background-image: radial-gradient(circle at 1px 1px, #fff 1px, transparent 0); background-size: 24px 24px;"></div>
                <div class="relative flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-7 h-7 rounded-lg bg-white/20 flex items-center justify-center">
                                <svg viewBox="0 0 24 24" fill="white" width="14" height="14"><path d="M12 21.593c-5.63-5.539-11-10.297-11-14.402 0-3.791 3.068-5.191 5.281-5.191 1.312 0 4.151.501 5.719 4.457 1.59-3.968 4.464-4.447 5.726-4.447 2.54 0 5.274 1.621 5.274 5.181 0 4.069-5.136 8.625-11 14.402z"/></svg>
                            </div>
                            <span class="text-white font-bold text-base" style="font-family: 'Playfair Display', serif;">Berkah-Link</span>
                        </div>
                        <p class="text-emerald-300 text-xs font-semibold uppercase tracking-widest mb-1">Bukti Donasi</p>
                        <p class="text-amber-300 font-bold text-2xl" style="font-family: 'Playfair Display', serif;">
                            #{{ str_pad($donation->id, 6, '0', STR_PAD_LEFT) }}
                        </p>
                        <p class="text-emerald-400 text-xs mt-1">{{ $donation->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
                    </div>
                    {{-- QR Code --}}
                    <div class="bg-white p-1.5 rounded-xl shadow-lg">
                    <div id="qrcode"></div>
                    </div>
                </div>
            </div>

            {{-- Divider dotted --}}
            <div class="relative">
                <div class="border-t-2 border-dashed border-stone-200 mx-6"></div>
                <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-stone-100 border border-stone-200"></div>
                <div class="absolute -right-3 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-stone-100 border border-stone-200"></div>
            </div>

            {{-- Detail Donasi --}}
            <div class="px-6 py-5 space-y-3.5">

                {{-- Nominal --}}
                <div class="bg-emerald-50 border border-emerald-100 rounded-xl px-4 py-3.5 text-center">
                    <p class="text-emerald-700 text-xs font-semibold uppercase tracking-widest mb-1">Total Donasi</p>
                    <p class="text-emerald-900 font-bold text-3xl" style="font-family: 'Playfair Display', serif;">
                        Rp {{ number_format($donation->amount, 0, ',', '.') }}
                    </p>
                </div>

                {{-- Info rows --}}
                @php
                    $rows = [
                        ['label' => 'Campaign',    'value' => $donation->campaign?->title ?? 'Campaign dihapus'],
                        ['label' => 'Donatur',     'value' => $donation->donor_name],
                        ['label' => 'No. WhatsApp','value' => $donation->donor_phone],
                        ['label' => 'Status',      'value' => null], // custom render
                        ['label' => 'Tanggal',     'value' => $donation->created_at->translatedFormat('d F Y')],
                        ['label' => 'Jam',         'value' => $donation->created_at->format('H:i') . ' WIB'],
                    ];
                @endphp

                <div class="space-y-2.5">
                    @foreach($rows as $row)
                        <div class="flex items-start justify-between gap-4 text-sm">
                            <span class="text-stone-400 shrink-0 w-28">{{ $row['label'] }}</span>
                            @if($row['label'] === 'Status')
                                <span class="font-semibold
                                    {{ $donation->payment_status === 'paid' ? 'text-emerald-700' : '' }}
                                    {{ $donation->payment_status === 'pending' ? 'text-amber-700' : '' }}
                                    {{ $donation->payment_status === 'failed' ? 'text-red-700' : '' }}">
                                    {{ $donation->payment_status === 'paid' ? '✓ Lunas' : ($donation->payment_status === 'pending' ? '⏳ Pending' : '✗ Gagal') }}
                                </span>
                            @else
                                <span class="font-semibold text-stone-800 text-right">{{ $row['value'] }}</span>
                            @endif
                        </div>
                    @endforeach

                    @if($donation->message)
                        <div class="flex items-start justify-between gap-4 text-sm">
                            <span class="text-stone-400 shrink-0 w-28">Pesan</span>
                            <span class="font-medium text-stone-600 text-right italic">"{{ $donation->message }}"</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Divider dotted --}}
            <div class="relative">
                <div class="border-t-2 border-dashed border-stone-200 mx-6"></div>
                <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-stone-100 border border-stone-200"></div>
                <div class="absolute -right-3 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-stone-100 border border-stone-200"></div>
            </div>

            {{-- Footer Invoice --}}
            <div class="px-6 py-4 text-center">
                <p class="text-stone-400 text-xs">
                    Simpan halaman ini sebagai bukti donasi kamu.
                </p>
                <p class="text-stone-300 text-[11px] mt-1">
                    Berkah-Link • {{ config('app.url') }}
                </p>
            </div>

        </div>

        {{-- Action Buttons --}}
        <div class="mt-5 space-y-3">

            {{-- Konfirmasi via WA --}}
            @if($donation->payment_status === 'pending')
                <a href="{{ $waLink }}" target="_blank"
                   class="flex items-center justify-center gap-2.5 w-full bg-[#25D366] hover:bg-[#20ba5a] text-white font-bold py-3.5 rounded-xl transition-colors shadow-md text-sm">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    Konfirmasi Pembayaran via WhatsApp
                </a>
            @endif

            {{-- Kembali ke Campaign --}}
            @if($donation->campaign)
                <a href="{{ route('campaigns.show', $donation->campaign) }}"
                   class="flex items-center justify-center gap-2 w-full bg-white hover:bg-stone-50 text-stone-700 font-semibold py-3 rounded-xl transition-colors border border-stone-200 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Campaign
                </a>
            @endif

            {{-- Print --}}
            <button onclick="window.print()"
                    class="flex items-center justify-center gap-2 w-full bg-white hover:bg-stone-50 text-stone-500 font-medium py-3 rounded-xl transition-colors border border-stone-200 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print / Simpan sebagai PDF
            </button>
        </div>

        {{-- Info --}}
        <p class="text-center text-stone-400 text-xs mt-5 leading-relaxed">
            Simpan nomor invoice <span class="font-semibold text-stone-500">#{{ str_pad($donation->id, 6, '0', STR_PAD_LEFT) }}</span> sebagai referensi.<br>
            Konfirmasi pembayaran via WhatsApp agar donasi segera diproses.
        </p>

    </div>
</div>

{{-- Print styles --}}
@push('styles')
<style>
    @media print {
        header, footer, .no-print { display: none !important; }
        body { background: white !important; }
        #invoice-card { box-shadow: none !important; border: 1px solid #e5e7eb !important; }
        .mt-5 { display: none !important; }
    }
</style>
@endpush
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    new QRCode(document.getElementById("qrcode"), {
        text: "{{ route('donations.invoice', $donation) }}",
        width: 80,
        height: 80,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });
</script>
@endpush
@endsection