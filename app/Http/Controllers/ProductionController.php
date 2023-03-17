<?php

namespace App\Http\Controllers;

use App\Models\Production;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    public function index(Request $request)
    {
        $query = Production::query();

        if ($request->q) {
            $query->where('name', 'like', "%{$request->q}%");
        }

        $query->orderBy('created_at', 'desc');

        return inertia('Production/Index', [
            'query' => $query->paginate(10),
        ]);
    }

    public function create()
    {
        return inertia('Production/Form', []);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'name' => 'required|string',
            'buyer_id' => 'nullable|exists:buyers,id',
            'brand_id' => 'nullable|exists:brands,id',
            'material_id' => 'nullable|exists:materials,id',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'sketch_image' => 'nullable|image',
            'items.*.size_id' => 'required|exists:sizes,id',
            'items.*.color_id' => 'required|exists:colors,id',
            'items.*.target_quantity' => 'required|numeric',
        ]);

        DB::beginTransaction();
        $production = Production::create([
            'buyer_id' => $request->buyer_id,
            'brand_id' => $request->brand_id,
            'material_id' => $request->material_id,
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
            'deadline' => $request->deadline,
        ]);

        foreach($request->items as $item) {
            $production->items()->create([
                'size_id' => $item['size_id'],
                'color_id' => $item['color_id'],
                'target_quantity' => $item['target_quantity'],
            ]);
        }

        if($request->hasFile('sketch_image')) {
            $file = $request->file('sketch_image');
            $file->store('uploads', 'public');
            $production->update(['sketch_image' => $file->hashName('uploads')]);
        }
        DB::commit();

        return redirect()->route('production.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has beed saved']);
    }

    public function edit(Production $production)
    {
        return inertia('Production/Form', [
            'production' => $production->load(['buyer', 'brand', 'material', 'items.size', 'items.color']),
        ]);
    }

    public function update(Request $request, Production $production)
    {
        $request->validate([
            'code' => 'required|string',
            'name' => 'required|string',
            'buyer_id' => 'nullable|exists:buyers,id',
            'brand_id' => 'nullable|exists:brands,id',
            'material_id' => 'nullable|exists:materials,id',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'sketch_image' => 'nullable|image',
            'items.*.size_id' => 'required|exists:sizes,id',
            'items.*.color_id' => 'required|exists:colors,id',
            'items.*.target_quantity' => 'required|numeric',
            'items.*.lock' => 'required|numeric',
        ]);

        DB::beginTransaction();
        $production->items()->where('lock', 0)->delete();
        $production->update([
            'buyer_id' => $request->buyer_id,
            'brand_id' => $request->brand_id,
            'material_id' => $request->material_id,
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
            'deadline' => $request->deadline,
        ]);

        foreach($request->items as $item) {
            if($item['lock'] == 0) {
                $production->items()->create([
                    'size_id' => $item['size_id'],
                    'color_id' => $item['color_id'],
                    'target_quantity' => $item['target_quantity'],
                ]);
            }
        }

        if($request->hasFile('sketch_image')) {
            $file = $request->file('sketch_image');
            $file->store('uploads', 'public');
            $production->update(['sketch_image' => $file->hashName('uploads')]);
        }
        DB::commit();

        return redirect()->route('production.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has beed saved']);
    }

    public function destroy(Production $production)
    {
        $production->delete();

        session()->flash('message', ['type' => 'success', 'message' => 'Item has beed deleted']);
    }
}
