<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $user = Auth::user();

        // Campaign milik user
        $campaigns = Campaign::where('user_id', $user->id)
            ->withCount('donations')
            ->latest()
            ->get();

        // Donasi yang masuk ke semua campaign milik user
        $campaignIds = $campaigns->pluck('id');

        $recentDonations = Donation::with('campaign')
            ->whereIn('campaign_id', $campaignIds)
            ->latest()
            ->take(10)
            ->get();

        $stats = [
            'total_campaigns'  => $campaigns->count(),
            'active_campaigns' => $campaigns->where('status', 'active')->count(),
            'total_donations'  => Donation::whereIn('campaign_id', $campaignIds)->count(),
            'total_amount'     => Donation::whereIn('campaign_id', $campaignIds)->where('payment_status', 'paid')->sum('amount'),
        ];

        return view('dashboard', compact('campaigns', 'recentDonations', 'stats'));
    }
}