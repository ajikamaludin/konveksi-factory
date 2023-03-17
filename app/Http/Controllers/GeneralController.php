<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\ProductionItemResult;
use App\Models\User;

class GeneralController extends Controller
{
    public function index()
    {
        $artikel = Production::count();
        $result = ProductionItemResult::count();
        $result_today = ProductionItemResult::whereDate('input_at', now())->count();
        $user = User::count();
        return inertia('Dashboard', [
            'artikel' => $artikel,
            'result' => $result,
            'result_today' => $result_today,
            'user' => $user,
        ]);
    }

    public function maintance()
    {
        return inertia('Maintance');
    }
}
