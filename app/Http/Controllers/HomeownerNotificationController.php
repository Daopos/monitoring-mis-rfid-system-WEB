<?php

namespace App\Http\Controllers;

use App\Models\HomeownerNotification;
use Illuminate\Http\Request;

class HomeownerNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */ public function index(Request $request)
    {
        // Get the authenticated homeowner
        $homeowner = $request->user();

        // Fetch the notifications for the authenticated homeowner
        $notifications = HomeOwnerNotification::where('home_owner_id', $homeowner->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($notifications);
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
    public function destroy(HomeownerNotification $homeownerNotification)
    {
        //
    }
}