<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Production;
use App\Models\ProductionItem;
use App\Models\Size;
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
        if($request->production_id != '' && $request->color_id != '' &&  $request->size_id != '') {
            $production = Production::find($request->production_id);
            $color = Color::find($request->color_id);
            $size = Size::find($request->size_id);
            
            $item = ProductionItem::with(['results.creator'])->where([
                ['production_id', '=', $request->production_id],
                ['color_id', '=', $request->color_id],
                ['size_id', '=', $request->size_id],
            ])->first();
        }

        return inertia('LineSewing/Index', [
            'item' => $item,
            '_production' => $production,
            '_color' => $color,
            '_size' => $size,
        ]);
    }

    public function store(Request $request, ProductionItem $item)
    {
        DB::beginTransaction();
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

        DB::commit();

        session()->flash('message', ['type' => 'success', 'message' => 'Item has beed saved']);

    }
}
