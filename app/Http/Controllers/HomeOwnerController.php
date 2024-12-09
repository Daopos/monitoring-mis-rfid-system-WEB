<?php

namespace App\Http\Controllers;

use App\Models\HomeOwner;
use App\Notifications\ApprovalNotification;
use App\Rules\UniqueRfid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HomeOwnerController extends Controller
{
    //

    public function registrationForm() {

        return view('admin.adminrfidreg');
    }

    public function register(Request $request)
    {
        // Validate inputs based on the schema fields
        $validator = Validator::make($request->all(), [
            'fname' => 'required|string',
            'lname' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email|unique:home_owners,email',
            'plate' => 'nullable|string',
            'extension' => 'nullable|string',
            'mname' => 'nullable|string',
            'birthdate' => 'required|date',
            'gender' => 'required|string',
            'rfid' => ['nullable', 'string', new UniqueRfid()], // Pass $id to the custom rule
            'image' => 'nullable|image|max:2048', // Optional, with max size 2MB
            'position' => 'nullable|string', // Optional, but will default to 'Resident'
            'password' => 'required|string|min:8',
            'phase' => 'required|string',
            'block' => 'required|string',
            'lot' => 'required|string',
            'document_image' => 'required|image|max:2048', // Optional, with max size 2MB for document image
        ]);

        if ($validator->fails()) {
            // Redirect back with validation errors
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Prepare data for saving
        $homeOwnerData = $request->except('password', 'image', 'document_image');
        $homeOwnerData['password'] = bcrypt($request->password); // Encrypt password
        $homeOwnerData['status'] = 'confirmed'; // Default status
        $homeOwnerData['position'] = $request->position ?? 'Resident'; // Set default position to 'Resident' if not provided

        // Handle image upload if an image was uploaded
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('images', 'public'); // Store in 'public/images' directory
            $homeOwnerData['image'] = $imagePath;
        }

        // Handle document image upload if a document image was uploaded
        if ($request->hasFile('document_image')) {
            $documentImage = $request->file('document_image');
            $documentImagePath = $documentImage->store('document_images', 'public'); // Store in 'public/document_images' directory
            $homeOwnerData['document_image'] = $documentImagePath;
        }

        // Create the homeowner
        HomeOwner::create($homeOwnerData);

        return redirect()->back()->with('success', 'Successfully created');
    }
    public function edit($id)
{
    $homeOwner = HomeOwner::findOrFail($id);
    return view('admin.adminedithomeowner', compact('homeOwner'));
}
public function update(Request $request, $id)
{
    // Validate inputs based on the schema fields
    $validator = Validator::make($request->all(), [
        'fname' => 'required|string',
        'lname' => 'required|string',
        'phone' => 'required|string',
        'email' => 'required|email|unique:home_owners,email,' . $id, // Ignore current email for uniqueness check
        'plate' => 'nullable|string',
        'extension' => 'nullable|string',
        'mname' => 'nullable|string',
        'birthdate' => 'required|date',
        'gender' => 'required|string',
        'rfid' => ['nullable', 'string', new UniqueRfid($id)], // Pass $id to the custom rule
        'image' => 'nullable|image|max:2048', // Optional with max size 2MB
        'position' => 'nullable|string',
        'password' => 'nullable|string|min:8', // Optional password update
        'phase' => 'required|string',
        'block' => 'required|string',
        'lot' => 'required|string',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $homeOwner = HomeOwner::findOrFail($id);
    $homeOwnerData = $request->except('password', 'image');

    // Encrypt password if provided
    if ($request->filled('password')) {
        $homeOwnerData['password'] = bcrypt($request->password);
    }

    // Handle image upload if an image was uploaded
    if ($request->hasFile('image')) {
        // Optionally delete the old image
        if ($homeOwner->image) {
            Storage::disk('public')->delete($homeOwner->image); // Delete old image
        }

        $image = $request->file('image');
        $imagePath = $image->store('images', 'public');
        $homeOwnerData['image'] = $imagePath;
    }

    // Set the default status if not provided
    $homeOwnerData['status'] = $homeOwnerData['status'] ?? 'confirmed';

    $homeOwner->update($homeOwnerData);

    return redirect()->back()->with('success', 'Successfully updated');
}
public function destroy($id)
{
    $homeOwner = HomeOwner::findOrFail($id);

    // Delete the associated image file if it exists
    if ($homeOwner->image) {
        Storage::disk('public')->delete($homeOwner->image);
    }

    $homeOwner->delete();

    return redirect()->back()->with('success', 'Successfully deleted');
}

public function getAllHomeOwner(Request $request) {
    // Get the search query and RFID filter from the request
    $search = $request->input('search');
    $rfidFilter = $request->input('rfid_filter');

    // Query homeowners with status 'confirmed', applying the search and RFID filter if provided
    $homeowners = HomeOwner::where('status', 'confirmed')
        ->when($search, function ($query, $search) {
            return $query->where('fname', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
        })
        ->when($rfidFilter, function ($query, $rfidFilter) {
            // Apply RFID filter: show with or without RFID
            if ($rfidFilter == 'with_rfid') {
                return $query->whereNotNull('rfid');
            } elseif ($rfidFilter == 'without_rfid') {
                return $query->whereNull('rfid');
            }
        })
        ->paginate(10); // Paginate by 10

    return view('admin.adminhomeownerlist', compact('homeowners'));
}

public function getHomeOwnerPending(Request $request) {
    $query = HomeOwner::where('status', 'pending');

    // Add search functionality for first name and last name
    if ($request->has('search') && $request->search != '') {
        $query->where(function ($q) use ($request) {
            $q->where('fname', 'like', '%' . $request->search . '%')
              ->orWhere('lname', 'like', '%' . $request->search . '%');
        });
    }

    // Paginate the results (10 per page)
    $homeowners = $query->paginate(10);

    // Append search query to pagination links
    $homeowners->appends(['search' => $request->search]);

    // Loop over the homeowners and generate image URLs if available
    foreach ($homeowners as $homeowner) {
        $homeowner->image_url = $homeowner->image ? asset('storage/' . $homeowner->image) : null;
        $homeowner->document_image_url = $homeowner->document_image ? asset('storage/' . $homeowner->document_image) : null;
    }

    return view('admin.homeownerpending')->with('homeowners', $homeowners);
}


    public function confirm($id)
    {
        // Find the homeowner by ID
        $homeowner = HomeOwner::findOrFail($id);

        // Update the status to 'confirmed'
        $homeowner->status = 'confirmed';
        $homeowner->save();


        $notification = new ApprovalNotification($homeowner);
        $notification->sendLoginNotification();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Homeowner confirmed successfully.');
    }


    public function getProfileAPI()
{
    $id = Auth::user()->id;

    $homeOwner = HomeOwner::find($id);

    if (!$homeOwner) {
        return response()->json(['message' => 'Profile not found'], 404);
    }

    // Get the image URL
    $imageUrl = $homeOwner->image ? url(Storage::url($homeOwner->image)) : null;
    $documentImageUrl = $homeOwner->document_image ? url(Storage::url($homeOwner->document_image)) : null;

    // Return profile data aligning with the schema
    return response()->json([
        'id' => $homeOwner->id,
        'fname' => $homeOwner->fname,
        'lname' => $homeOwner->lname,
        'phone' => $homeOwner->phone,
        'email' => $homeOwner->email,
        'plate' => $homeOwner->plate, // Include plate
        'extension' => $homeOwner->extension, // Include extension
        'mname' => $homeOwner->mname, // Include middle name
        'birthdate' => $homeOwner->birthdate,
        'gender' => $homeOwner->gender,
        'rfid' => $homeOwner->rfid,
        'image' => $imageUrl,
        'position' => $homeOwner->position,
        'status' => $homeOwner->status,
        'phase' => $homeOwner->phase,
        'block' => $homeOwner->block, // Include block
        'lot' => $homeOwner->lot, // Include lot
        'document_image' => $documentImageUrl, // Include document image
    ]);
}

    // Function to update the profile name
    public function updateProfileAPI(Request $request)
{

    $id = Auth::user()->id;

    $request->validate([
        'fname' => 'required|string|max:255',
        'lname' => 'required|string|max:255',
        'phone' => 'nullable|string|max:20',
        'email' => 'required|email|unique:home_owners,email,' . $id, // Ignore current email for uniqueness check
        'birthdate' => 'nullable|date',
        'gender' => 'nullable|string|max:10',
        'extension' => 'nullable|string|max:10',
        'mname' => 'nullable|string|max:255',
        'phase' => 'nullable|string|max:20',
        'block' => 'nullable|string|max:20',
        'lot' => 'nullable|string|max:20',
        'status' => 'nullable|string|max:50',
        'position' => 'nullable|string|max:100',
        'document_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // Validate as image
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // Validate as image
    ]);

    $homeOwner = HomeOwner::find($id);

    if (!$homeOwner) {
        return response()->json(['message' => 'Profile not found'], 404);
    }

    // Update fields if they are provided in the request
    $homeOwner->fname = $request->fname;
    $homeOwner->lname = $request->lname;
    $homeOwner->phone = $request->phone;
    $homeOwner->email = $request->email;
    $homeOwner->birthdate = $request->birthdate;
    $homeOwner->gender = $request->gender;
    $homeOwner->extension = $request->extension;
    $homeOwner->mname = $request->mname;
    $homeOwner->phase = $request->phase;
    $homeOwner->block = $request->block;
    $homeOwner->lot = $request->lot;
    $homeOwner->status = $request->status;
    $homeOwner->position = $request->position;

    // Handle the document_image upload
    if ($request->hasFile('document_image')) {
        $path = $request->file('document_image')->store('documents', 'public');
        $homeOwner->document_image = $path;
    }

    // Handle the image upload
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('images', 'public');
        $homeOwner->image = $path;
    }

    $homeOwner->save();

    return response()->json(['message' => 'Profile updated successfully']);
}




    //guard

    public function getAllHomeOwnerGuard(Request $request)
{
    // Get the search query and status filter from the request
    $search = $request->input('search');
    $statusFilter = $request->input('status'); // 'in' or 'out'

    // Query homeowners with their status determined by the latest gate monitor entry
    $homeowners = HomeOwner::where('status', 'confirmed')
        ->when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('fname', 'like', "%{$search}%")
                  ->orWhere('lname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        })
        ->when($statusFilter, function ($query, $statusFilter) {
            if ($statusFilter === 'in') {
                // Filter for homeowners currently "in"
                return $query->whereHas('gateMonitors', function ($q) {
                    $q->whereNull('out'); // No "out" time means they are inside
                });
            } elseif ($statusFilter === 'out') {
                // Filter for homeowners currently "out"
                return $query->whereHas('gateMonitors', function ($q) {
                    $q->whereNotNull('out'); // "Out" time means they are outside
                });
            }
        })
        ->with(['gateMonitors' => function ($query) {
            $query->orderBy('in', 'desc'); // Get the latest gate monitor entry
        }])
        ->paginate(10); // Paginate results (10 items per page)

    return view('guard.homeowner')->with('homeowners', $homeowners);
}



    public function rfidlist()
    {
        // Fetch homeowners with RFID (excluding null values)
        $homeowners = HomeOwner::whereNotNull('rfid')->get();

        return view('admin.rfidlist', compact('homeowners'));
    }
}