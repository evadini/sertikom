<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];

    /**
     * Ambil jumlah event yang menggunakan kategori ini.
     */
    public function getEventCountAttribute(): int
    {
        return \App\Models\Event::where('category_id', $this->id)->count();
    }

    public function events() {
    return $this->hasMany(Event::class, 'category_id');
}
}

