<?php

namespace App\Models;

class Fabric extends Model
{
    public $cascadeDeletes = ['fabricItems'];

    protected $fillable = [
        'name',
        'order_date',
        'letter_number',
        'composisi',
        'setting_size',
        'supplier_id',
    ];

    public function fabricItems()
    {
        return $this->hasMany(FabricItem::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
