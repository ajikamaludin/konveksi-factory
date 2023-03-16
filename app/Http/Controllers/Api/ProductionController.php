<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Production;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function index(Request $request)
    {
        $query = Production::query()->with(['buyer']);

        if ($request->q) {
            $query->where('name', 'like', "%{$request->q}%")
            ->orWhere('code', 'like', "%{$request->q}%");
        }

        return $query->orderBy('created_at', 'desc')->get();
    }
}
