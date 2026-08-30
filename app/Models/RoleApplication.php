<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleApplication extends Model
{
    use HasFactory;

    // Pastikan nama tabelnya sesuai dengan migration
    protected $table = 'role_applications';

    // TAMBAHKAN kolom ukm_id dan nomor_rekening di dalam array ini
    protected $fillable = [
    'user_id',
    'ukm_id',
    'nomor_rekening',
    'alasan_ditolak',
    'status',
];

    /**
     * Hubungan relasi ke model User (Opsional, tapi bagus untuk dimiliki)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Hubungan relasi ke model Ukm (Untuk mengambil nama UKM saat divalidasi)
     */
    public function ukm()
    {
        return $this->belongsTo(Ukm::class, 'ukm_id');
    }
}
