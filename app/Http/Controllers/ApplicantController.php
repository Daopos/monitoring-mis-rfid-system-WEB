<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\HomeOwner;
use App\Models\Officer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicantController extends Controller
{

    public function indexAdmin()
    {
        // Fetch all applicants with their neighbors, including related homeowner details
        $applicants = Applicant::with('neighbors.homeowner')->get();

        // Pass the data to a view
        return view('admin.applicants', compact('applicants'));
    }

    public function indexGuard()
    {
        // Fetch all applicants with their neighbors, including related homeowner details
        $applicants = Applicant::with('neighbors.homeowner')->where('status', 'Approved')->get();

        // Pass the data to a view
        return view('guard.applicants', compact('applicants'));
    }


    public function approve($id)
{
    $applicant = Applicant::findOrFail($id);
    $applicant->status = 'Approved';
    $applicant->save();

    return redirect()->back()->with('success', 'Applicant approved successfully.');
}

public function reject($id)
{
    $applicant = Applicant::findOrFail($id);
    $applicant->status = 'Rejected';
    $applicant->save();

    return redirect()->back()->with('success', 'Applicant rejected successfully.');
}

public function print($id)
{
    $applicant = Applicant::with('neighbors')->findOrFail($id); // Fetch the single applicant with neighbors
    $president = Officer::with('homeowner')->where('position', 'President')->first(); // Fetch officers with the position "President"

    return view('admin.printpermit', compact('applicant', 'president')); // Pass the applicant to the view
}




    public function storeAPI(Request $request)
{
    $homeOwnerId = Auth::user()->id; // Authenticated user ID

    // Validate the input fields
    $validated = $request->validate([
        'mobilization_date' => 'required|date',
        'completion_date' => 'required|date',
        'project_description' => 'required|string',
        'selection' => 'required|string',
        'neighbors' => 'required|array|min:3', // Enforce at least 3 neighbors
        'neighbors.*.homeowner_id' => 'required|exists:home_owners,id',
        'neighbors.*.status' => 'nullable|string',
    ]);

    // Get the authenticated user's ID and assign it to homeowner_id
    $validated['homeowner_id'] = $homeOwnerId;

    // Add the current date as the application date
    $validated['application_date'] = now();

    // Create the applicant
    $applicant = Applicant::create($validated);

    // Create neighbors
    $applicant->neighbors()->createMany($validated['neighbors']);

    return response()->json([
        'message' => 'Applicant and neighbors created successfully!',
    ], 201);
}
public function updateAPI(Request $request, $id)
{

    // Validate the input fields
    $validated = $request->validate([
        'mobilization_date' => 'required|date',
        'completion_date' => 'required|date',
        'project_description' => 'required|string',
        'selection' => 'required|string',
        'neighbors' => 'required|array|min:3', // Enforce at least 3 neighbors
        'neighbors.*.homeowner_id' => 'required|exists:home_owners,id',
        'neighbors.*.status' => 'nullable|string',
    ]);

    // Find the applicant by ID and ensure it belongs to the authenticated user
    $applicant = Applicant::where('id', $id)
        ->first();

    if (!$applicant) {
        return response()->json([
            'message' => 'Applicant not found or unauthorized access.',
        ], 404);
    }

    // Update the applicant
    $applicant->update([
        'mobilization_date' => $validated['mobilization_date'],
        'completion_date' => $validated['completion_date'],
        'project_description' => $validated['project_description'],
        'selection' => $validated['selection'],
    ]);

    // Update neighbors
    $applicant->neighbors()->delete(); // Delete existing neighbors
    foreach ($validated['neighbors'] as $neighbor) {
        $applicant->neighbors()->create($neighbor); // Create each neighbor one at a time
    }


    return response()->json([
        'message' => 'Applicant and neighbors updated successfully!',
    ], 200);
}
public function deleteAPI($id)
{
    // Find the applicant by ID
    $applicant = Applicant::find($id);

    // Check if the applicant exists
    if (!$applicant) {
        return response()->json([
            'message' => 'Applicant not found.',
        ], 404);
    }

    // Delete associated neighbors
    $applicant->neighbors()->delete();

    // Delete the applicant
    $applicant->delete();

    return response()->json([
        'message' => 'Applicant and neighbors deleted successfully!',
    ], 200);
}
public function show($id)
{
    // Fetch the applicant by ID
    $applicant = Applicant::with('neighbors.homeowner')->find($id);

    if (!$applicant) {
        return response()->json([
            'message' => 'Applicant not found',
        ], 404);
    }

    return response()->json([
        'data' => $applicant,
    ], 200);
}

public function getNeighborAPI() {
    $neighbors = HomeOwner::select('id', 'fname', 'mname', 'lname')
                          ->orderBy('fname')  // Sort by fname
                          ->get();  // or use pagination if the list is large
    return response()->json($neighbors);
}


public function getApplicantWithNeighbors()
{
    // Get the authenticated user's ID
    $homeOwnerId = Auth::user()->id;

    // Retrieve all applicants for the authenticated user along with their associated neighbors
    $applicants = Applicant::with('neighbors.homeowner')->where('homeowner_id', $homeOwnerId)->get();

    // Check if applicants exist
    if ($applicants->isEmpty()) {
        return response()->json([
            'message' => 'No applicants found for this user.',
        ], 200);
    }

    // Return the applicants data along with neighbors
    return response()->json([
        'applicants' => $applicants,
    ], 200);
}




// public function getNeighborAPI(Request $request) {
//     $query = HomeOwner::query();

//     // Filter based on search term
//     if ($request->has('search')) {
//         $searchTerm = $request->input('search');
//         $query->where(function ($q) use ($searchTerm) {
//             $q->where('fname', 'like', "%$searchTerm%")
//               ->orWhere('lname', 'like', "%$searchTerm%");
//         });
//     }

//     $neighbors = $query->get();  // or use pagination if the list is large
//     return response()->json($neighbors);
// }

}
