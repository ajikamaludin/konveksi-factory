<?php

namespace App\Http\Controllers;

use App\Models\UserCutting;
use Illuminate\Http\Request;

class UserCuttingController extends Controller
{
    //
    public function index(Request $request){
        $query =UserCutting::query()->with('cuttingItems.size');
        return inertia('UserCutting/Index', [
            'query' => $query->paginate(10),
        ]);
    }
    public function store(Request $request)
    {

    }
}
