<?php

namespace App\Http\Controllers;

use App\Models\Cutting;
use App\Models\Fabric;
use App\Models\FabricItem;
use App\Models\Production;
use App\Models\ProductionItem;
use App\Models\ProductionItemResult;
use App\Models\Ratio;
use App\Models\UserCutting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class CuttingController extends Controller
{
    public function index(Request $request)
    {
        $query = Cutting::query()->with('cuttingItems.size', 'cuttingItems.color', 'creator');

        return inertia('Cutting/Index', [
            'query' => $query->paginate(10),
        ]);
    }

    public function create()
    {
        return inertia('Cutting/Form', []);
    }

    public function store(Request $request)
    {

        $request->validate([
            'style' => 'required|string',
            'name' => 'required|string',
            'buyer_id' => 'nullable|exists:buyers,id',
            'brand_id' => 'nullable|exists:brands,id',
            'material_id' => 'nullable|exists:materials,id',
            'deadline' => 'nullable|date',
            'items' => 'required|array',
            'items.*.size_id' => 'required|exists:sizes,id',
            'items.*.color_id' => 'required|exists:colors,id',
            'items.*.qty' => 'required|numeric',
        ]);

        DB::beginTransaction();
        $production = Production::create([
            'buyer_id' => $request->buyer_id,
            'brand_id' => $request->brand_id,
            'material_id' => $request->material_id,
            'code' => $request->style,
            'name' => $request->name,
            'deadline' => $request->deadline,
        ]);
        $fritter_quantity=0;
        foreach ($request->items as $item) {
            $fritter_quantity=$fritter_quantity+$item['qty'];
        }

        $cutting = $production->cuttings()->create([
            'buyer_id' => $request->buyer_id,
            'brand_id' => $request->brand_id,
            'material_id' => $request->material_id,
            'style' => $request->style,
            'name' => $request->name,
            'deadline' => $request->deadline,
            'fritter_quantity'=>$fritter_quantity
        ]);

        foreach ($request->items as $item) {
            $cutting->cuttingItems()->create([
                'size_id' => $item['size_id'],
                'color_id' => $item['color_id'],
                'qty' => $item['qty'],

            ]);
            $production->items()->create([
                'size_id' => $item['size_id'],
                'color_id' => $item['color_id'],
                'target_quantity' => $item['qty'],
            ]);
        }
        DB::commit();

        return redirect()->route('cutting.index')
            ->with('message', ['type' => 'success', 'message' => 'Ratio has beed saved']);
    }

    public function edit(Cutting $cutting)
    {

        return inertia('Cutting/Form', [
            'cutting' => $cutting->load(['cuttingItems.size', 'cuttingItems.color']),
        ]);
    }

    public function update(Request $request, Cutting $cutting)
    {
        $request->validate([
            'style' => 'required|string',
            'name' => 'required|string',
            'buyer_id' => 'nullable|exists:buyers,id',
            'brand_id' => 'nullable|exists:brands,id',
            'material_id' => 'nullable|exists:materials,id',
            'deadline' => 'nullable|date',
            'items' => 'required|array',
            'items.*.size_id' => 'required|exists:sizes,id',
            'items.*.color_id' => 'required|exists:colors,id',
            'items.*.qty' => 'required|numeric',
        ]);
       
        $production=Production::find($cutting->production_id)->load('items');
        DB::beginTransaction();
        $production->update([
            'buyer_id' => $request->buyer_id,
            'brand_id' => $request->brand_id,
            'material_id' => $request->material_id,
            'code' => $request->style,
            'name' => $request->name,
            'deadline' => $request->deadline,
        ]);
        $production->items()->where('lock', 0)->delete();
        $cutting->cuttingItems()->where('lock','0')->delete();
        $fritter_quantity=0;
        foreach ($request->items as $item) {
           
            $fritter_quantity=$fritter_quantity+$item['qty'];
        }
        if ($cutting->result_quantity>0){
            $fritter_quantity=$fritter_quantity-$cutting->result_quantity;
        }
      
        $cutting->update([
            'buyer_id' => $request->buyer_id,
            'brand_id' => $request->brand_id,
            'material_id' => $request->material_id,
            'style' => $request->style,
            'name' => $request->name,
            'deadline' => $request->deadline,
            'fritter_quantity'=>$fritter_quantity
        ]);
       
        foreach ($request->items as $item) {
            $cutting->cuttingItems()->create([
                'size_id' => $item['size_id'],
                'color_id' => $item['color_id'],
                'qty' => $item['qty'],
            ]);
    
            $production->items()->create([
                'size_id' => $item['size_id'],
                'color_id' => $item['color_id'],
                'target_quantity' => $item['qty'],
            ]);
            
        }

        DB::commit();

        return redirect()->route('cutting.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has beed saved']);
    }

    public function destroy(Cutting $cutting)
    {

        $production = Production::where('id', $cutting->production_id)->first();
        DB::beginTransaction();
        $itemIds = $production->items()->pluck('id')->toArray();
        ProductionItemResult::whereIn('production_item_id', $itemIds)->delete();

        $production->items()->delete();
        $production->delete();
        $cutting->delete();
        DB::commit();

        session()->flash('message', ['type' => 'success', 'message' => 'Item has beed deleted']);
    }

    public function export(Cutting $cutting)
    {
        $userCutting = UserCutting::with('userCuttingItem.creator')->where('artikel_id', $cutting?->production_id)->first();

        $fabricItem = FabricItem::where('id', $userCutting?->fabric_item_id)->first();
        $supplier = Fabric::with('supplier')->where('id', $fabricItem?->fabric_id)->first();
        $ratios = Ratio::with('detailsRatio.size')->where('id', $userCutting?->ratio_id)->first();
        $sizes = ['', '', '', ''];
        $space = ['User', 'Lot', 'Kain', 'Hasil Cutting'];
        if ($ratios != null) {
            foreach ($ratios?->detailsRatio as $ratio) {
                array_push($sizes, $ratio?->size?->name);
                array_push($space, '');
            }
        }
        array_push($space, 'Total', 'Konsumsi');
        $exports = [
            ['Style', 'Nama', 'Pembeli', 'Deadline', 'Bahan', 'Brand', 'Supplier Kain'],
            [
                $cutting?->style,
                $cutting?->name,
                $cutting?->buyer?->name,
                $cutting?->deadline,
                $cutting?->material?->name,
                $cutting?->brand?->name,
                $supplier?->supplier?->name,
            ],
            [],
            [],
            $space,
        ];

        $exports[] = $sizes;
        $total_kain = 0;
        $arrcutting = [];
        $total_konsumsi = 0;
        $total_qty = 0;
        $total_cutting = 0;
        $count = 0;
        if ($userCutting != null) {
            foreach ($userCutting->userCuttingItem as $item) {
                $count++;
                $items = [
                    $item?->creator?->name,
                    $fabricItem?->code, $item?->qty_fabric,
                    '',
                ];

                foreach ($ratios->detailsRatio as $ratio) {
                    array_push(
                        $items,
                        $item?->qty_sheet * $ratio?->qty
                    );
                    $detail = [
                        $item?->qty,
                        $item?->qty_fabric / $item?->qty,
                    ];
                }
                $total_cutting += $item->qty_sheet;
                $s = array_merge($items, $detail);
                $exports[] = $s;
                $total_kain += $item?->qty_fabric;
                $total_qty += $item?->qty;
                $total_konsumsi +=$item?->qty_fabric / $item?->qty;
            }
        }
        if ($ratios != null) {
            foreach ($ratios->detailsRatio as $ratio) {
                array_push($arrcutting, $total_cutting * $ratio?->qty);
            }
        }
        $t = [
            'Total',
            '',
            $total_kain,
            '',
        ];
        if ($count == 0) {
            $count = 1;
        }
        $a = array_merge($t, $arrcutting, [$total_qty, $total_konsumsi / $count]);
        $exports[] = $a;
        $arrsisa = [];
       
        foreach ($cutting->cuttingItems as $index => $val) {
            $substract=0;
            if(count($arrcutting)){
                $substract=$arrcutting[$index];
            }
            array_push($arrsisa, $val->qty - $substract);
            
        }
        $sisa = array_merge(['Sisa PO', '', '', ''], $arrsisa, [$cutting?->fritter_quantity]);
        $exports[] = $sisa;

        $now = now()->format('d-m-Y');
       
        return (new FastExcel($exports))
            ->withoutHeaders()
            ->download("Cutting-$cutting->name-$now.xlsx");
    }
}