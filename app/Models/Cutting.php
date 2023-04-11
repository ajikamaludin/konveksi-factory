<?php

namespace App\Models;



class Cutting extends Model
{
    public $cascadeDeletes = ['cuttingItems'];
    protected $fillable = [
        'buyer_id',
        'brand_id',
        'material_id',
        'style',
        'name',
        'consumsion',
        'deadline',
    ];
    public function cuttingItems(){
        return $this->hasMany(CuttingItem::class);
    }
}
