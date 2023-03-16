<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

class ProductionItem extends Model
{
    protected $fillable = [
        'production_id',
        'size_id',
        'color_id',
        'target_quantity',
        'finish_quantity',
        'reject_quantity',
    ];

    protected $appends = ['left_quantity'];

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function results() 
    {
        return $this->hasMany(ProductionItemResult::class);
    }

    public function leftQuantity(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->target_quantity - $this->finish_quantity - $this->reject_quantity,
        );
    }
}
