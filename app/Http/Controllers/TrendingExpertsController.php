<?php

namespace App\Http\Controllers;

use App\Models\User;

class TrendingExpertsController extends Controller
{
    public function index()
    {
        $experts = User::where('is_expert', true)
            ->orderBy('reputation', 'desc')
            ->take(10)
            ->get();

        return response()->json($experts);
    }
}
