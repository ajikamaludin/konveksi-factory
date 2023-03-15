<?php

namespace App\Models;

class Production extends Model
{
    protected $fillable = [
        'buyer_id',
        'brand_id',
        'material_id',
        'code',
        'name',
        'description',
        'deadline',
        'sketch_image',
    ];
}
