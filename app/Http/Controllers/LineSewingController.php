<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Operator;
use App\Models\Production;
use App\Models\ProductionItem;
use App\Models\Size;
use App\Models\TargetProductions;
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
        $operator = 0;
        $target = 0;
        $dataNow = date('Y-m-d');

        if ($request->production_id != '' && $request->color_id != '' && $request->size_id != '') {
            $production = Production::find($request->production_id);
            $color = Color::find($request->color_id);
            $size = Size::find($request->size_id);

            $item = ProductionItem::with([
                'results' => function ($q) {
                    return $q->orderBy('created_at', 'desc');
                },
                'results.creator',
            ])
                ->where([
                    ['production_id', '=', $request->production_id],
                    ['color_id', '=', $request->color_id],
                    ['size_id', '=', $request->size_id],
                ])->first();
        } elseif ($request->production_id != '' && $request->size_id != '') {
            $production = Production::find($request->production_id);
            $size = Size::find($request->size_id);
            $item = ProductionItem::with(['results.creator'])->where([
                ['production_id', '=', $request->production_id],

                ['size_id', '=', $request->size_id],
            ])->first();
        }
        if ($request->production_id != '') {
            $target = TargetProductions::whereDate('input_at', '=', $dataNow)
                ->where('production_id', '=', $request->production_id)
                ->orderBy('input_at', 'desc')
                ->value('qty') ?? 0;
        }

        $operator = Operator::whereDate('input_date', '=', $dataNow)->orderBy('input_date', 'desc')->value('qty') ?? 0;

        return inertia('LineSewing/Index', [
            'item' => $item,
            '_production' => $production,
            '_color' => $color,
            '_size' => $size,
            'operator' => $operator,
            'target' => $target,
        ]);
    }

    public function store(Request $request, ProductionItem $item)
    {
        $request->validate([
            'finish_quantity' => 'required|numeric',
            'qty' => 'required|numeric',
            'qtytarget' => 'required|numeric',
        ]);
        DB::beginTransaction();

        $qtyinput = $request->finish_quantity + $request->reject_quantity;
        $lastqty = $item->target_quantity - $item->finish_quantity + $item->reject_quantity;
        if ($qtyinput <= $lastqty) {
            $date = date('Y-m-d');
            $item->update([
                'lock' => 1,
                'finish_quantity' => $item->finish_quantity + $request->finish_quantity,
                'reject_quantity' => $item->reject_quantity + $request->reject_quantity,
            ]);

            $item->results()->create([
                'input_at' => now(),
                'finish_quantity' => $request->finish_quantity,
                'reject_quantity' => $request->reject_quantity,
            ]);

            Operator::whereDate('input_date', $date)
                ->updateOrCreate([
                    'input_date' => $date,
                ], [
                    'qty' => $request->qty,
                ]);

            TargetProductions::whereDate('input_at', $date)
                ->where('production_id', '=', $item->production_id)
                ->updateOrCreate([
                    'input_at' => $date,
                    'production_id' => $item->production_id,
                ], [
                    'qty' => $request->qtytarget,
                ]);

            DB::commit();
            session()->flash('message', ['type' => 'success', 'message' => 'Item has beed saved']);
        } else {
            session()->flash('message', ['type' => 'Faield', 'message' => 'Your Quantity is more than PO']);
        }
    }
}
