<?php

namespace App\Models;


class Ratio extends Model
{
    public $cascadeDeletes = ['detailsRatio'];
    protected $fillable = [
        'name',
    ];

    public function detailsRatio(){
        return $this->hasMany(DetailRatio::class);
    }
}
