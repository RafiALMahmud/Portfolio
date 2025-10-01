<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UserMessage;
use App\Models\User;

class NotificationsController extends Controller
{
    public function markAsRead(string $id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();
        return back()->with('status', 'Marked as read');
    }

    public function userReply(Request $request)
    {
        $data = $request->validate([
            'message' => ['required','string','max:1000'],
        ]);

        // Users can only message admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new UserMessage($data['message'], $request->user()));
        }

        return back()->with('status', 'Message sent to admin');
    }
}



