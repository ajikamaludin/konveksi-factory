<?php

namespace App\Models;

class UserCuttingItem extends Model
{
    protected $fillable = [
        'qty',
        'qty_sheet',
        'qty_fabric',
        'user_cutting_id',
        'fritter',
    ];
}
