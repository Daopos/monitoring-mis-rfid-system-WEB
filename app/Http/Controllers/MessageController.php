<?php

namespace App\Http\Controllers;

use App\Models\HomeOwner;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{

// Admin: Show all messages for the admin with confirmed homeowners
public function adminMessageIndex() {
    // Fetch all homeowners with a confirmed status
    $homeOwners = HomeOwner::where('status', 'confirmed')->get();
    $messages = []; // No messages to display initially
    return view('admin.adminmessages', compact('homeOwners', 'messages'));
}

public function adminShowMessage(HomeOwner $homeOwner) {
    // Ensure only confirmed homeowners are available
    if ($homeOwner->status !== 'confirmed') {
        return redirect()->route('admin.messages.index')->with('error', 'Homeowner not found or not confirmed.');
    }

    $homeOwners = HomeOwner::where('status', 'confirmed')->get(); // Fetch confirmed homeowners

    // Fetch only messages between the admin and the specified homeowner
    $messages = Message::where('home_owner_id', $homeOwner->id)
        ->where(function ($query) {
            $query->where('sender_role', 'admin')
                  ->where('recipient_role', 'home_owner') // Admin to homeowner
                ->orWhere(function ($subQuery) {
                    $subQuery->where('sender_role', 'home_owner')
                             ->where('recipient_role', 'admin'); // Homeowner to admin
                });
        })
        ->get();

    return view('admin.adminmessages', compact('homeOwners', 'homeOwner', 'messages'));
}

// Admin: Send a message from the admin to the home owner
public function adminSendMessages(Request $request, HomeOwner $homeOwner) {
    $request->validate([
        'message' => 'required|string|max:255'
    ]);

    $message = new Message();
    $message->message = $request->input('message');
    $message->home_owner_id = $homeOwner->id;
    $message->sender_role = 'admin';
    $message->recipient_role = 'home_owner';
    $message->save();

    return redirect()->route('admin.messages.show', $homeOwner->id);
}

// Guard: Show all messages for the guard with confirmed homeowners
public function guardMessageIndex() {
    // Fetch all homeowners with a confirmed status
    $homeOwners = HomeOwner::where('status', 'confirmed')->get();
    $messages = []; // No messages to display initially
    return view('guard.message', compact('homeOwners', 'messages'));
}

// Guard: Show all messages between the authenticated guard and the home owner
public function guardShowMessage(HomeOwner $homeOwner) {
    // Ensure only confirmed homeowners are available
    if ($homeOwner->status !== 'confirmed') {
        return redirect()->route('guard.messages.index')->with('error', 'Homeowner not found or not confirmed.');
    }

    $homeOwners = HomeOwner::where('status', 'confirmed')->get(); // Fetch confirmed homeowners

    // Fetch only messages between the guard and the selected homeowner
    $messages = Message::where('home_owner_id', $homeOwner->id)
        ->where(function ($query) {
            $query->where('sender_role', 'guard')
                  ->where('recipient_role', 'home_owner') // Guard to Homeowner
                ->orWhere(function ($subQuery) {
                    $subQuery->where('sender_role', 'home_owner')
                             ->where('recipient_role', 'guard'); // Homeowner to Guard
                });
        })
        ->get();

    return view('guard.message', compact('homeOwners', 'homeOwner', 'messages'));
}

// Guard: Send a message from the guard to the home owner
public function guardSendMessages(Request $request, HomeOwner $homeOwner) {
    $request->validate([
        'message' => 'required|string|max:255'
    ]);

    $message = new Message();
    $message->message = $request->input('message');
    $message->home_owner_id = $homeOwner->id;
    $message->sender_role = 'guard';
    $message->recipient_role = 'home_owner';
    $message->save();

    return redirect()->route('guard.messages.show', $homeOwner->id);
}



    // Send a message from the admin to the home owner



    // public function getMessageAPI() {
    //     // Get the authenticated home owner's ID
    //     $homeownerId = Auth::user()->id;

    //     // Retrieve messages where the home owner is either the sender or recipient
    //     $messages = Message::where('home_owner_id', $homeownerId)
    //                     ->where(function($query) {
    //                         $query->where('sender_role', 'home_owner')
    //                               ->orWhere('recipient_role', 'home_owner');
    //                     })
    //                     ->orderBy('created_at', 'asc') // Order by creation time, oldest first
    //                     ->get();

    //     // Return the messages in JSON format for the API response
    //     return response()->json($messages);
    // }

    // public function sendMessageAPI(Request $request) {
    //     // Get the authenticated homeowner's ID
    //     $homeownerId = Auth::user()->id;

    //     // Validate the request inputs
    //     $request->validate([
    //         'message' => 'required|string|max:255',
    //         'recipient_role' => 'required|string|in:admin,guard', // Ensure valid recipient
    //     ]);

    //     // Create a new message
    //     Message::create([
    //         'message' => $request->message,
    //         'home_owner_id' => $homeownerId, // The authenticated homeowner
    //         'sender_role' => 'home_owner', // Homeowner is the sender
    //         'recipient_role' => $request->recipient_role, // Either 'admin' or 'guard'
    //     ]);

    //     // Return a success response
    //     return response()->json(['success' => 'Message sent successfully!']);
    // }

    public function getMessageAdminAPI() {
        // Get the authenticated home owner's ID
        $homeownerId = Auth::user()->id;

        // Retrieve messages where the home owner is either the sender or the recipient, and one of the roles is 'admin'
        $messages = Message::where('home_owner_id', $homeownerId)
                            ->where(function($query) {
                                $query->where('sender_role', 'home_owner')
                                      ->where('recipient_role', 'admin') // Homeowner to Admin
                                    ->orWhere(function($subQuery) {
                                        $subQuery->where('sender_role', 'admin')
                                                 ->where('recipient_role', 'home_owner'); // Admin to Homeowner
                                    });
                            })
                            ->orderBy('created_at', 'asc') // Order by creation time, oldest first
                            ->get();

        // Return the messages in JSON format for the API response
        return response()->json($messages);
    }
    public function getMessageGuardAPI() {
        // Get the authenticated home owner's ID
        $homeownerId = Auth::user()->id;

        // Retrieve messages where the home owner is either the sender or the recipient, and one of the roles is 'guard'
        $messages = Message::where('home_owner_id', $homeownerId)
                            ->where(function($query) {
                                $query->where('sender_role', 'home_owner')
                                      ->where('recipient_role', 'guard') // Homeowner to Guard
                                    ->orWhere(function($subQuery) {
                                        $subQuery->where('sender_role', 'guard')
                                                 ->where('recipient_role', 'home_owner'); // Guard to Homeowner
                                    });
                            })
                            ->orderBy('created_at', 'asc') // Order by creation time, oldest first
                            ->get();

        // Return the messages in JSON format for the API response
        return response()->json($messages);
    }


    public function sendMessageAdminAPI(Request $request) {
        // Get the authenticated homeowner's ID
        $homeownerId = Auth::user()->id;

        // Validate the request inputs
        $request->validate([
            'message' => 'required|string|max:255',
            'recipient_role' => 'required|in:admin', // Only allow sending messages to admin
        ]);

        // Create a new message for admin
        Message::create([
            'message' => $request->message,
            'home_owner_id' => $homeownerId, // The authenticated homeowner
            'sender_role' => 'home_owner', // Homeowner is the sender
            'recipient_role' => 'admin', // Admin is the recipient
        ]);

        // Return a success response
        return response()->json(['success' => 'Message sent to admin successfully!']);
    }

    public function sendMessageGuardAPI(Request $request) {
        // Get the authenticated homeowner's ID
        $homeownerId = Auth::user()->id;

        // Validate the request inputs
        $request->validate([
            'message' => 'required|string|max:255',
            'recipient_role' => 'required|in:guard', // Only allow sending messages to guard
        ]);

        // Create a new message for guard
        Message::create([
            'message' => $request->message,
            'home_owner_id' => $homeownerId, // The authenticated homeowner
            'sender_role' => 'home_owner', // Homeowner is the sender
            'recipient_role' => 'guard', // Guard is the recipient
        ]);

        // Return a success response
        return response()->json(['success' => 'Message sent to guard successfully!']);
    }
}