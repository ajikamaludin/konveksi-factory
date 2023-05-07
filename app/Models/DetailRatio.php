<?php

namespace App\Models;

class DetailRatio extends Model
{
    protected $fillable = [
        'qty',
        'ratio_id',
        'size_id',
    ];

    public function size()
    {
        return $this->belongsTo(Size::class);
    }
}
