<?php

namespace App\Http\Controllers;

use App\Models\Ratio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RatioController extends Controller
{
    public function index(Request $request){
        $query =Ratio::query()->with('detailsRatio.size');
        return inertia('Ratio/Index', [
            'query' => $query->paginate(10),
        ]);
    }
    public function create()
    {
        return inertia('Ratio/Form', []);
    }
    public function store(Request $request){
        $request->validate([
            'name' => 'required|string',
            'items'=>'required|array',
            'items.*.size_id' => 'required|exists:sizes,id',
            'items.*.qty' => 'required|numeric',
        ]);
        DB::beginTransaction();
        $ratio=Ratio::create([
            'name'=>$request->name
        ]);
        foreach($request->items as $item) {
            $ratio->detailsRatio()->create([
                'size_id' => $item['size_id'],
                'qty' => $item['qty'],
            ]);
        }
        DB::commit();

        return redirect()->route('ratio.index')
            ->with('message', ['type' => 'success', 'message' => 'Ratio has beed saved']);
    }

    public function edit(Ratio $ratio)
    {
        return inertia('Ratio/Form', [
            'ratio' => $ratio->load(['detailsRatio.size']),
        ]);
    }

    public function update(Request $request, Ratio $ratio){
        $request->validate([
            'name' => 'required|string',
            'items'=>'required|array',
            'items.*.size_id' => 'required|exists:sizes,id',
            'items.*.qty' => 'required|numeric',
        ]);
        DB::beginTransaction();
        $ratio->detailsRatio()->delete();
        $ratio->update([  
            'name' => $request->name,
        ]);
        foreach($request->items as $item) {
            $ratio->detailsRatio()->create([
                'size_id' => $item['size_id'],
                'qty' => $item['qty'],
            ]);
        }
        DB::commit();
        return redirect()->route('ratio.index')
        ->with('message', ['type' => 'success', 'message' => 'Item has beed saved']);
    }

    public function destroy(Ratio $ratio){
        DB::beginTransaction();
        $ratio->detailsRatio()->delete();
        $ratio->delete();
        DB::commit();

        session()->flash('message', ['type' => 'success', 'message' => 'Item has beed deleted']);
    }
}
