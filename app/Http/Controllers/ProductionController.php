<?php

namespace App\Http\Controllers;

use App\Models\Cutting;
use App\Models\Operator;
use App\Models\OperatorFinishing;
use App\Models\Production;
use App\Models\ProductionItemResult;
use App\Models\SettingPayroll;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;
use Rap2hpoutre\FastExcel\FastExcel;

class ProductionController extends Controller
{
    public function index(Request $request)
    {
        $query = Production::query()->where('is_archive', '=', '0');

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

        foreach ($request->items as $item) {
            $production->items()->create([
                'size_id' => $item['size_id'],
                'color_id' => $item['color_id'],
                'target_quantity' => $item['target_quantity'],
            ]);
        }

        if ($request->hasFile('sketch_image')) {
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

        foreach ($request->items as $item) {
            if ($item['lock'] == 0) {
                $production->items()->create([
                    'size_id' => $item['size_id'],
                    'color_id' => $item['color_id'],
                    'target_quantity' => $item['target_quantity'],
                ]);
            }
        }

        if ($request->hasFile('sketch_image')) {
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
        DB::beginTransaction();
        $itemIds = $production->items()->pluck('id')->toArray();
        ProductionItemResult::whereIn('production_item_id', $itemIds)->delete();

        $production->items()->delete();
        $production->delete();
        $cutting = Cutting::where('production_id', $production->id)->first();
        if ($cutting != null) {
            $cutting->delete();
        }

        DB::commit();

        session()->flash('message', ['type' => 'success', 'message' => 'Item has beed deleted']);
    }

    public function export(Production $production)
    {
        $salary = SettingPayroll::first();
        $exports = [
            ['Style', 'Nama', 'Pembeli', 'Deadline', 'Bahan', 'Brand'],
            [
                $production->code,
                $production->name,
                $production->buyer?->name,
                $production->deadline,
                $production->material?->name,
                $production->brand?->name,
            ],
            [],
            [],
            ['User', 'Warna', 'Size', 'Total PO', 'Jumlah', 'Reject', 'Sisa', 'HPP'],
        ];

        $target = 0;
        $finish = 0;
        $reject = 0;
        $leftTotal = 0;
        $hpp = 0;
        // $line=1;
        foreach ($production->items as $item) {
            $left = $item->target_quantity - $item->finish_quantity - $item->reject_quantity;
            $leftTotal += $left;

            $items = [
                $item->creator->name,
                $item?->color?->name,
                $item->size->name,
                $item->target_quantity,
                $item->finish_quantity,
                $item->reject_quantity,
                $left,
            ];
            $target += $item->target_quantity;
            $finish += $item->finish_quantity;
            $reject += $item->reject_quantity;

            $count = 0;
            $detail = [];
            if (isEmpty($item->results)) {
                $linehpp = 0;
                $detail = [
                    $linehpp,
                ];
            }
            foreach ($item->results as $result) {
                $workhours = SettingPayroll::getdays($result->input_date);
                $operator = Operator::whereDate('input_date', '=', Carbon::parse($result->input_at)->format('Y-m-d'))->first();
                $totalqty = $result->finish_quantity + $result->reject_quantity;
                $totalqty = $totalqty <= 0 ? 1 : $totalqty;
                $workhours = $workhours <= 0 ? 1 : $workhours;
                $totalpay = $salary->payroll * $operator?->qty;
                $totalpay = $totalpay <= 0 ? 1 : $totalpay;
                $linehpp = $totalpay / $totalqty * $workhours;

                $detail = [
                    $linehpp,
                ];

                $hpp += $linehpp;
                $count++;
            }
            $s = array_merge($items, $detail);
            $exports[] = $s;
        }

        $exports[] = [
            'Total',
            '',
            '',
            $target,
            $finish,
            $reject,
            $leftTotal,
        ];
        if ($count == 0) {
            $count = 1;
        }
        $exports[] = [
            'HPP',
            '',
            '',
            '',
            '',
            '',
            '',
            $hpp / $count,
        ];
        // dd($exports);
        $now = now()->format('d-m-Y');

        return (new FastExcel($exports))
            ->withoutHeaders()
            ->download("artikel-$production->code-$now.xlsx");
    }

    public function exportfinishing(Production $production)
    {

        $salary = SettingPayroll::first();
        $exports = [
            ['Style', 'Nama', 'Pembeli', 'Deadline', 'Bahan', 'Brand'],
            [
                $production->code,
                $production->name,
                $production->buyer?->name,
                $production->deadline,
                $production->material?->name,
                $production->brand?->name,
            ],
            [],
            [],
            ['User', 'Warna', 'Size', 'Total PO', 'Jumlah', 'Reject', 'Sisa', 'HPP'],
        ];

        $target = 0;
        $finish = 0;
        $reject = 0;
        $leftTotal = 0;
        $hpp = 0;
        // $line=1;
        foreach ($production->items as $item) {
            $left = $item->target_quantity - $item->finish_quantity - $item->reject_quantity;
            $leftTotal += $left;

            $items = [
                $item->creator->name,
                $item?->color?->name,
                $item->size->name,
                $item->target_quantity,
                $item->result_quantity_finishing,
                $item->reject_quantity,
                $left,
            ];
            $target += $item->target_quantity;
            $finish += $item->result_quantity_finishing;
            $reject += $item->reject_quantity_finishing;

            $count = 0;
            $detail = [];
            if ($item->finishingresults->isEmpty()) {
                $linehpp = 0;
                $detail = [
                    $linehpp,
                ];
            } else {
                foreach ($item->finishingresults as $result) {
                    $workhours = SettingPayroll::getdays($result->input_date);
                    $operator = OperatorFinishing::whereDate('input_at', '=', Carbon::parse($result->input_at)->format('Y-m-d'))->first();

                    $total = ($result->finish_quantity + $result->reject_quantity);
                    $total = $total <= 0 ? 1 : $total;
                    $workhours = $workhours <= 0 ? 1 : $workhours;
                    $totalpay = $salary->payroll * $operator?->qty;
                    $totalpay = $totalpay <= 0 ? 1 : $totalpay;
                    $linehpp = $totalpay / ($total * $workhours);

                    $detail = [
                        $linehpp,
                    ];

                    $hpp += $linehpp;
                    $count++;
                }
            }
            $s = array_merge($items, $detail);
            $exports[] = $s;
        }

        $exports[] = [
            'Total',
            '',
            '',
            $target,
            $finish,
            $reject,
            $leftTotal,
        ];
        if ($count == 0) {
            $count = 1;
        }
        $exports[] = [
            'HPP',
            '',
            '',
            '',
            '',
            '',
            '',
            $hpp / $count,
        ];

        $now = now()->format('d-m-Y');

        return (new FastExcel($exports))
            ->withoutHeaders()
            ->download("artikel-finishing-$production->code-$now.xlsx");
    }

    public function getarchive(Request $request)
    {
        $query = Production::query()->where('is_archive', '=', '1');

        if ($request->q) {
            $query->where('name', 'like', "%{$request->q}%");
        }

        $query->orderBy('created_at', 'desc');

        return inertia('Production/Archive', [
            'query' => $query->paginate(10),
        ]);
    }

    public function archive(Production $production)
    {
        $production->update(['is_archive' => 1]);

        return redirect()->route('production.index')
            ->with('message', ['type' => 'success', 'message' => 'Fabric has beed Archive']);
    }

    public function unarchive(Production $production)
    {
        $production->update(['is_archive' => 0]);

        return redirect()->route('production.archive')
            ->with('message', ['type' => 'success', 'message' => 'Fabric has beed Unarchive']);
    }
}
