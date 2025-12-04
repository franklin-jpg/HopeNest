<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of all notifications
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(20);
        $unreadCount = Auth::user()->unreadNotifications()->count();
        
        return view('admin.notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Display the specified notification and mark as read
     */
    public function show($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        
        // Mark as read
        if ($notification->unread()) {
            $notification->markAsRead();
        }
        
        // Redirect to the relevant page based on notification type
        $data = $notification->data;
        
        if (isset($data['url'])) {
            return redirect($data['url']);
        }
        
        return redirect()->route('admin.notifications.index');
    }

    /**
     * Mark a single notification as read
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        
        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * Get unread notification count
     */
    public function getUnreadCount()
    {
        $count = Auth::user()->unreadNotifications()->count();
        
        return response()->json([
            'count' => $count
        ]);
    }

    /**
     * Get latest notifications for dropdown
     */
    public function getLatest()
    {
        $notifications = Auth::user()->notifications()
            ->take(5)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->data['type'] ?? 'notification',
                    'message' => $notification->data['message'] ?? 'New notification',
                    'time' => $notification->created_at->diffForHumans(),
                    'read' => $notification->read_at !== null,
                    'url' => $notification->data['url'] ?? '#',
                ];
            });
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => Auth::user()->unreadNotifications()->count()
        ]);
    }

    /**
     * Delete a single notification
     */
    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Notification deleted successfully'
        ]);
    }

    /**
     * Delete all notifications
     */
    public function destroyAll()
    {
        Auth::user()->notifications()->delete();
        
        return redirect()->back()->with('success', 'All notifications deleted successfully');
    }
}