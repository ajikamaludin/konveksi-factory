<?php

namespace App\Models;

class ProductionItem extends Model
{
    protected $fillable = [
        'production_id',
        'size_id',
        'color_id',
        'target_quantity',
        'finish_quantity',
        'reject_quantity',
    ];
}
