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
        'result_quantity',
        'fritter_quantity',
        'production_id'
        
    ];
    public function cuttingItems(){
        return $this->hasMany(CuttingItem::class);
    }
    public function buyer() 
    {
        return $this->belongsTo(Buyer::class);
    }

    public function brand() 
    {
        return $this->belongsTo(Brand::class);
    }

    public function material() 
    {
        return $this->belongsTo(Material::class);
    }
    public function supplier() 
    {
        return $this->belongsTo(Supplier::class);
    }
}
