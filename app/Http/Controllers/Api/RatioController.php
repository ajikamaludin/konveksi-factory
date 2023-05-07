<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ratio;
use Illuminate\Http\Request;

class RatioController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = Ratio::query()->with('detailsRatio.size');

        if ($request->q) {
            $query->where('name', 'like', "%{$request->q}%");
        }

        return $query->orderBy('created_at', 'desc')->get();
    }
}
