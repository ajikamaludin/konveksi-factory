<?php

namespace App\Models;

class Fabric extends Model
{
    public $cascadeDeletes = ['fabricItems'];

    protected $fillable = [
        'name',
        'order_date',
        'letter_number',
        'composisi_id',
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
    public function composition(){
        return $this->belongsTo(Compositions::class);
    }
}
