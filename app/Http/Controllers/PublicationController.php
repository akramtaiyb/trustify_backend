<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\Http\Request;

class PublicationController extends Controller
{
    public function index()
    {
//        return Publication::with('user')->get();
        return Publication::with(['user', 'comments.user'])->withCount([
            'votes as upvotes_count' => function ($query) {
                $query->where('vote', 'real');
            },
            'votes as downvotes_count' => function ($query) {
                $query->where('vote', 'fake');
            },
        ])->withCount('comments')->orderByDesc('id')->paginate(4)->jsonSerialize();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'max:255',
            'content' => 'required',
            'type' => '',
            'user_id' => 'required|exists:users,id',
        ]);

        $publication = Publication::create($request->all());

        return response()->json($publication, 201);
    }

    public function show(Publication $publication)
    {
        return $publication->load('user');
    }

    public function update(Request $request, Publication $publication)
    {
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'type' => 'sometimes|required|string',
        ]);

        $publication->update($request->all());

        return response()->json($publication, 200);
    }

    public function destroy(Publication $publication)
    {
        $publication->delete();

        return response()->noContent();
    }
}
