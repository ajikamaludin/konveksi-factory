<?php

namespace App\Http\Controllers;

use App\Models\UserCutting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserCuttingController extends Controller
{
    //
    public function index(Request $request){
        $userCutting=null;
      
        if($request->production_id != '' && $request->ratio_id != '' &&  $request->fabric_item_id != '') {
            $userCutting =UserCutting::with('userCuttingItem.creator')->where([
                ['artikel_id', '=', $request->production_id],
                ['ratio_id', '=', $request->ratio_id],
                ['fabric_item_id', '=', $request->fabric_item_id],
            ])->get();  
        }
        
        return inertia('UserCutting/Index', [
            '_userCutting' => $userCutting,
            
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'ratio_id' => 'required|exists:ratios,id',
            'production_id' => 'required|exists:productions,id',
            'fabric_item_id' => 'required|exists:fabric_items,id',
            'total_po'=>'required|numeric|gt:0',
            'fritter'=>'required|numeric|gt:0',
            'items'=>'required|array',
            'items.*.quantity' => 'required|numeric',
            'items.*.detail_fabric' => 'required|array',
            'items.*.detail_fabric.id' => 'required|exists:detail_fabrics,id',
        ]);
        DB::beginTransaction();
        $userCatting=UserCutting::create([
            'ratio_id' => $request->ratio_id,
            'artikel_id' => $request->production_id,
            'fabric_item_id' => $request->fabric_item_id,
        ]);
        $total_po=$request->fritter;
        foreach ($request->items as $item) {
            $qty_fabric=$item['detail_fabric']['qty'];
            $total_po=$total_po-$item['total_qty'];
            $userCatting->userCuttingItem()->create([
                'qty_fabric' => $qty_fabric,
                'qty_sheet'=>$item['quantity'],
                'qty'=>$item['total_qty'],
                'fritter'=>$total_po,
            ]);
        }
        DB::commit();

        session()->flash('message', ['type' => 'success', 'message' => 'Item has beed saved']);
        // return redirect()->route('user-cutting.index')
        // ->with('message', ['type' => 'success', 'message' => 'Item has beed saved']);
    }
}
