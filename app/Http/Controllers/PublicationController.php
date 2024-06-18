<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $publications = Publication::with(['user', 'comments.user'])
            ->withCount([
                'votes as upvotes_count' => function ($query) {
                    $query->where('vote', 'real');
                },
                'votes as downvotes_count' => function ($query) {
                    $query->where('vote', 'fake');
                },
                'comments'
            ])
            // retrieve the vote id of the user auth()->user() as user_vote_id if the vote exists else return false
            ->orderByDesc('id')
            ->paginate(4);

        return response()->json($publications);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'max:255',
            'content' => 'required',
            'user_id' => 'required|exists:users,id',
        ]);

        $publication = Publication::create($request->all());

        $publication = Publication::with(['user', 'comments.user'])
            ->withCount([
                'votes as upvotes_count' => function ($query) {
                    $query->where('vote', 'real');
                },
                'votes as downvotes_count' => function ($query) {
                    $query->where('vote', 'fake');
                },
                'comments'
            ])->find($publication->id);

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
