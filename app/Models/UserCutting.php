<?php

namespace App\Models;

class UserCutting extends Model
{
    protected $fillable = [
        'fabric_item_id',
        'artikel_id',
        'ratio_id',
    ];

    public function userCuttingItem()
    {
        return $this->hasMany(UserCuttingItem::class);
    }
}
