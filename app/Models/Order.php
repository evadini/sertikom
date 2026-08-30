<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'order_code',
        'snap_token',
        'payment_url',
        'ticket_type',
        'quantity',
        'price_per_ticket',
        'total_amount',
        'ticket_items',
        'status',
        'payment_type',
        'transaction_id',
        'paid_at',
        'expired_at',
    ];

    protected $casts = [
        'paid_at'    => 'datetime',
        'expired_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'price_per_ticket' => 'decimal:2',
        'ticket_items' => 'array',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Generate kode order unik, contoh: ORD-20260604-A3X9
     */
    public static function generateOrderCode(): string
    {
        do {
            $code = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        } while (self::where('order_code', $code)->exists());

        return $code;
    }
}
