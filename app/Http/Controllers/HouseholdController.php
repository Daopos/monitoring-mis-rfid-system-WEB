<?php

namespace App\Http\Controllers;

use App\Models\Household;
use App\Rules\UniqueRfid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HouseholdController extends Controller
{

    public function updateRfid(Household $household, Request $request)
    {
        // Validate the RFID input
        $validator = Validator::make($request->all(), [
            'rfid' => ['nullable', 'string', new UniqueRfid($household->id)], // Pass $household->id to exclude current record
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Update the RFID if validation passes
        $household->rfid = $request->input('rfid');
        $household->save();

        return back()->with('success', 'RFID updated successfully.');
    }
  // Create a new household
  public function createMemberAPI(Request $request)
{
    $homeOwnerId = Auth::user()->id; // Authenticated user ID

    $request->validate([
        'name' => 'required|string|max:255',
        'relationship' => 'required|string|max:255',
        'age' => 'required|integer|min:0',
        'gender' => 'required|string|max:50',
    ]);

    $household = Household::create([
        'home_owner_id' => $homeOwnerId, // Ensure household is tied to the authenticated user
        'name' => $request->name,
        'relationship' => $request->relationship,
        'age' => $request->age,
        'gender' => $request->gender,
    ]);

    return response()->json([
        'message' => 'Household created successfully',
        'data' => $household,
    ], 201);
}


  // Update a household
  public function updateMemberAPI(Request $request, $id)
{
    $homeOwnerId = Auth::user()->id; // Authenticated user ID

    $household = Household::where('id', $id)
                          ->where('home_owner_id', $homeOwnerId) // Ensure ownership
                          ->first();

    if (!$household) {
        return response()->json(['message' => 'Household not found or not authorized'], 404);
    }

    $request->validate([
        'name' => 'sometimes|string|max:255',
        'relationship' => 'sometimes|string|max:255',
        'age' => 'sometimes|integer|min:0',
        'gender' => 'sometimes|string|max:50',
    ]);

    $household->update($request->only(['name', 'relationship', 'age', 'gender']));

    return response()->json([
        'message' => 'Household updated successfully',
        'data' => $household,
    ], 200);
}

  // Delete a household
  public function deleteMemberAPI($id)
{
    $homeOwnerId = Auth::user()->id; // Authenticated user ID

    $household = Household::where('id', $id)
                          ->where('home_owner_id', $homeOwnerId) // Ensure ownership
                          ->first();

    if (!$household) {
        return response()->json(['message' => 'Household not found or not authorized'], 404);
    }

    $household->delete();

    return response()->json(['message' => 'Household deleted successfully'], 200);
}

public function getMembersAPI()
{
    $homeOwnerId = Auth::user()->id; // Authenticated user ID

    // Retrieve all households belonging to the authenticated homeowner
    $households = Household::where('home_owner_id', $homeOwnerId)->get();

    return response()->json([
        'message' => 'Household members retrieved successfully',
        'data' => $households,
    ], 200);
}


public function adminIndexHousehold(Request $request)
{
    // Retrieve search and RFID filter inputs
    $search = $request->input('search');
    $rfidFilter = $request->input('rfid_filter');

    // Query households with homeowner relationship
    $query = Household::with('homeowner');

    // Filter by search (household name or homeowner name)
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhereHas('homeowner', function ($q) use ($search) {
                  $q->where('fname', 'like', '%' . $search . '%')
                    ->orWhere('lname', 'like', '%' . $search . '%');
              });
        });
    }

    // Filter by RFID status
    if ($rfidFilter === 'with_rfid') {
        $query->whereNotNull('rfid');
    } elseif ($rfidFilter === 'without_rfid') {
        $query->whereNull('rfid');
    }
       // Paginate the results (10 items per page)
       $households = $query->paginate(10);

    // Pass data to the Blade view
    return view('admin.households', compact('households'));
}

}