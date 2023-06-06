<?php

namespace App\Models;

class ProductionItemResult extends Model
{
    protected $fillable = [
        'production_item_id',
        'finish_quantity',
        'reject_quantity',
        'input_at',
    ];

    public function item(){
        return $this->belongsTo(ProductionItem::class,'production_item_id');
    }
}
