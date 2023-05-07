<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($request->q) {
            $query->where('name', 'like', "%{$request->q}%");
        }

        $query->orderBy('created_at', 'desc');

        return inertia('Supplier/Index', [
            'query' => $query->paginate(10),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phonenumber' => 'required|numeric',
            'emails' => 'required|email|unique:suppliers,email',
        ]);

        Supplier::create([
            'name' => $request->name,
            'address' => $request->address,
            'phonenumber' => $request->phonenumber,
            'emails' => $request->emails,
        ]);

        session()->flash('message', ['type' => 'success', 'message' => 'Item has beed saved']);
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phonenumber' => 'required|numeric',
            'emails' => 'required|email|unique:suppliers,email',
        ]);

        $supplier->update([
            'name' => $request->name,
            'address' => $request->address,
            'phonenumber' => $request->phonenumber,
            'emails' => $request->emails,
        ]);

        session()->flash('message', ['type' => 'success', 'message' => 'Item has beed updated']);
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        session()->flash('message', ['type' => 'success', 'message' => 'Item has beed deleted']);
    }
}
