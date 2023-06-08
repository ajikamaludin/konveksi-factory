<?php

namespace App\Models;

class FabricItem extends Model
{
    public $cascadeDeletes = ['detailFabrics'];

    protected $fillable = [
        'code',
        'name',
        'fabric_id',

    ];

    public function detailFabrics()
    {
        return $this->hasMany(DetailFabric::class);
    }

    public function first_detail()
    {
        return $this->hasOne(DetailFabric::class)->oldestOfMany();
    }

    public function getTotalDetailFabric()
    {
        return $this->DetailFabric
            ->sum('qty');
    }
}
