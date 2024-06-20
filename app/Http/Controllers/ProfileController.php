<?php
namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Publication;
use App\Models\Comment;
use App\Models\Vote;

class ProfileController extends Controller
{
    /**
     * Display the profile of the authenticated user.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $user->load([
            'publications.user',
            'publications.comments.user',
            'publications.votes',
            'comments.user',
            'votes'
        ]);

        return response()->json($user);
    }

    /**
     * Display the profile of a user by username.
     *
     * @param string $username
     * @return JsonResponse
     */
    public function show($username)
    {
        $user = User::where('username', $username)->firstOrFail();

        $user->load([
            'publications.user',
            'publications.comments.user',
            'publications.votes',
            'publications.mediaFiles',
            'comments.user',
            'votes'
        ]);

        return response()->json($user);
    }

    /**
     * Display the publications, comments, and votes of the authenticated user.
     *
     * @return JsonResponse
     */
    public function userActivity()
    {
        $userId = Auth::id();

        if (!$userId) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $publications = Publication::where('user_id', $userId)
            ->with([
                'user',
                'comments.user',
                'votes'
            ])
            ->withCount([
                'votes as upvotes_count' => function ($query) {
                    $query->where('vote', 'real');
                },
                'votes as downvotes_count' => function ($query) {
                    $query->where('vote', 'fake');
                },
                'comments'
            ])
            ->get();

        $comments = Comment::where('user_id', $userId)
            ->with('publication.user')
            ->with('user') // Include user for each comment
            ->get();

        $votes = Vote::where('user_id', $userId)
            ->with('publication.user')
            ->get();

        return response()->json([
            'publications' => $publications,
            'comments' => $comments,
            'votes' => $votes
        ]);
    }

    /**
     * Update the profile of the authenticated user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($validatedData);

        return response()->json($user);
    }
}
