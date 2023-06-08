<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cutting;
use App\Models\Production;
use Illuminate\Http\Request;

class CuttingController extends Controller
{
    public function index(Request $request)
    {
        $query = Cutting::query()->with(['buyer','cuttingItems.size'])->where('is_archive','=','0');

        if ($request->q) {
            $query->where('name', 'like', "%{$request->q}%")
                ->orWhere('code', 'like', "%{$request->q}%");
        }

        return $query->orderBy('created_at', 'desc')->get();
    }
}
