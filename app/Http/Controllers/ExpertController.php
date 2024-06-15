<?php

namespace App\Http\Controllers;

use App\Models\Expert;
use Illuminate\Http\Request;

class ExpertController extends Controller
{
    public function index()
    {
        return Expert::with('user')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'domain_expertise' => 'required|string|max:255',
        ]);

        $expert = Expert::create($request->all());

        return response()->json($expert, 201);
    }

    public function show(Expert $expert)
    {
        return $expert->load('user');
    }

    public function update(Request $request, Expert $expert)
    {
        $request->validate([
            'domain_expertise' => 'sometimes|required|string|max:255',
        ]);

        $expert->update($request->all());

        return response()->json($expert, 200);
    }

    public function destroy(Expert $expert)
    {
        $expert->delete();

        return response()->noContent();
    }
}
