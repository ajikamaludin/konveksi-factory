<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\FinishingItemResults;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\Production;
use App\Models\ProductionItem;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class FinishingController extends Controller
{
    //
    public function index(Request $request)
    {
        $production = null;
        $color = null;
        $size = null;
        $item = null;
        $operator = 0;
        $results = null;

        if ($request->production_id != '' && $request->color_id != '' && $request->size_id != '') {
            $production = Production::find($request->production_id);
            $color = Color::find($request->color_id);
            $size = Size::find($request->size_id);

            $item = ProductionItem::with(['finishingresults.creator'])->where([
                ['production_id', '=', $request->production_id],
                ['color_id', '=', $request->color_id],
                ['size_id', '=', $request->size_id],
            ])->first();
        }
        $dataNow = date('Y-m-d');

        $results = FinishingItemResults::Select(
            'productions.id',
            'productions.name as name',
            DB::raw('DATE(input_at) as input_at')
        )
            ->join('production_items', 'production_items.id', '=', 'finishing_item_results.production_item_id')
            ->join('productions', 'productions.id', '=', 'production_items.production_id')
            ->groupBy(DB::raw('DATE(input_at)'))->get();
        return inertia('Finishing/Index', [
            'item' => $item,
            '_production' => $production,
            '_color' => $color,
            '_size' => $size,
            // 'operator' => $operator,
            'results' => $results,
        ]);
    }

    public function store(Request $request, ProductionItem $item)
    {
        $request->validate([
            'finish_quantity' => 'required|numeric',
            // 'qty' => 'required|numeric',
        ]);
        DB::beginTransaction();

        $qtyinput = $request->finish_quantity + $request->reject_quantity;
        $lastqty = $item->target_quantity - $item->finish_quantity + $item->reject_quantity;
        if ($qtyinput <= $lastqty) {
            $date = date('Y-m-d');
            $item->update([
                'result_quantity_finishing' => $item->result_quantity_finishing + $request->finish_quantity,
                'reject_quantity_finishing' => $item->reject_quantity_finishing + $request->reject_quantity,
            ]);

            $resultItem = $item->finishingresults()->create([
                'input_at' => now(),
                'finish_quantity' => $request->finish_quantity,
                'reject_quantity' => $request->reject_quantity,
            ]);


            DB::commit();
            session()->flash('message', ['type' => 'success', 'message' => 'Item has beed saved']);
        } else {
            session()->flash('message', ['type' => 'Faield', 'message' => 'Your Quantity is more than PO']);
        }
    }

    public function export(String $finishing)
    {
        $exports = [
            ['Tanggal', 'Artikel', 'Result', 'Reject']
        ];
        $results = FinishingItemResults::Select(
            'productions.id',
            'productions.name as name',
            DB::raw('DATE(input_at) as input_at'),'production_items.id as production_item_id'
        )->with('productionItem.product')
            ->join('production_items', 'production_items.id', '=', 'finishing_item_results.production_item_id')
            ->join('productions', 'productions.id', '=', 'production_items.production_id')
            ->groupBy('productions.id', DB::raw('DATE(input_at)'))->whereDate('input_at', $finishing)->get();
            
        foreach ($results as $result) {
          $products=[
            $finishing,
            $result['name'],
          ];
          $production_items=FinishingItemResults::with('productionItem.size')->whereDate('input_at', $finishing)->get();
          foreach($production_items as $item){
          if($result['id']==$item['productionItem']['production_id']){
            array_push($products,$item['productionItem']['size']['name'],$item['finish_quantity'],$item['reject_quantity']);
          }
           
          }
          $exports[]=$products;
        }
     
        return (new FastExcel($exports))
            ->withoutHeaders()
            ->download("Finishing-$finishing.xlsx");
    }
}
