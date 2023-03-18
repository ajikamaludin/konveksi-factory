<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\Production;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index(Request $request)
    {
        $query = Color::query();

        if ($request->q) {
            $query->where('name', 'like', "%{$request->q}%");
        }

        if($request->prod_id != '') {
            $production = Production::find($request->prod_id);
            $colorIds = $production->items()->groupBy('color_id')->pluck('color_id')->toArray();

            $query->whereIn('id', $colorIds);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }
}
