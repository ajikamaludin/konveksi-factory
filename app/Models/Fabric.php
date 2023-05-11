<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;

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
    public function first_item(){
        return $this->hasOne(FabricItem::class)
        ->join('detail_fabrics','fabric_item_id','fabric_items.id')
        ->select('fabric_items.*',DB::raw('sum(fritter) as fritter'))
        ->where('fritter','>','0')
        ;
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function composition(){
        return $this->belongsTo(Compositions::class);
    }
}
