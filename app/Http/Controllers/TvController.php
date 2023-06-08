<?php

namespace App\Http\Controllers;

use App\Models\Operator;
use App\Models\Production;
use App\Models\ProductionItemResult;
use App\Models\SettingPayroll;
use App\Models\TargetProductions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TvController extends Controller
{
    public function index(Request $request)
    {

        $target = 0;
        $hourline = "-";
        $salary = SettingPayroll::first();
        $hasil = 0;
        $hpp = 0;
        $dataNow=date('Y-m-d');
        $product=null;
        $linehpp=0;
        $prod=ProductionItemResult::with(['item.product','creator'])
        ->where('created_by', Auth::user()->id)
        ->whereDate('input_at','=',$dataNow)
        ->orderBy('created_at', 'DESC')->get();
        $count=1;
        $creator="-";
        $operator = Operator::whereDate('input_date', '=', $dataNow)->orderBy('input_date', 'desc')->value('qty') ?? 1;
        if ($prod->isNotEmpty()) {
            $count=count($prod);
                    foreach($prod as $detail){
                     
                    $creator=$detail->creator->name;
                    $workhours = SettingPayroll::getdays($detail->input_at);
                    $hourline = Carbon::parse($detail->input_at)->format('H:i:s');
                    $gettarget = TargetProductions::whereDate('input_at','=',$dataNow)
                    ->where('production_id','=',$detail->item->product->id)
                    ->orderBy('input_at','desc')->first();
                    if (!empty($gettarget)){
                        $target=$gettarget?->qty;
                    } 
                    $qty = ($detail->finish_quantity + $detail->reject_quantity) * $workhours;
                    $linehpp += ($salary->payroll * $operator) / $qty;
                    $hasil += ($detail->finish_quantity + $detail->reject_quantity);
                    $product=$detail?->item?->product;
                   
                }
        }
     
        $hpp = $linehpp/$count;
      
        return inertia('Tv/Index', [
            '_production' => $product,
            'target' => $target,
            'operator' => $operator,
            'hourline' => $hourline,
            'hpp' => $hpp,
            'hasil' => $hasil,
            'creator'=>$creator,
        ]);
    }
}
