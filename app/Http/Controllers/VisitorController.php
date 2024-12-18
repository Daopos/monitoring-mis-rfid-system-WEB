<?php

namespace App\Http\Controllers;

use App\Models\HomeOwner;
use App\Models\HomeownerNotification;
use App\Models\Visitor;
use App\Models\VisitorGroup;
use App\Models\VisitorRfidRequest;
use App\Rules\UniqueRfid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class VisitorController extends Controller
{
    // Inside VisitorController.php

    public function index(Request $request)
    {
        // Get the search query from the request
        $search = $request->input('search');

        // Query visitors with homeowner and visitorGroups, and apply the search filter
        $visitors = Visitor::with(['homeowner', 'visitorGroups']) // Eager load both relationships
            ->where(function ($query) use ($search) {
                if ($search) {
                    $query->where('name', 'like', '%' . $search . '%') // Search visitor name
                          ->orWhereHas('homeowner', function ($q) use ($search) {
                              $q->whereRaw("CONCAT(fname, ' ', lname) LIKE ?", ['%' . $search . '%']); // Search homeowner's full name
                          });
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.visitors', compact('visitors', 'search'));
    }


public function approve(Request $request, $visitorId)
{
     // Validate the RFID input
     $request->validate([
        'rfid' => ['required', 'string', new UniqueRfid($visitorId)], // Pass $visitorId to exclude current visitor
    ]);

    // Find the visitor and update RFID status to approved
    $visitor = Visitor::findOrFail($visitorId);
    $visitor->update([
        'rfid' => $request->rfid,
        'status' => 'approved',
    ]);

    return redirect()->route('visitors.index')->with('success', 'RFID approved successfully.');
}

public function deny($id)
{
    // Deny the RFID request
    $visitor = Visitor::findOrFail($id);
    $visitor->update(['status' => 'denied']);

    // Add a notification for the homeowner
    HomeownerNotification::create([
        'home_owner_id' => $visitor->home_owner_id, // Ensure the correct homeowner ID is used
        'title' => 'Visitor Denied',
        'message' => "Your visitor, {$visitor->name}, has been denied by the guard.",
        'is_read' => false,
    ]);


    return redirect()->route('visitors.index')->with('success', 'RFID denied successfully.');
}




public function indexGuard(Request $request)
{
    // Get the search term from the request, if any
    $searchTerm = $request->input('search', '');

    // Eager load visitors with their homeowner, filtered by search term if provided
    $visitors = Visitor::with(['homeowner', 'visitorGroups'])
        ->where(function($query) use ($searchTerm) {
            $query->where('name', 'like', '%'.$searchTerm.'%')
                  ->orWhere('plate_number', 'like', '%'.$searchTerm.'%')
                  ->orWhere('relationship', 'like', '%'.$searchTerm.'%')
                  ->orWhereHas('homeowner', function ($q) use ($searchTerm) {
                    $q->whereRaw("CONCAT(fname, ' ', lname) LIKE ?", ['%' . $searchTerm . '%']); // Search homeowner's full name
                });
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    // Fetch all homeowners to pass to the view for the dropdown
    $homeowners = Homeowner::orderBy('fname')->orderBy('lname')->get();

    // Return the view with the visitors and homeowners
    return view('guard.visitor', compact('visitors', 'homeowners', 'searchTerm'));
}

public function storeVisitor(Request $request)
{
    // Validate the request data
    $request->validate([
        'home_owner_id' => 'required|exists:home_owners,id',
        'representative.name' => 'required|string',
        'representative.relationship' => 'required|string',
        'representative.type_id' => 'nullable|string',
        'representative.reason' => 'nullable|string',
        'representative.brand' => 'nullable|string',
        'representative.color' => 'nullable|string',
        'representative.model' => 'nullable|string',
        'representative.plate_number' => 'nullable|string',
        'representative.profile_img' => 'nullable|image|max:22048',
        'representative.valid_id' => 'nullable|image|max:22048',
        'date_visit' => 'nullable|date',
        'members' => 'array',
        'members.*.name' => 'required|string',
        'members.*.type_id' => 'nullable|string',
        'members.*.valid_id' => 'nullable|image|max:22048',
        'members.*.profile_img' => 'nullable|image|max:22048',
    ]);
    // Handle file uploads for representative
    $repProfileImg = $request->file('representative.profile_img')
        ? $request->file('representative.profile_img')->store('profile_images', 'public')
        : null;

    $repValidId = $request->file('representative.valid_id')
        ? $request->file('representative.valid_id')->store('valid_ids', 'public')
        : null;

    // Create the representative visitor
    $representative = Visitor::create([
        'home_owner_id' => $request->home_owner_id,
        'name' => $request->input('representative.name'),
        'relationship' => $request->input('representative.relationship'),
        'type_id' => $request->input('representative.type_id'),
        'reason' => $request->input('representative.reason'),
        'brand' => $request->input('representative.brand'),
        'color' => $request->input('representative.color'),
        'model' => $request->input('representative.model'),
        'plate_number' => $request->input('representative.plate_number'),
        'date_visit' => $request->input('date_visit', now()->format('Y-m-d')),
        'profile_img' => $repProfileImg,
        'valid_id' => $repValidId,
        'status' => 'requested',
        'guard' => 0,
    ]);

    // Create members (visitor groups)
if ($request->has('members')) {
    foreach ($request->input('members') as $index => $member) {
        $memberProfileImg = $request->file("members.$index.profile_img")
            ? $request->file("members.$index.profile_img")->store('profile_images', 'public')
            : null;

        $memberValidId = $request->file("members.$index.valid_id")
            ? $request->file("members.$index.valid_id")->store('valid_ids', 'public')
            : null;

        VisitorGroup::create([
            'visitor_id' => $representative->id,
            'name' => $member['name'],
            'type_id' => $member['type_id'] ?? null,
            'profile_img' => $memberProfileImg,
            'valid_id' => $memberValidId,
        ]);
    }
}

    return redirect()->route('guard.visitor')->with('success', 'Visitor and members added successfully.');
}




public function approveGuard(Request $request, $visitorId)
{
    $request->validate(['rfid' => 'required|string']);

    // Find the visitor and update RFID status to approved
    $visitor = Visitor::findOrFail($visitorId);
    $visitor->update([
        'rfid' => $request->rfid,
        'status' => 'approved',
    ]);


    // Retrieve the homeowner associated with the visitor
    $homeOwnerId = $visitor->home_owner_id;

    // Create a notification for the homeowner
    HomeownerNotification::create([
        'home_owner_id' => $homeOwnerId,
        'title' => 'Visitor Approved',
        'message' => "Your visitor, {$visitor->name}, has been approved by the guard.",
        'is_read' => false,
    ]);

    return redirect()->route('guard.visitor')->with('success', 'RFID approved successfully.');
}

public function denyGuard($id)
{
    // Deny the RFID request
    $visitor = Visitor::findOrFail($id);
    $visitor->update(['status' => 'denied']);

    HomeownerNotification::create([
        'home_owner_id' => $visitor->home_owner_id, // Ensure the correct homeowner ID is used
        'title' => 'Visitor Denied',
        'message' => "Your visitor, {$visitor->name}, has been denied by the guard.",
        'is_read' => false,
    ]);


    return redirect()->route('guard.visitor')->with('success', 'RFID denied successfully.');
}


public function ReturnVisitorGuard($id)
{
    // Find the visitor by ID or fail
    $visitor = Visitor::findOrFail($id);

    // Update the status to 'return' and set RFID to null
    $visitor->update([
        'status' => 'return',
        'rfid' => null, // Ensure the RFID column is set to null
        'guard' => 1,
    ]);

    HomeownerNotification::create([
        'home_owner_id' => $visitor->home_owner_id, // Ensure the correct homeowner ID is used
        'title' => 'Visitor Left Subdivision',
        'message' => "Your visitor, {$visitor->name}, has successfully exited the subdivision.",
        'is_read' => false,
    ]);

    // Redirect back with a success message
    return redirect()->route('guard.visitor')->with('success', 'RFID return successfully.');
}

public function deleteVisitorGuard($id)
{
    try {
        // Attempt to find the visitor by ID
        $visitor = Visitor::find($id);

        // Check if the visitor exists
        if (!$visitor) {
            return redirect()->route('guard.visitor')->with('error', 'Visitor not found');
        }

        // Delete the visitor record
        $visitor->delete();

        // Return success message
        return redirect()->route('guard.visitor')->with('success', 'Visitor deleted successfully');
    } catch (\Exception $e) {
        // Catch any errors and return a failure message
        return redirect()->route('guard.visitor')->with('error', 'An error occurred while deleting the visitor');
    }
}

public function requestRfid(Request $request) {
    try {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'plate_number' => 'nullable|string',
            'expiry_date' => 'required|date',
        ]);

        $id = Auth::user()->id;

        $visitor = new Visitor();
        $visitor->home_owner_id = $id; // Get the currently authenticated homeowner
        $visitor->name = $validatedData['name'];
        $visitor->plate_number = $validatedData['plate_number'];
        $visitor->expiry_date = $validatedData['expiry_date'];
        $visitor->requested_at = now(); // Automatically set requested time
        $visitor->status = 'pending'; // Set default status
        $visitor->save();

        return response()->json(['message' => 'RFID request submitted successfully'], 200);
    } catch (\Exception $e) {
        // Log the exception message
        \Log::error('RFID request error: ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => 'An error occurred while processing your request.'], 500);
    }
}

public function getVisitors(Request $request)
{
    $id = Auth::user()->id;

    // Fetch all visitors for the authenticated homeowner, ordered by created_at descending
    $visitors = Visitor::where('home_owner_id', $id)
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($visitor) {
            return [
                'id' => $visitor->id,
                'name' => $visitor->name,
                'plate_number' => $visitor->plate_number,
                'rfid' => $visitor->rfid,
                'expiry_date' => $visitor->expiry_date,
                'status' => $visitor->status,
            ];
        });

    return response()->json($visitors);
}



//API

public function createVisitorAPI(Request $request)
{
    // Validate the request data
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'brand' => 'nullable|string|max:255',
        'color' => 'nullable|string|max:255',
        'model' => 'nullable|string|max:255',
        'plate_number' => 'nullable|string|max:255',
        'rfid' => 'nullable|string|max:255',
        'relationship' => 'nullable|string|max:255',
        'date_visit' => 'nullable|date',
        'status' => 'nullable|in:pending,approved,denied',
        'members' => 'array',
        'members.*.name' => 'required|string',
    ]);

    // Set the status to 'pending' if it's not provided
    $status = $validated['status'] ?? 'pending';

    // Get the authenticated user's home_owner_id
    $homeOwnerId = Auth::user()->id;

    // Create the visitor record with the authenticated user's home_owner_id
    $visitor = Visitor::create(array_merge($validated, ['status' => $status, 'home_owner_id' => $homeOwnerId]));

    // Handle creation of the members (visitor group)
    if ($request->has('members')) {
        foreach ($request->input('members') as $index => $member) {
            VisitorGroup::create([
                'visitor_id' => $visitor->id,
                'name' => $member['name'],
            ]);
        }
    }

    return response()->json($visitor, 201);
}




public function getVisitorAPI()
{
    $homeOwnerId = Auth::user()->id;

    $visitors = Visitor::with('visitorGroups')->where('home_owner_id', $homeOwnerId)
        ->get()
        ->map(function ($visitor) {
            // Check if the visitor has valid_id and profile_img, then generate full URLs
            if ($visitor->valid_id) {
                $visitor->valid_id_url = URL::to(Storage::url($visitor->valid_id)); // Prepend full URL
            }
            if ($visitor->profile_img) {
                $visitor->profile_img_url = URL::to(Storage::url($visitor->profile_img)); // Prepend full URL
            }

            // Loop through the visitorGroups to add full URLs for each group
            $visitor->visitorGroups->map(function ($group) {
                if ($group->valid_id) {
                    $group->valid_id_url = URL::to(Storage::url($group->valid_id)); // Prepend full URL
                }
                if ($group->profile_img) {
                    $group->profile_img_url = URL::to(Storage::url($group->profile_img)); // Prepend full URL
                }
                return $group;
            });

            return $visitor;
        });

    return response()->json($visitors);
}


// Get a single visitor for the authenticated home_owner
public function show($id)
{
    $homeOwnerId = Auth::id(); // Get the authenticated user's home_owner_id
    $visitor = Visitor::where('id', $id)->where('home_owner_id', $homeOwnerId)->firstOrFail();

    return response()->json($visitor);
}

// Update an existing visitor
public function updateVisitorAPI(Request $request, $id)
{
    // Get the authenticated user's home_owner_id
    $homeOwnerId = Auth::user()->id;

    // Find the visitor associated with the authenticated user
    $visitor = Visitor::where('id', $id)->where('home_owner_id', $homeOwnerId)->firstOrFail();

    // Validate the request data
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'brand' => 'nullable|string|max:255',
        'color' => 'nullable|string|max:255',
        'model' => 'nullable|string|max:255',
        'plate_number' => 'nullable|string|max:255',
        'rfid' => 'nullable|string|max:255',
        'relationship' => 'nullable|string|max:255',
        'date_visit' => 'nullable|date',
        'status' => 'nullable|in:pending,approved,denied',
        'members' => 'array',
        'members.*.name' => 'required|string|max:255',
    ]);

    // Update the visitor record
    $visitor->update($validated);

    // Handle visitor group members update
    if ($request->has('members')) {
        // Get the existing members for this visitor
        $existingMembers = VisitorGroup::where('visitor_id', $visitor->id)->get();

        // Create a list of new member names from the request
        $newMembers = collect($request->input('members'))->pluck('name')->toArray();

        // Remove members that are not in the new list
        foreach ($existingMembers as $existingMember) {
            if (!in_array($existingMember->name, $newMembers)) {
                $existingMember->delete();
            }
        }

        // Add new members that are not in the existing list
        foreach ($newMembers as $newMemberName) {
            if (!in_array($newMemberName, $existingMembers->pluck('name')->toArray())) {
                VisitorGroup::create([
                    'visitor_id' => $visitor->id,
                    'name' => $newMemberName,
                ]);
            }
        }
    }

    return response()->json([
        'message' => 'Visitor updated successfully.',
        'visitor' => $visitor,
    ]);
}


// Delete a visitor
public function deleteVisitorAPI($id)
{
    $homeOwnerId = Auth::user()->id;
    $visitor = Visitor::where('id', $id)->where('home_owner_id', $homeOwnerId)->firstOrFail();
    $visitor->delete();

    return response()->json(['message' => 'Visitor deleted successfully']);
}


public function approvedVisitorAPI($id)
{
    $homeOwnerId = Auth::user()->id;

    // Find the visitor record with the specified ID and home owner ID
    $visitor = Visitor::where('id', $id)->where('home_owner_id', $homeOwnerId)->firstOrFail();

    // Update the visitor's status to 'pending'
    $visitor->status = 'pending';
    $visitor->save();

    return response()->json(['message' => 'Visitor status updated to pending successfully']);
}


public function rejectVisitorAPI($id)
{
    $homeOwnerId = Auth::user()->id;

    // Find the visitor record with the specified ID and home owner ID
    $visitor = Visitor::where('id', $id)->where('home_owner_id', $homeOwnerId)->firstOrFail();

    // Update the visitor's status to 'denied'
    $visitor->status = 'denied';
    $visitor->save();

    return response()->json(['message' => 'Visitor status updated to denied successfully']);
}

}