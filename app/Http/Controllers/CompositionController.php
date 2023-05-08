<?php

namespace App\Http\Controllers;

use App\Models\Compositions;
use Illuminate\Http\Request;

class CompositionController extends Controller
{
    public function index(Request $request)
    {
        $query = Compositions::query();

        if ($request->q) {
            $query->where('name', 'like', "%{$request->q}%");
        }

        $query->orderBy('created_at', 'desc');

        return inertia('Composition/Index', [
            'query' => $query->paginate(10),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Compositions::create([
            'name' => $request->name,
        ]);

        session()->flash('message', ['type' => 'success', 'message' => 'Item has beed saved']);
    }

    public function update(Request $request, Compositions $composition)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $composition->update([
            'name' => $request->name,
        ]);

        session()->flash('message', ['type' => 'success', 'message' => 'Item has beed updated']);
    }

    public function destroy(Compositions $composition)
    {
        $composition->delete();

        session()->flash('message', ['type' => 'success', 'message' => 'Item has beed deleted']);
    }
}
