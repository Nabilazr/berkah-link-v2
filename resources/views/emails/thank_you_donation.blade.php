<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terima Kasih — Berkah-Link</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f5f5f0; color: #1c1c1c; }
        .wrapper { max-width: 560px; margin: 40px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #052e16, #14532d, #134e4a); padding: 36px 32px; text-align: center; }
        .header .logo { color: #ffffff; font-size: 22px; font-weight: 700; letter-spacing: -0.5px; margin-bottom: 4px; }
        .header .tagline { color: #6ee7b7; font-size: 13px; }
        .badge { display: inline-block; background: #d1fae5; color: #065f46; font-size: 13px; font-weight: 600; padding: 6px 16px; border-radius: 100px; margin: 24px 0 0; }
        .body { padding: 32px; }
        .greeting { font-size: 18px; font-weight: 700; color: #1c1c1c; margin-bottom: 12px; }
        .text { font-size: 14px; color: #6b7280; line-height: 1.7; margin-bottom: 24px; }
        .card { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 20px 24px; margin-bottom: 24px; }
        .card-title { font-size: 11px; font-weight: 700; color: #059669; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 14px; }
        .row { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; }
        .row:last-child { margin-bottom: 0; }
        .label { font-size: 13px; color: #9ca3af; }
        .value { font-size: 13px; font-weight: 600; color: #1c1c1c; text-align: right; max-width: 60%; }
        .nominal { background: #ecfdf5; border: 1px solid #6ee7b7; border-radius: 10px; padding: 16px; text-align: center; margin-bottom: 24px; }
        .nominal-label { font-size: 11px; color: #059669; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
        .nominal-value { font-size: 28px; font-weight: 800; color: #064e3b; }
        .btn { display: block; text-align: center; background: #059669; color: #ffffff !important; text-decoration: none; padding: 14px 24px; border-radius: 10px; font-weight: 700; font-size: 14px; margin-bottom: 24px; }
        .divider { border: none; border-top: 1px dashed #d1fae5; margin: 24px 0; }
        .footer { padding: 24px 32px; background: #f9fafb; text-align: center; border-top: 1px solid #f3f4f6; }
        .footer p { font-size: 12px; color: #9ca3af; line-height: 1.6; }
        .footer a { color: #059669; text-decoration: none; }
    </style>
</head>
<body>
    <div class="wrapper">
        {{-- Header --}}
        <div class="header">
            <div class="logo">❤️ Berkah-Link</div>
            <div class="tagline">Platform Donasi Terpercaya</div>
            <div class="badge">✓ Pembayaran Dikonfirmasi</div>
        </div>

        {{-- Body --}}
        <div class="body">
            <p class="greeting">Terima kasih, {{ $donation->donor_name }}! 🙏</p>
            <p class="text">
                Donasi kamu telah kami terima dan dikonfirmasi oleh admin.
                Semoga kebaikanmu menjadi berkah dan memberikan manfaat nyata bagi yang membutuhkan.
            </p>

            {{-- Nominal --}}
            <div class="nominal">
                <div class="nominal-label">Total Donasi</div>
                <div class="nominal-value">Rp {{ number_format($donation->amount, 0, ',', '.') }}</div>
            </div>

            {{-- Detail --}}
            <div class="card">
                <div class="card-title">Detail Donasi</div>
                <div class="row">
                    <span class="label">No. Invoice</span>
                    <span class="value">#{{ str_pad($donation->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="row">
                    <span class="label">Campaign</span>
                    <span class="value">{{ $donation->campaign?->title ?? '-' }}</span>
                </div>
                <div class="row">
                    <span class="label">Tanggal</span>
                    <span class="value">{{ $donation->updated_at->translatedFormat('d F Y, H:i') }} WIB</span>
                </div>
                <div class="row">
                    <span class="label">Status</span>
                    <span class="value" style="color: #059669;">✓ Lunas</span>
                </div>
                @if($donation->message)
                <div class="row">
                    <span class="label">Pesan</span>
                    <span class="value" style="font-style: italic; font-weight: 400; color: #6b7280;">"{{ $donation->message }}"</span>
                </div>
                @endif
            </div>

            {{-- CTA --}}
            <a href="{{ route('donations.invoice', $donation) }}" class="btn">
                Lihat Bukti Donasi Lengkap
            </a>

            <hr class="divider">

            <p class="text" style="margin-bottom: 0;">
                Jika ada pertanyaan, jangan ragu untuk menghubungi kami via WhatsApp di
                <strong>{{ env('ADMIN_WHATSAPP') }}</strong>.
            </p>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p>
                Email ini dikirim otomatis oleh <strong>Berkah-Link</strong>.<br>
                Mohon jangan membalas email ini.<br>
                <a href="{{ config('app.url') }}">{{ config('app.url') }}</a>
            </p>
        </div>
    </div>
</body>
</html>
