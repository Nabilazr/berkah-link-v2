<?php

namespace App\Http\Controllers;

use App\Mail\ThankYouDonation;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class DonationController extends Controller
{
    /**
     * Menyimpan donasi baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'campaign_id'  => 'required|exists:campaigns,id',
            'donor_name'   => 'required|string|max:255',
            'donor_email'  => 'required|email|max:255',
            'donor_phone'  => ['required', 'string', 'max:20', 'regex:/^(\+62|62|0)[0-9]{8,13}$/'],
            'amount'       => 'required|numeric|min:1000',
            'message'      => 'nullable|string|max:500',
        ], [
            'campaign_id.required'  => 'Campaign tidak valid.',
            'campaign_id.exists'    => 'Campaign tidak ditemukan.',
            'donor_name.required'   => 'Nama donatur wajib diisi.',
            'donor_email.required'  => 'Email wajib diisi.',
            'donor_email.email'     => 'Format email tidak valid.',
            'donor_phone.required'  => 'Nomor WhatsApp wajib diisi.',
            'donor_phone.regex'     => 'Format tidak valid. Contoh: 08123456789',
            'amount.required'       => 'Jumlah donasi wajib diisi.',
            'amount.numeric'        => 'Jumlah donasi harus berupa angka.',
            'amount.min'            => 'Jumlah donasi minimal Rp 1.000.',
            'message.max'           => 'Pesan maksimal 500 karakter.',
        ]);

        $campaign = Campaign::findOrFail($request->campaign_id);

        if ($campaign->status !== 'active') {
            return redirect()
                ->route('campaigns.show', $campaign)
                ->with('error', 'Campaign ini sedang tidak menerima donasi.');
        }

        $donation = Donation::create([
            'campaign_id'    => $campaign->id,
            'donor_name'     => $request->donor_name,
            'donor_email'    => $request->donor_email,
            'donor_phone'    => $request->donor_phone,
            'message'        => $request->message,
            'amount'         => $request->amount,
            'payment_status' => 'pending',
            'mayar_order_id' => null,
        ]);

        return redirect()->route('donations.invoice', $donation);
    }

    /**
     * Menampilkan halaman invoice/slip donasi.
     */
    public function invoice(Donation $donation): View
    {
        $donation->load('campaign');

        $adminPhone = env('ADMIN_WHATSAPP', '6281234567890');

        $waMessage = urlencode(
            "Halo Admin Berkah-Link 👋\n\n" .
            "Saya sudah melakukan donasi dengan detail berikut:\n" .
            "━━━━━━━━━━━━━━━━━━\n" .
            "📋 ID Donasi : #" . str_pad($donation->id, 6, '0', STR_PAD_LEFT) . "\n" .
            "👤 Nama      : " . $donation->donor_name . "\n" .
            "📧 Email     : " . $donation->donor_email . "\n" .
            "💛 Campaign  : " . ($donation->campaign?->title ?? '-') . "\n" .
            "💰 Nominal   : Rp " . number_format($donation->amount, 0, ',', '.') . "\n" .
            "━━━━━━━━━━━━━━━━━━\n" .
            "Mohon konfirmasi pembayaran saya. Terima kasih! 🙏"
        );

        $waLink = "https://wa.me/{$adminPhone}?text={$waMessage}";

        return view('donations.invoice', compact('donation', 'waLink'));
    }

    /**
     * Menampilkan riwayat donasi.
     */
    public function history(): View
    {
    $donations = Donation::with('campaign')
        ->latest()
        ->paginate(15);

    return view('donations.history', compact('donations'));
    }

    /**
     * Admin menandai donasi sebagai lunas & kirim email terima kasih.
     */
    // public function markAsPaid(Donation $donation): RedirectResponse
    // {
    //     // Pastikan hanya admin yang bisa akses (sudah diproteksi di route)
    //     if ($donation->payment_status === 'paid') {
    //         return redirect()->route('admin.index')->with('info', 'Donasi ini sudah berstatus lunas.');
    //     }

    //     // Update status donasi
    //     $donation->update(['payment_status' => 'paid']);

    //     // Tambah current_amount campaign
    //     $donation->campaign->increment('current_amount', $donation->amount);

    //     // Kirim email terima kasih ke donatur
    //     if ($donation->donor_email) {
    //         try {
    //             Mail::to($donation->donor_email)->send(new ThankYouDonation($donation));
    //         } catch (\Exception $e) {
    //             // Jika email gagal, tetap lanjut — jangan sampai error email menghentikan proses
    //             \Log::error('Gagal kirim email terima kasih: ' . $e->getMessage());
    //         }
    //     }

    //     return redirect()
    //         ->route('admin.index')
    //         ->with('success', 'Donasi #' . str_pad($donation->id, 6, '0', STR_PAD_LEFT) . ' berhasil ditandai lunas & email terima kasih terkirim!');
    // }
    public function markAsPaid(Donation $donation): RedirectResponse
{
    if ($donation->payment_status === 'paid') {
        return redirect()->route('admin.index')->with('info', 'Donasi ini sudah berstatus lunas.');
    }

    $donation->update(['payment_status' => 'paid']);
    $donation->campaign->increment('current_amount', $donation->amount);

    // Kirim email tanpa try-catch dulu biar error kelihatan
    Mail::to($donation->donor_email)->send(new ThankYouDonation($donation));

    return redirect()
        ->route('admin.index')
        ->with('success', 'Donasi berhasil ditandai lunas!');
}

    /**
     * Webhook dari Mayar (aktifkan saat production).
     */
    public function webhook(Request $request): \Illuminate\Http\Response
    {
        $signature = $request->header('Authorization');
        if ($signature !== env('MAYAR_WEBHOOK_SECRET')) {
            return response('Unauthorized', 401);
        }

        $payload  = $request->all();
        $donation = Donation::where('mayar_order_id', $payload['id'] ?? '')->first();

        if ($donation && strtoupper($payload['status'] ?? '') === 'PAID') {
            $donation->update(['payment_status' => 'paid']);
            $donation->campaign->increment('current_amount', $donation->amount);

            if ($donation->donor_email) {
                try {
                    Mail::to($donation->donor_email)->send(new ThankYouDonation($donation));
                } catch (\Exception $e) {
                    \Log::error('Gagal kirim email webhook: ' . $e->getMessage());
                }
            }
        }

        return response('OK', 200);
    }
}