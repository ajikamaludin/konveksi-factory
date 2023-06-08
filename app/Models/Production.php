<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

class Production extends Model
{
    public $cascadeDeletes = ['items'];

    protected $fillable = [
        'buyer_id',
        'brand_id',
        'material_id',
        'code',
        'name',
        'description',
        'deadline',
        'sketch_image',
        'is_archive',
    ];

    protected $appends = ['sketch_image_url', 'total', 'reject', 'left', 'active_line'];

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

    public function total(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->items()->sum('target_quantity');
            }
        );
    }

    public function finish(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->items()->sum('finish_quantity');
            }
        );
    }

    public function reject(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->items()->sum('reject_quantity');
            }
        );
    }

    public function left(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->total - $this->reject - $this->finish;
            }
        );
    }

    public function activeLine(): Attribute
    {
        return Attribute::make(
            get: function () {
                $item = $this->items()->orderBy('updated_at', 'desc')->first();
                if ($item != null) {
                    return $item->editor?->name;
                }

                return '';
            }
        );
    }

    public function cuttings()
    {
        return $this->hasMany(Cutting::class);
    }
}
