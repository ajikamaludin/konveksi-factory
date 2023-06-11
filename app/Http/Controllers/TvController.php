<?php

namespace App\Http\Controllers;

use App\Models\Operator;
use App\Models\Production;
use App\Models\ProductionItemResult;
use App\Models\SettingPayroll;
use App\Models\TargetProductions;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TvController extends Controller
{
    public function index(Request $request)
    {

        $target = 0;
        $hourline = '-';
        $salary = SettingPayroll::first();
        $hasil = 0;
        $hpp = 0;
        $estimate = 0;
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

            $totalBefore = ProductionItemResult::whereIn('production_item_id', $productionItemIds)
                ->whereDate('created_at', '<', $dataNow)
                ->selectRaw('(SUM(finish_quantity) + SUM(reject_quantity)) as total')
                ->value('total');

            $count_total = ProductionItemResult::whereIn('production_item_id', $productionItemIds)
                ->whereDate('created_at', $dataNow)
                ->count();

            $total = ProductionItemResult::whereIn('production_item_id', $productionItemIds)
                ->whereDate('created_at', $dataNow)
                ->selectRaw('(SUM(finish_quantity) + SUM(reject_quantity)) as total')
                ->value('total');

            $hasil = $total;

            $total = ($total <= 0 ? 1 : $total) + $totalBefore;
            $linehpp = ($salary->payroll * $operator);

            $hpp = $linehpp / $total;

            $estimate = ($total / $count_total) * $workhours;
        }

        return inertia('Tv/Index', [
            '_production' => $production,
            'target' => $target,
            'operator' => $operator,
            'hourline' => $hourline,
            'hpp' => $hpp,
            'hasil' => $hasil,
            'creator' => $creator,
            'estimate' => $estimate
        ]);
    }
}
