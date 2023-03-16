<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

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

    protected $appends = ['sketch_image_url'];

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

    public function items()
    {
        return $this->hasMany(ProductionItem::class);
    }

    public function sketchImageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                return asset($this->sketch_image);
            }
        );
    }
}
