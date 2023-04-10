<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;


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

    public function getTotalDetailFabric(){
        return $this->DetailFabric
       ->sum('qty')
        ;
    }
}
