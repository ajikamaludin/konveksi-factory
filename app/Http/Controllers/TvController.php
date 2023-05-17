<?php

namespace App\Http\Controllers;

use App\Models\Operator;
use App\Models\Production;
use App\Models\SettingPayroll;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TvController extends Controller
{
    public function index(Request $request)
    {

        $target = 0;
        $operator = 0;
        $hourline = null;
        $salary = SettingPayroll::first();
        $hasil = 0;
        $hpp = 0;
        $prod = Production::query()->with(['items.results'=>function($query){
            $query->orderBy('created_at', 'DESC');
        }])->orderBy('updated_at', 'DESC')
            // ->where('created_by', Auth::user()->id)
            ->first();
            $operator = Operator::whereDate('input_date','=', date('Y-m-d'))->orderBy('input_date', 'desc')->first();
        if ($prod != null) {
            foreach ($prod->items as $item) {
                foreach ($item->results as $result) {
                   
                    $workhours = SettingPayroll::getdays($result->input_date);
                    // $operator = Operator::whereDate('input_date','=', $result->input_at)->orderBy('input_date', 'desc')->first();
                    $hourline = Carbon::parse($result->input_at)->format('H:i:s');
                    $target = $item->results[0]->finish_quantity * $workhours;
                    $qty=($result->finish_quantity + $result->reject_quantity) * $workhours;
                    if ($qty==0){
                        $qty=1;
                    }
                 
                    if($operator==null){
                        $operator=1;
                    }else{
                        $operator=$operator?->qty;
                    }
                    
                    $linehpp = ($salary->payroll * $operator) / $qty;
                    $hpp = $linehpp;
                    $hasil = $result->finish_quantity + $result->reject_quantity;
                }
            }
        }

        return inertia('Tv/Index', [
            '_production' => $prod,
            'target' => $target,
            'operator' => $operator,
            'hourline' => $hourline,
            'hpp' => $hpp,
            'hasil' => $hasil,
        ]);
    }
}
