<?php

namespace App\Models;


class Ratio extends Model
{
    protected $fillable = [
        'name',
    ];

    public function detailsRatio(){
        return $this->hasMany(DetailRatio::class);
    }
}
