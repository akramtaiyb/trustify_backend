<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        return Comment::with(['user', 'publication'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'publication_id' => 'required|exists:publications,id',
        ]);

        $comment = Comment::create($request->all());

        return response()->json($comment, 201);
    }

    public function show(Comment $comment)
    {
        return $comment->load(['user', 'publication']);
    }

    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'content' => 'sometimes|required|string',
        ]);

        $comment->update($request->all());

        return response()->json($comment, 200);
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->noContent();
    }
}
