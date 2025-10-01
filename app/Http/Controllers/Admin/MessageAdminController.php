<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageAdminController extends Controller
{
    public function index(Request $request)
    {
        $admin = Auth::user();
        $notifications = $admin->notifications()
            ->whereIn('type', [
                'App\\Notifications\\UserMessage',
                'App\\Notifications\\AdminMessage',
                '\\App\\Notifications\\UserMessage',
                '\\App\\Notifications\\AdminMessage',
            ])
            ->latest()
            ->paginate(20);

        return view('admin.messages.index', compact('notifications'));
    }

    public function markAsRead(string $id)
    {
        $admin = Auth::user();
        $notification = $admin->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();
        return back()->with('status', 'Message marked as read');
    }
}


