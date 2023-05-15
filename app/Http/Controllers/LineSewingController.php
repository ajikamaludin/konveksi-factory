<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Operator;
use App\Models\Production;
use App\Models\ProductionItem;
use App\Models\Size;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LineSewingController extends Controller
{
    public function index(Request $request)
    {
        $production = null;
        $color = null;
        $size = null;
        $item = null;
        $operator=0;
        

        if ($request->production_id != '' && $request->color_id != '' && $request->size_id != '') {
            $production = Production::find($request->production_id);
            $color = Color::find($request->color_id);
            $size = Size::find($request->size_id);

            $item = ProductionItem::with(['results.creator'])->where([
                ['production_id', '=', $request->production_id],
                ['color_id', '=', $request->color_id],
                ['size_id', '=', $request->size_id],
            ])->first();
       
            if (count($item->results)>0){
                $operator = Operator::where(['input_date' => $item->results[0]['input_at']])->orderBy('input_date', 'desc')->first()?->qty;
            }
            
        } elseif ($request->production_id != '' && $request->size_id != '') {
            $production = Production::find($request->production_id);
            $size = Size::find($request->size_id);
            $item = ProductionItem::with(['results.creator'])->where([
                ['production_id', '=', $request->production_id],

                ['size_id', '=', $request->size_id],
            ])->first();
        }

        return inertia('LineSewing/Index', [
            'item' => $item,
            '_production' => $production,
            '_color' => $color,
            '_size' => $size,
            'operator'=>$operator,
        ]);
    }

    public function store(Request $request, ProductionItem $item)
    {
        $request->validate([
            'finish_quantity' => 'required|numeric',
            'qty' => 'required|numeric',
        ]);
        DB::beginTransaction();

        $qtyinput = $request->finish_quantity + $request->reject_quantity;
        $lastqty = $item->target_quantity - $item->finish_quantity + $item->reject_quantity;
        if ($qtyinput <= $lastqty) {
            $date = Carbon::now()->toDateString();
            $item->update([
                'lock' => 1,
                'finish_quantity' => $item->finish_quantity + $request->finish_quantity,
                'reject_quantity' => $item->reject_quantity + $request->reject_quantity,
            ]);

            $resultItem = $item->results()->create([
                'input_at' => now(),
                'finish_quantity' => $request->finish_quantity,
                'reject_quantity' => $request->reject_quantity,
            ]);
            Operator::where('input_date', $date)->updateOrCreate([
                'qty' => $request->qty,
                'input_date' => $resultItem->input_at,

            ]);

            DB::commit();
            session()->flash('message', ['type' => 'success', 'message' => 'Item has beed saved']);
        } else {
            session()->flash('message', ['type' => 'Faield', 'message' => 'Your Quantity is more than PO']);
        }

    }
}
