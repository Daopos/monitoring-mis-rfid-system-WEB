<?php

namespace App\Http\Controllers;

use App\Models\HomeownerNotification;
use Illuminate\Http\Request;

class HomeownerNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get the authenticated homeowner
        $homeowner = $request->user();

        // Fetch the notifications for the authenticated homeowner
        $notifications = HomeOwnerNotification::where('home_owner_id', $homeowner->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($notifications);
    }



    public function markAsRead(Request $request)
{
    $notificationIds = $request->input('notification_ids');

    // Ensure valid IDs are provided
    if (!is_array($notificationIds)) {
        return response()->json(['error' => 'Invalid notification IDs'], 400);
    }

    // Update the notifications as read
    HomeOwnerNotification::whereIn('id', $notificationIds)
        ->where('home_owner_id', $request->user()->id)
        ->update(['is_read' => true]);

    return response()->json(['message' => 'Notifications marked as read']);
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(HomeownerNotification $homeownerNotification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HomeownerNotification $homeownerNotification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HomeownerNotification $homeownerNotification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        // Get the authenticated homeowner
        $homeowner = $request->user();

        // Find the notification by ID and ensure it belongs to the authenticated homeowner
        $notification = HomeOwnerNotification::where('id', $id)
            ->where('home_owner_id', $homeowner->id)
            ->first();

        if (!$notification) {
            return response()->json(['message' => 'Notification not found or not authorized to delete'], 404);
        }

        // Delete the notification
        $notification->delete();

        return response()->json(['message' => 'Notification deleted successfully']);
    }
}