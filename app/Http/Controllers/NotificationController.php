<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Get all notifications for the current user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Fetch notifications for the authenticated user's publications
        $notifications = Notification::whereHas('publication.user', function ($query) {
            $query->where('id', auth('sanctum')->user()->id);
        })
            ->with('user', 'comment', 'publication')
            ->where('user_id', '!=', auth('sanctum')->user()->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        return response()->json($notifications);
    }

    /**
     * Store a new notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'publication_id' => 'nullable|exists:publications,id',
            'vote_id' => 'nullable|exists:votes,id',
            'comment_id' => 'nullable|exists:comments,id',
            'type' => 'required|in:1,2,3', // 1: upvote, 2: downvote, 3: comment
        ]);

        // Create the notification
        $notification = Notification::create($request->all());

        return response()->json($notification, 201);
    }
}
