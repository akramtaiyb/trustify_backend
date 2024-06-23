<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function index()
    {
        return Vote::with(['user', 'publication'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'vote' => 'required|in:real,fake',
            'user_id' => 'required|exists:users,id',
            'publication_id' => 'required|exists:publications,id',
        ]);

        $vote = Vote::create($request->all());

        $publication = $vote->publication;
        $publication->updateClassificationScore();

        $publicationUser = $publication->user;
        $publicationUser->updateReputation();

        return response()->json($vote->id, 201);
    }

    public function show(Vote $vote)
    {
        return $vote->load(['user', 'publication']);
    }

    public function update(Request $request, Vote $vote)
    {
        $request->validate([
            'vote' => 'sometimes|required|in:real,fake',
        ]);

        $vote->update($request->all());

        $publication = $vote->publication;
        $publication->updateClassificationScore();

        $publicationUser = $publication->user;
        $publicationUser->updateReputation();

        return response()->json($vote, 200);
    }

    public function destroy(Vote $vote)
    {
        $vote->delete();

        $publication = $vote->publication;
        $publication->updateClassificationScore();

        $publicationUser = $publication->user;
        $publicationUser->updateReputation();

        return response()->noContent();
    }
}
