<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fabric;
use App\Models\FabricItem;
use Illuminate\Http\Request;

class FabricItemController extends Controller
{
    public function index(Request $request)
    {
        $query = FabricItem::query()->with('detailFabrics');

        if ($request->q) {
            $query->where('name', 'like', "%{$request->q}%");
        }

        return $query->orderBy('created_at', 'desc')->get();
    }
    public function fabric(Request $request)
    {
        $query = Fabric::query()->with('first_item.detailFabrics');

        if ($request->q) {
            $query->where('name', 'like', "%{$request->q}%");
        }

        return $query->orderBy('created_at', 'desc')->get();
    }
}
