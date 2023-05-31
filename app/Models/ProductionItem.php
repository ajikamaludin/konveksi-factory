<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

class ProductionItem extends Model
{
    public $cascadeDeletes = ['results'];

    protected $fillable = [
        'production_id',
        'size_id',
        'color_id',
        'target_quantity',
        'finish_quantity',
        'reject_quantity',
        'result_quantity_finishing',
        'reject_quantity_finishing',
        'lock',
    ];

    protected $appends = ['left_quantity','leftfinishing_quantity'];

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

    public function finishingresults()
    {
        return $this->hasMany(FinishingItemResults::class);
    }

    public function leftQuantity(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->target_quantity - $this->finish_quantity - $this->reject_quantity,
        );
    }
    public function leftfinishingQuantity(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->target_quantity - $this->result_quantity_finishing - $this->reject_quantity_finishing,
        );
    }
}
