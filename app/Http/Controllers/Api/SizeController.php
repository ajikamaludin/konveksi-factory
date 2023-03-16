<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

        return $query->orderBy('created_at', 'desc')->get();
    }
}
