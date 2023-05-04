<?php

namespace App\Http\Controllers;

use App\Models\Cutting;
use App\Models\UserCutting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserCuttingController extends Controller
{
    //
    public function index(Request $request)
    {
        $userCutting = null;

        if ($request->production_id != '' && $request->ratio_id != '' && $request->fabric_item_id != '') {
            $userCutting = UserCutting::with('userCuttingItem.creator')->where([
                ['artikel_id', '=', $request->production_id],
                ['ratio_id', '=', $request->ratio_id],
                ['fabric_item_id', '=', $request->fabric_item_id],
            ])
                ->get();
        }

        return inertia('UserCutting/Index', [
            'userCutting' => $userCutting,

        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'ratio_id' => 'required|exists:ratios,id',
            'production_id' => 'required|exists:productions,id',
            'fabric_item_id' => 'required|exists:fabric_items,id',
            'total_po' => 'required|numeric|gt:0',
            'items' => 'required|array',
            'items.*.quantity' => 'required|numeric',
            'items.*.detail_fabric' => 'required|array',
            'items.*.detail_fabric.id' => 'required|exists:detail_fabrics,id',
        ]);
        $userCutting = UserCutting::with('userCuttingItem.creator')->where([
            ['artikel_id', '=', $request->production_id],
            ['ratio_id', '=', $request->ratio_id],
            ['fabric_item_id', '=', $request->fabric_item_id],
        ])
            ->get();

        DB::beginTransaction();
        $userCatting = UserCutting::create([
            'ratio_id' => $request->ratio_id,
            'artikel_id' => $request->production_id,
            'fabric_item_id' => $request->fabric_item_id,
        ]);
        $total_po = 0;
        $result_quantity = 0;
        $consumsion = 0;
        $total_qty = 0;
        if (count($userCutting) > 0) {
            foreach ($userCutting as $cutting) {
                foreach ($cutting['userCuttingItem'] as $cuttingItem) {
                    $result_quantity = $cuttingItem['qty'] + $result_quantity;
                    $total_po = $cuttingItem['fritter'];
                    $total_qty = $total_qty + $cuttingItem['qty'];
                }
            }
        } else {
            $total_po = $request->total_po;
        }

        foreach ($request->items as $item) {
           
            $result_quantity = $item['total_qty'] + $result_quantity;
            $qty_fabric = $item['detail_fabric']['qty'];
            $total_po = $total_po - $item['total_qty'];
           $cutingItem=$userCatting->userCuttingItem()->create([
                'qty_fabric' => $qty_fabric,
                'qty_sheet' => $item['quantity'],
                'qty' => $item['total_qty'],
                'fritter' => $total_po,
            ]);
            $total_qty = $total_qty + $qty_fabric;
           
        }
        $consumsion = $total_qty / $result_quantity;
        Cutting::where('production_id', $request->production_id)->update([
            'result_quantity' => $result_quantity,
            'fritter_quantity' => $total_po,
            'consumsion' => $consumsion,
        ]);
        DB::commit();

        session()->flash('message', ['type' => 'success', 'message' => 'Item has beed saved']);
        return redirect()->route('user-cutting.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has beed saved']);
    }
}