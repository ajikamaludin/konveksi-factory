<?php

namespace App\Http\Controllers;

use App\Models\DetailFabric;
use App\Models\Fabric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FabricController extends Controller
{
    public function index(Request $request)
    {
        $query = Fabric::query()->with('supplier', 'fabricItems.detailFabrics')
            ->join('fabric_items', 'fabric_id', '=', 'fabrics.id')
            ->join('detail_fabrics', function ($join) {
                $join->on('fabric_item_id', '=', 'fabric_items.id');
                $join->whereNull('detail_fabrics.deleted_at');
            })
            ->select('fabrics.*', DB::raw('round(sum(qty),2) as qty'));
        if ($request->q) {
            $query->where('name', 'like', "%{$request->q}%");
        }

        $query->groupBy('fabrics.id')->orderBy('fabrics.created_at', 'desc');

        return inertia('Fabric/Index', [
            'query' => $query->paginate(10),
        ]);
    }

    public function create()
    {
        return inertia('Fabric/Form', []);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'letter_number' => 'nullable|string',
            'composisi_id' => 'nullable|exists:compositions,id',
            'setting_size' => 'nullable|string',
            'order_date' => 'nullable|date',
            'items' => 'required|array',
            'items.*.code' => 'required|string',
            'items.*.detail_fabrics' => 'required|array',
            'items.*.detail_fabrics.*.qty' => 'required|numeric',
        ]);

        DB::beginTransaction();

        $fabric = Fabric::create([
            'name' => $request->name,
            'supplier_id' => $request->supplier_id,
            'letter_number' => $request->letter_number,
            'composisi_id' => $request->composisi_id,
            'setting_size' => $request->setting_size,
            'order_date' => $request->order_date,
        ]);

        foreach ($request->items as $item) {
            $fabricitems = $fabric->fabricItems()->create([
                'code' => $item['code'],
                'name' => $request->name,
            ]);

            foreach ($item['detail_fabrics'] as $detail) {
                $fabricitems->detailFabrics()->create([
                    'qty' => $detail['qty'],
                    'fabric_item_id' => $fabricitems['id'],
                ]);
            }
        }
        DB::commit();

        return redirect()->route('fabric.index')
            ->with('message', ['type' => 'success', 'message' => 'Fabric has beed saved']);
    }

    public function edit(Fabric $fabric)
    {
        return inertia('Fabric/Form', [
            'fabric' => $fabric->load(['supplier', 'fabricItems.detailFabrics']),
        ]);
    }

    public function update(Request $request, Fabric $fabric)
    {
        $request->validate([
            'name' => 'required|string',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'letter_number' => 'nullable|string',
            'composisi_id' => 'nullable|exists:compositions,id',
            'setting_size' => 'nullable|string',
            'order_date' => 'nullable|date',
            'items' => 'required|array',
            'items.*.code' => 'required|string',
            'items.*.detail_fabrics' => 'required|array',
            'items.*.detail_fabrics.*.qty' => 'required|numeric',
        ]);

        DB::beginTransaction();

        $fabric->update([
            'name' => $request->name,
            'supplier_id' => $request->supplier_id,
            'letter_number' => $request->letter_number,
            'composisi_id' => $request->composisi_id,
            'setting_size' => $request->setting_size,
            'order_date' => $request->order_date,
        ]);

        $items = collect($request->items)->pluck('code')->toArray();
        $fabric->fabricItems()->whereNotIn('code', $items)->delete();

        foreach ($request->items as $item) {
            $fabricitems = $fabric->fabricItems()->updateOrCreate([
                'code' => $item['code'],
            ], [
                'name' => $request->name,
            ]);

            $fabricitems->detailFabrics()->delete();
            foreach ($item['detail_fabrics'] as $detail) {
                $fabricitems->detailFabrics()->create([
                    'qty' => $detail['qty'],
                ]);
            }
        }
        DB::commit();

        return redirect()->route('fabric.index')
            ->with('message', ['type' => 'success', 'message' => 'Fabric has beed updated']);
    }

    public function delete(Fabric $fabric)
    {
        DB::beginTransaction();
        $itemIds = $fabric->fabricItems()->pluck('id')->toArray();
        DetailFabric::whereIn('fabric_item_id', $itemIds)->delete();
        $fabric->delete();
        $fabric->fabricItems()->delete();
        DB::commit();
        session()->flash('message', ['type' => 'success', 'message' => 'Item has beed deleted']);
    }
}
