<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminController extends Controller
{
    /**
     * Halaman utama dashboard admin.
     */
    public function index(): View
    {
        $stats = [
            'total_campaigns'  => Campaign::count(),
            'active_campaigns' => Campaign::where('status', 'active')->count(),
            'total_donations'  => Donation::count(),
            'total_amount'      => Donation::where('payment_status', 'paid')->sum('amount'),
            'pending_donations' => Donation::where('payment_status', 'pending')->count(),
            'total_users'      => User::count(),
        ];

        $campaigns = Campaign::with(['user', 'donations'])
            ->withCount('donations')
            ->latest()
            ->paginate(10, ['*'], 'campaigns_page');

        $donations = Donation::with('campaign')
            ->latest()
            ->paginate(10, ['*'], 'donations_page');

        return view('admin.index', compact('stats', 'campaigns', 'donations'));
    }

    /**
     * Update status campaign dari admin.
     */
    public function updateCampaignStatus(Request $request, Campaign $campaign): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:draft,active,completed,cancelled',
        ]);

        $campaign->update(['status' => $request->status]);

        return redirect()
            ->route('admin.index')
            ->with('success', 'Status campaign berhasil diperbarui.');
    }

    /**
     * Hapus campaign dari admin.
     */
    public function destroyCampaign(Campaign $campaign): RedirectResponse
    {
        $campaign->delete();

        return redirect()
            ->route('admin.index')
            ->with('success', 'Campaign berhasil dihapus.');
    }

    /**
     * Hapus donasi dari admin.
     */
    public function destroyDonation(Donation $donation): RedirectResponse
    {
        $donation->delete();

        return redirect()
            ->route('admin.index')
            ->with('success', 'Donasi berhasil dihapus.');
    }
}
