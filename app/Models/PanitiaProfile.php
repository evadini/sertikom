<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PanitiaProfile extends Model
{
    protected $fillable = ['user_id', 'ukm_id', 'no_rekening'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ukm()
    {
        return $this->belongsTo(Ukm::class, 'ukm_id');
    }
}
