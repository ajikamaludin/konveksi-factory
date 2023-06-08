<?php

namespace App\Models;

class CuttingItem extends Model
{
    protected $fillable = [
        'cutting_id',
        'size_id',
        'color_id',
        'qty',
        'lock',
    ];

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }
}
