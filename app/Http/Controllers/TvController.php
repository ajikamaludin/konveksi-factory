<?php

namespace App\Http\Controllers;

use App\Models\Operator;
use App\Models\Production;
use App\Models\ProductionItemResult;
use App\Models\SettingPayroll;
use App\Models\TargetProductions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TvController extends Controller
{
    public function index(Request $request)
    {

        $target = 0;
        $hourline = '-';
        $salary = SettingPayroll::first();
        $total = 0;
        $hpp = 0;
        $dataNow = date('Y-m-d');
        $linehpp = 0;
        $creator = auth()->user()->name;
        $production = null;
        $operator = Operator::whereDate('input_date', '=', $dataNow)->orderBy('input_date', 'desc')->value('qty') ?? 1;
        $lastSewingResult = ProductionItemResult::where('created_by', Auth::user()->id)->orderBy('created_at', 'desc')->first();

        if ($lastSewingResult != null) {
            $production = Production::where('id', $lastSewingResult->item->product->id)->first();

            $target = TargetProductions::whereDate('input_at', '=', $dataNow)
                ->where('production_id', '=', $production->id)
                ->orderBy('input_at', 'desc')->value('qty');
            $hourline = now()->format('H:i');
            $workhours = SettingPayroll::getdays($dataNow);
            $productionItemIds = $production->items()->pluck('id')->toArray();

            $total = ProductionItemResult::whereIn('production_item_id', $productionItemIds)
                ->whereDate('created_at', $dataNow)
                ->selectRaw('(SUM(finish_quantity) + SUM(reject_quantity)) as total')
                ->value('total');

            $total = $total <= 0 ? 1 : $total;
            $qty = $total * $workhours;
            $linehpp += ($salary->payroll * $operator) / $qty;

            $hpp = $linehpp / $total;
        }

        return inertia('Tv/Index', [
            '_production' => $production,
            'target' => $target,
            'operator' => $operator,
            'hourline' => $hourline,
            'hpp' => $hpp,
            'hasil' => $total,
            'creator' => $creator,
        ]);
    }
}
