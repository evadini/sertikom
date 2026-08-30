<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'banner',
        'name',
        'category_id',
        'location',
        'date_start',
        'date_end',
        'time_start',
        'time_end',
        'description',
        'terms',
        'organiser_description',
        'organiser_photo',
        'status',
        'unpublish_reason',
        'unpublished_at',
        'refund_date',
        'refund_location',
        'refund_info',
    ];

    protected $casts = [
        'ticket_types' => 'array',
    ];

    // Hubungan balik ke User (Panitia)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Mendapatkan status display event
     * Jika event sudah lewat tanggalnya, status akan menjadi 'completed'
     * Selain itu, kembalikan status asli dari database
     */
    public function getDisplayStatus()
    {
        // Jika status bukan 'published', kembalikan status aslinya
        if ($this->status !== 'published') {
            return $this->status;
        }

        $eventEnd = Carbon::parse(($this->date_end ?? $this->date_start) . ' ' . $this->time_end);
        if ($eventEnd < Carbon::now()) {
            return 'completed';
        }

        return $this->status;
    }


    public function getDateRangeAttribute()
    {
        if ($this->date_end && $this->date_end !== $this->date_start) {
            return Carbon::parse($this->date_start)->format('d F Y') . ' - ' . Carbon::parse($this->date_end)->format('d F Y');
        }
        return Carbon::parse($this->date_start)->format('d F Y');
    }

    // Relasi ke Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function scopeFilter($query, array $filters)
{
    // Filter Nama Event
    $query->when($filters['search'] ?? null, function ($q, $search) {
        $q->where('name', 'like', '%' . $search . '%');
    });

    // Filter Kategori (Sesuaikan dengan nama field di DB, kemungkinan 'category_id')
    $query->when($filters['category_id'] ?? null, function ($q, $category_id) {
        $q->where('category_id', $category_id);
    });

    $query->when($filters['date'] ?? null, function ($q, $date) {
        $q->where(function ($q) use ($date) {
            $q->whereDate('date_start', '<=', $date)
              ->where(function ($q) use ($date) {
                  $q->whereDate('date_end', '>=', $date)
                    ->orWhereNull('date_end');
              });
        });
    });
}

}
