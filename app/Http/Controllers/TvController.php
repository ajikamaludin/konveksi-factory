<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\ProductionItem;
use Illuminate\Http\Request;

class TvController extends Controller
{
    //
    public function index(Request $request){
        $prod = Production::query()->with('items.results')->orderBy('created_at', 'desc');
        if ($request->production_id != '' ){
            $prod->where('id',$request->production_id);
        }
    
        return inertia('Tv/Index', [
           '_production'=>$prod->first(),
        ]);
    }
}
