<?php

namespace App\Models;


class CuttingItem extends Model
{
    protected $fillable = [
        'cutting_id',
        'size_id',
        'qty',
    ];

    public function size(){
        return $this->belongsTo(Size::class);
    }
}
