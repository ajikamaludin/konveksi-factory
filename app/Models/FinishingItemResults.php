<?php

namespace App\Models;

class FinishingItemResults extends Model
{
    protected $fillable = [
        'production_item_id',
        'finish_quantity',
        'reject_quantity',
        'input_at',
    ];

    public function productionItem()
    {
        return $this->belongsTo(ProductionItem::class);
    }
}
