<?php

namespace App\Models;

class Operator extends Model
{
    protected $fillable = [
        'qty',
        'input_date',
        'production_item_id',
        'created_by'
    ];
}
