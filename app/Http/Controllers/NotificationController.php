<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user() ?: Auth::guard('teacher')->user() ?: Auth::guard('student')->user();
        
        if (!$user) return response()->json(['error' => 'Unauthorized'], 401);

        $notifications = Notification::where('user_id', $user->id)
            ->where('user_type', get_class($user))
            ->latest()
            ->get();

        return response()->json($notifications);
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['read' => true]);
        
        return response()->json(['success' => true]);
    }

    public static function send($user, $title, $message)
    {
        return Notification::create([
            'notification_id' => bin2hex(random_bytes(8)),
            'user_id' => $user->id,
            'user_type' => get_class($user),
            'title' => $title,
            'message' => $message,
            'read' => false,
        ]);
    }
}
