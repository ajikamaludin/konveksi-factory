<?php

namespace App\Http\Controllers;

use App\Models\Operator;
use App\Models\Production;
use App\Models\ProductionItemResult;
use App\Models\SettingPayroll;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TvController extends Controller
{
    public function index(Request $request)
    {

        $target = 0;
        $hourline = null;
        $salary = SettingPayroll::first();
        $hasil = 0;
        $hpp = 0;
    
        $prod=ProductionItemResult::with(['item.product'])
        ->where('created_by', Auth::user()->id)
        ->orderBy('created_at', 'DESC')->first();
       
        $operator = Operator::whereDate('input_date', '=', date('Y-m-d'))->orderBy('input_date', 'desc')->value('qty') ?? 1;
        if ($prod != null) {
    
                    $workhours = SettingPayroll::getdays($prod->input_at);
                    $hourline = Carbon::parse($prod->input_at)->format('H:i:s');
                    $target = $prod->finish_quantity * $workhours;
                    $qty = ($prod->item->finish_quantity + $prod->reject_quantity) * $workhours;
          
                    $linehpp = ($salary->payroll * $operator) / $qty;
                    $hpp = $linehpp;
                    $hasil = $prod->item->finish_quantity + $prod->item->reject_quantity;
        }
        
        return inertia('Tv/Index', [
            '_production' => $prod?->item?->product,
            'target' => $target,
            'operator' => $operator,
            'hourline' => $hourline,
            'hpp' => $hpp,
            'hasil' => $hasil,
        ]);
    }
}
