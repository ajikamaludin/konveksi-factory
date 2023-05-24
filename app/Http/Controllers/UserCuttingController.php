<?php

namespace App\Http\Controllers;

use App\Models\Cutting;
use App\Models\DetailFabric;
use App\Models\Production;
use App\Models\UserCutting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserCuttingController extends Controller
{
    //
    public function index(Request $request)
    {
        $userCutting = null;
        $cutting = null;
        if ($request->production_id != '' && $request->ratio_id != '' && $request->fabric_item_id != '' && $request->cutting_id != '') {
            $userCutting = UserCutting::with('userCuttingItem.creator')->where([
                // ['artikel_id', '=', $request->production_id],
                // ['ratio_id', '=', $request->ratio_id],
                // ['fabric_item_id', '=', $request->fabric_item_id],
                ['cutting_id', '=', $request->cutting_id],
            ])->get();

            $cutting = Cutting::where('id', $request->cutting_id)->with(['buyer'])->orderBy('created_at', 'desc')->first();
        }

        return inertia('UserCutting/Form', [
            'userCutting' => $userCutting,
            'cutting' => $cutting,

        ]);
    }

    public function store(Request $request, Cutting $cutting)
    {
        $request->validate([
            'ratio_id' => 'required|exists:ratios,id',
            'production_id' => 'required|exists:productions,id',
            'fabric_item_id' => 'required|exists:fabrics,id',
            'total_po' => 'required|numeric|gt:0',
            'items' => 'required|array',
            'items.*.quantity' => 'required|numeric',
            // 'items.*.detail_fabric' => 'required|array',
            // 'items.*.detail_fabric.id' => 'required|exists:detail_fabrics,id',
        ]);
        $cutting_id=$cutting->id;
        $userCutting = UserCutting::with('userCuttingItem.creator')->where([
            ['cutting_id', '=', $cutting_id],
        ])->get();
        DB::beginTransaction();
        try {
            $userCatting = UserCutting::create([
                'ratio_id' => $request->ratio_id,
                'artikel_id' => $request->production_id,
                'fabric_item_id' => $request->fabric_item_id,
                'cutting_id' => $cutting->id,
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
                $total_po = $request->fritter_quantity;
            }
           
            foreach ($request->items as $item) {
                // dd( $item['total_qty'],$request,$total_po);
                $result_quantity = $item['total_qty'] + $result_quantity;
                $qty_fabric = $item['qty'];
                $total_po = $total_po - $item['total_qty'];
                if ($item['total_qty'] > 0) {
                    $userCatting->userCuttingItem()->create([
                        'qty_fabric' => $qty_fabric,
                        'qty_sheet' => $item['quantity'],
                        'qty' => $item['total_qty'],
                        'fritter' => $request->fritter_quantity,
                        'lock' => 1,
                        'fabric_item_id' => $item['fabric_item_id']
                    ]);
                    $total_qty = $total_qty + $qty_fabric;

                    if ($item['result_qty'] > 0) {
                        DetailFabric::where('id', $item['id'])->update([
                            'fritter' => $item['fritter'] - $item['fritter_item'],
                            'result_qty' => $item['quantity'] + $item['result_qty']
                        ]);
                    }
                }
            }
            if ($result_quantity == 0) {
                $result_quantity = 1;
            }

            $consumsion = $total_qty / $result_quantity;
           
           
            Cutting::where('id', $cutting_id)->update([
                'result_quantity' => $result_quantity,
                'fritter_quantity' => $request->fritter_quantity,
                'consumsion' => $consumsion,
                'lock' => '1',
            ]);
            DB::commit();
           
            session()->flash('message', ['type' => 'success', 'message' => 'Item has beed saved']);
        } catch (\Exception $e) {
            dd($e);
            session()->flash('message', ['type' => 'Failed', 'message' => 'Data Is Not Valid unable to Save']);
            DB::rollBack();
        }
    }
}
