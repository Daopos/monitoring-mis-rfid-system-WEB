<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'brand' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'plate_number' => 'nullable|string|max:255|unique:vehicles,plate_number',
        ]);

        // Get the authenticated homeowner's ID

        $id = Auth::user()->id;

        $validatedData['home_owner_id'] = $id;

        $vehicle = Vehicle::create($validatedData);

        return response()->json($vehicle, 201);
    }

    public function update(Request $request, $id)
    {
    $homeOwnerId = Auth::user()->id;

        $vehicle = Vehicle::where('id', $id)
                          ->where('home_owner_id', $homeOwnerId)
                          ->firstOrFail();

        $validatedData = $request->validate([
            'brand' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'plate_number' => 'nullable|string|max:255|unique:vehicles,plate_number,' . $id,
        ]);

        $vehicle->update($validatedData);

        return response()->json($vehicle);
    }

    // DELETE /vehicles/{id} - Delete a specific vehicle for the authenticated homeowner
    public function destroy($id)
    {
    $homeOwnerId = Auth::user()->id;

        $vehicle = Vehicle::where('id', $id)
                          ->where('home_owner_id', $homeOwnerId)
                          ->firstOrFail();

        $vehicle->delete();

        return response()->json(['message' => 'Vehicle deleted successfully']);
    }




    public function getVehicles()
{
    // Get the authenticated homeowner's ID
    $homeOwnerId = Auth::user()->id;

    // Retrieve vehicles for the authenticated homeowner
    $vehicles = Vehicle::where('home_owner_id', $homeOwnerId)->get();

    return response()->json($vehicles);
}
}