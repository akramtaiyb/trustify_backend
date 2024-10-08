<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\Notification;
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


        // Create a suitable notification
        Notification::create([
            'user_id' => $vote->user_id,
            'publication_id' => $publication->id,
            'vote_id' => $vote->id,
            'type' => $vote->vote === 'real' ? 1 : 2, // Type: vote
        ]);

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

        // Update vote
        $vote->update($request->all());

        // Update classification score and user reputation
        $publication = $vote->publication;
        $publication->updateClassificationScore();

        $publicationUser = $publication->user;
        $publicationUser->updateReputation();

        // Update the existing notification linked to this vote
        $notification = Notification::where('vote_id', $vote->id)->first();

        if ($notification) {

            // 1 = upvote, 2 = downvote
            $notification->type = $vote->vote === 'real' ? 1 : 2;
            $notification->save();
        }

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
