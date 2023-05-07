<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Production;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index(Request $request)
    {
        $query = Size::query();

        if ($request->q) {
            $query->where('name', 'like', "%{$request->q}%");
        }

        if ($request->prod_id != '') {
            $production = Production::find($request->prod_id);
            $sizeIds = $production->items()->groupBy('size_id')->pluck('size_id')->toArray();

            $query->whereIn('id', $sizeIds);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }
}
