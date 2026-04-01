<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    protected $fillable = [
        'campaign_id',
        'donor_name',
        'donor_email',
        'donor_phone',
        'message',
        'amount',
        'payment_status',
        'mayar_order_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    // Helper: apakah donasi sudah lunas?
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    // Helper: apakah donasi masih pending?
    public function isPending(): bool
    {
        return $this->payment_status === 'pending';
    }
}