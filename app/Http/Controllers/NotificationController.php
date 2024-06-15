<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        return Notification::with('user')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        $notification = Notification::create($request->all());

        return response()->json($notification, 201);
    }

    public function show(Notification $notification)
    {
        return $notification->load('user');
    }

    public function update(Request $request, Notification $notification)
    {
        $request->validate([
            'content' => 'sometimes|required|string',
        ]);

        $notification->update($request->all());

        return response()->json($notification, 200);
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();

        return response()->noContent();
    }
}
