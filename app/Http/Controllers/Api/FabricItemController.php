<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fabric;
use App\Models\FabricItem;
use Illuminate\Http\Request;

class FabricItemController extends Controller
{
    public function index(Request $request)
    {
        $query = FabricItem::query()->with('detailFabrics');

        if ($request->q) {
            $query->where('name', 'like', "%{$request->q}%");
        }
        if ($request->fabric_id){
            $query->where('fabric_id',$request->fabric_id);
        }

        if($request->fabric_item_id){
            $query->where('id',$request->fabric_item_id);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }
    public function fabric(Request $request)
    {
        $query = Fabric::query()->with('fabricItems.detailFabrics')->where('is_archive','=','0');
        
        if ($request->q) {
            $query->where('name', 'like', "%{$request->q}%");
        }

        return $query->orderBy('created_at', 'desc')->get();
    }
}
