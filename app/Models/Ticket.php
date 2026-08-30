<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

   protected $fillable = [
    'user_id',
    'event_id',
    'order_id',
    'ticket_type',
    'price',
    'stock',
    'status',
    'purchase_date',
    'date_used',
    'qr_code',
    'is_attended',
    'attended_at',
];

    protected $casts = [
        'purchase_date' => 'datetime',
        'date_used' => 'datetime',
        'attended_at' => 'datetime',
        'is_attended' => 'boolean',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Relasi ke Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
