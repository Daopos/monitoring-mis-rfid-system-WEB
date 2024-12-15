<?php

namespace App\Http\Controllers;

use App\Models\HomeOwner;
use App\Models\HomeownerNotification;
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

public function adminShowMessage(HomeOwner $homeOwner)
{
    if ($homeOwner->status !== 'confirmed') {
        return redirect()->route('admin.messages.index')->with('error', 'Homeowner not found or not confirmed.');
    }

    // Fetch confirmed homeowners, prioritizing those with unread messages
    $homeOwners = HomeOwner::where('status', 'confirmed')
    ->with(['messages' => function ($query) {
        // Fetch the most recent message
        $query->orderByDesc('created_at')->limit(1);
    }])
    ->orderByDesc(function ($query) {
        $query->selectRaw('max(messages.created_at)')
            ->from('messages')
            ->whereColumn('messages.home_owner_id', 'home_owners.id')
            ->where('messages.is_seen', false)
            ->where('messages.sender_role', 'home_owner')
            ->where('messages.recipient_role', 'admin');
    })
    ->get();

    // Fetch messages for the selected homeowner
    $messages = Message::where('home_owner_id', $homeOwner->id)
        ->where(function ($query) {
            $query->where('sender_role', 'admin')
                ->where('recipient_role', 'home_owner')
                ->orWhere(function ($subQuery) {
                    $subQuery->where('sender_role', 'home_owner')
                        ->where('recipient_role', 'admin');
                });
        })
        ->get();

    // Mark homeowner's messages as seen
    Message::where('home_owner_id', $homeOwner->id)
        ->where('sender_role', 'home_owner') // Only homeowner's messages
        ->where('recipient_role', 'admin') // Only homeowner's messages
        ->where('is_seen', false) // Only unseen messages
        ->update(['is_seen' => true]);

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

   // Add a notification for the homeowner
   HomeownerNotification::create([
    'home_owner_id' => $homeOwner->id, // Ensure the correct homeowner ID is used
    'title' => 'New Message from Admin',
    'message' => "You have received a new message: {$message->message}",
    'is_read' => false,
]);

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

    // Fetch confirmed homeowners, prioritizing those with unread messages
    // $homeOwners = HomeOwner::where('status', 'confirmed')
    //     ->orderByDesc(function ($query) {
    //         $query->selectRaw('count(*)')
    //             ->from('messages')
    //             ->whereColumn('messages.home_owner_id', 'home_owners.id')
    //             ->where('messages.is_seen', false)
    //             ->where('messages.sender_role', 'home_owner') // Only unread messages from the homeowner
    //             ->where('messages.recipient_role', 'guard');
    //     })
    //     ->get();

        $homeOwners = HomeOwner::where('status', 'confirmed')
        ->with(['messages' => function ($query) {
            // Fetch the most recent message
            $query->orderByDesc('created_at')->limit(1);
        }])
        ->orderByDesc(function ($query) {
            $query->selectRaw('max(messages.created_at)')
                ->from('messages')
                ->whereColumn('messages.home_owner_id', 'home_owners.id')
                ->where('messages.is_seen', false)
                ->where('messages.sender_role', 'home_owner')
                ->where('messages.recipient_role', 'guard');
        })
        ->get();

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
        Message::where('home_owner_id', $homeOwner->id)
        ->where('sender_role', 'home_owner') // Only homeowner's messages
        ->where('recipient_role', 'guard') // Only homeowner's messages
        ->where('is_seen', false) // Only unseen messages
        ->update(['is_seen' => true]);

    return view('guard.message', compact('homeOwners', 'homeOwner', 'messages'));
}

// Guard: Send a message from the guard to the home owner
public function guardSendMessages(Request $request, HomeOwner $homeOwner) {
    $request->validate([
        'message' => 'required|string|max:255'
    ]);

    // Get the authenticated guard's name and last name
    $guard = Auth::guard('guard')->user();
    $guardName = $guard->fname . ' ' . $guard->lname; // Adjust according to your column names

    $message = new Message();
    $message->message = $request->input('message');
    $message->home_owner_id = $homeOwner->id;
    $message->sender_role = 'guard';
    $message->recipient_role = 'home_owner';
    $message->guard_name = $guardName; // Add the guard's name to the message
    $message->save();

    HomeownerNotification::create([
        'home_owner_id' => $homeOwner->id, // Ensure the correct homeowner ID is used
        'title' => 'New Message from Guard',
        'message' => "You have received a new message from {$guardName}: {$message->message}",
        'is_read' => false,
    ]);

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

    public function markAsSeen(Request $request)
{
    $messageId = $request->input('message_id');

    // Find the message and mark it as seen
    $message = Message::find($messageId);
    if ($message) {
        $message->is_seen = true;
        $message->save();

        return response()->json(['success' => true, 'message' => 'Message marked as seen.']);
    }

    return response()->json(['success' => false, 'message' => 'Message not found.'], 404);
}

}
