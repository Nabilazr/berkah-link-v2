<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CampaignController extends Controller
{
    public function __construct()
{
    $this->middleware('auth')->except(['index', 'show']);
}
    /**
     * Menampilkan semua campaign.
     */
    public function index(): View
    {
        
        $campaigns = Campaign::with('user')
            ->withCount('donations')
            ->latest()
            ->paginate(10);

        return view('campaigns.index', compact('campaigns'));
    }

    /**
     * Menampilkan form tambah campaign baru.
     */
    public function create(): View
    {
        return view('campaigns.create');
    }

    /**
     * Menyimpan campaign baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'target_amount' => 'required|numeric|min:1',
            'status'        => 'required|in:draft,active,completed,cancelled',
        ], [
            'title.required'         => 'Judul campaign wajib diisi.',
            'title.max'              => 'Judul campaign maksimal 255 karakter.',
            'description.required'   => 'Deskripsi campaign wajib diisi.',
            'target_amount.required' => 'Target donasi wajib diisi.',
            'target_amount.numeric'  => 'Target donasi harus berupa angka.',
            'target_amount.min'      => 'Target donasi minimal 1.',
            'status.required'        => 'Status campaign wajib dipilih.',
            'status.in'              => 'Status tidak valid.',
        ]);

        Campaign::create([
            'user_id'        => Auth::id(),
            'title'          => $request->title,
            'slug'           => Str::slug($request->title) . '-' . Str::random(6),
            'description'    => $request->description,
            'target_amount'  => $request->target_amount,
            'current_amount' => 0,
            'status'         => $request->status,
        ]);

        return redirect()
            ->route('campaigns.index')
            ->with('success', 'Campaign berhasil dibuat!');
    }

    /**
     * Menampilkan detail campaign beserta donasi-donasinya.
     */
    public function show(Campaign $campaign): View
    {
        $campaign->load(['user', 'donations']);

        return view('campaigns.show', compact('campaign'));
    }

    /**
     * Menampilkan form edit campaign.
     */
    public function edit(Campaign $campaign): View
    {
        $this->authorizeOwner($campaign);

        return view('campaigns.edit', compact('campaign'));
    }

    /**
     * Menyimpan perubahan data campaign.
     */
    public function update(Request $request, Campaign $campaign): RedirectResponse
    {
        $this->authorizeOwner($campaign);

        $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'target_amount' => 'required|numeric|min:1',
            'status'        => 'required|in:draft,active,completed,cancelled',
        ], [
            'title.required'         => 'Judul campaign wajib diisi.',
            'title.max'              => 'Judul campaign maksimal 255 karakter.',
            'description.required'   => 'Deskripsi campaign wajib diisi.',
            'target_amount.required' => 'Target donasi wajib diisi.',
            'target_amount.numeric'  => 'Target donasi harus berupa angka.',
            'target_amount.min'      => 'Target donasi minimal 1.',
            'status.required'        => 'Status campaign wajib dipilih.',
            'status.in'              => 'Status tidak valid.',
        ]);

        // Regenerate slug hanya jika title berubah
        $slug = $campaign->slug;
        if ($campaign->title !== $request->title) {
            $slug = Str::slug($request->title) . '-' . Str::random(6);
        }

        $campaign->update([
            'title'         => $request->title,
            'slug'          => $slug,
            'description'   => $request->description,
            'target_amount' => $request->target_amount,
            'status'        => $request->status,
        ]);

        return redirect()
            ->route('campaigns.show', $campaign)
            ->with('success', 'Campaign berhasil diperbarui!');
    }

    /**
     * Menghapus campaign dari database.
     */
    public function destroy(Campaign $campaign): RedirectResponse
    {
        $this->authorizeOwner($campaign);

        $campaign->delete();

        return redirect()
            ->route('campaigns.index')
            ->with('success', 'Campaign berhasil dihapus!');
    }

    /**
     * Pastikan hanya pemilik campaign yang bisa edit/hapus.
     */
    private function authorizeOwner(Campaign $campaign): void
    {
        if (Auth::id() !== $campaign->user_id) {
            abort(403, 'Anda tidak memiliki akses ke campaign ini.');
        }
    }
}