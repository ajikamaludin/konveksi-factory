<?php

namespace App\Http\Controllers;

use App\Models\Cutting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CuttingController extends Controller
{
    public function index(Request $request){
        $query =Cutting::query()->with('cuttingItems.size');
        return inertia('Cutting/Index', [
            'query' => $query->paginate(10),
        ]);
    }
    public function create()
    {
        return inertia('Cutting/Form', []);
    }
    public function store(Request $request){
        $request->validate([
            'buyer_id' => 'required|exists:buyers,id',
            'brand_id' => 'required|exists:brands,id',
            'material_id' => 'required|exists:materials,id',
            'style' => 'required|string',
            'name' => 'required|string',
            'deadline' => 'required|date',
            'items'=>'required|array',
            'items.*.size_id' => 'required|exists:sizes,id',
            'items.*.qty' => 'required|numeric',
        ]);
        DB::beginTransaction();
        $cutting=Cutting::create([
            'buyer_id' => $request->buyer_id,
            'brand_id' => $request->brand_id,
            'material_id' => $request->material_id,
            'style' => $request->style,
            'name' => $request->name,
            'deadline' => $request->deadline,
        ]);
        foreach($request->items as $item) {
            $cutting->cuttingItems()->create([
                'size_id' => $item['size_id'],
                'qty' => $item['qty'],
            ]);
        }
        DB::commit();

        return redirect()->route('cutting.index')
            ->with('message', ['type' => 'success', 'message' => 'Ratio has beed saved']);
    }

    public function edit(Cutting $cuuting)
    {
        return inertia('Cutting/Form', [
            'ratio' => $cuuting->load(['cuttingItems.size']),
        ]);
    }

    public function update(Request $request, Cutting $cutting){
        $request->validate([
            'buyer_id' => 'required|exists:buyers,id',
            'brand_id' => 'required|exists:brands,id',
            'material_id' => 'required|exists:materials,id',
            'style' => 'required|string',
            'name' => 'required|string',
            'deadline' => 'required|date',
            'items'=>'required|array',
            'items.*.size_id' => 'required|exists:sizes,id',
            'items.*.qty' => 'required|numeric',
        ]);
        DB::beginTransaction();
        $cutting->cuttingItems()->delete();
        $cutting->update([  
            'name' => $request->name,
        ]);
        foreach($request->items as $item) {
            $cutting->detailsRatio()->create([
                'size_id' => $item['size_id'],
                'qty' => $item['qty'],
            ]);
        }
        DB::commit();
        return redirect()->route('cutting.index')
        ->with('message', ['type' => 'success', 'message' => 'Item has beed saved']);
    }

    public function destroy(Cutting $cutting){
        $cutting->delete();
        session()->flash('message', ['type' => 'success', 'message' => 'Item has beed deleted']);
    }
}
