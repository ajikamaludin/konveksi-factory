<?php

namespace App\Http\Controllers;

use App\Models\SettingPayroll;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = SettingPayroll::query();
        $query->orderBy('created_at', 'desc');

        return inertia('Setting/Index', [
            'settingPayroll' => $query->first(),
        ]);
    }

    public function store(Request $request, SettingPayroll $settingPayroll)
    {
        $request->validate([
            'payroll' => 'required|numeric',
            'workhours_sunday' => 'required|numeric',
            'workhours_monday' => 'required|numeric',
            'workhours_tuesday' => 'required|numeric',
            'workhours_wednesday' => 'required|numeric',
            'workhours_thusday' => 'required|numeric',
            'workhours_friday' => 'required|numeric',
            'workhours_saturday' => 'required|numeric',
        ]);

        $settingPayroll->update([
            'payroll' => $request->payroll,
            'workhours_sunday' => $request->workhours_sunday,
            'workhours_monday' => $request->workhours_monday,
            'workhours_tuesday' => $request->workhours_tuesday,
            'workhours_wednesday' => $request->workhours_wednesday,
            'workhours_thusday' => $request->workhours_thusday,
            'workhours_friday' => $request->workhours_friday,
            'workhours_saturday' => $request->workhours_saturday,
        ]);
        session()->flash('message', ['type' => 'success', 'message' => 'Setting has beed updated']);
    }
}
