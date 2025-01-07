<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'brand' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'car_type' => 'nullable|string|max:255',
            'or_number' => 'nullable|string|max:255',
            'cr_number' => 'nullable|string|max:255',
            'plate_number' => 'nullable|string|max:255|unique:vehicles,plate_number',
            'vehicle_img' => 'nullable|image|mimes:jpeg,png,jpg|max:22048', // Validate image
            'or_img' => 'nullable|image|mimes:jpeg,png,jpg|max:22048', // Validate image
            'cr_img' => 'nullable|image|mimes:jpeg,png,jpg|max:22048', // Validate image
        ]);

        // Get the authenticated homeowner's ID
        $id = Auth::user()->id;

        // Handle file uploads
        if ($request->hasFile('vehicle_img')) {
            $path = $request->file('vehicle_img')->store('files/vehicle_img', 'public');
            $validatedData['vehicle_img'] = $path;
        }

        if ($request->hasFile('or_img')) {
            $path = $request->file('or_img')->store('files/or_img', 'public');
            $validatedData['or_img'] = $path;
        }

        if ($request->hasFile('cr_img')) {
            $path = $request->file('cr_img')->store('files/cr_img', 'public');
            $validatedData['cr_img'] = $path;
        }

        $validatedData['home_owner_id'] = $id;

        $vehicle = Vehicle::create($validatedData);

        return response()->json($vehicle, 201);
    }


    public function update(Request $request, $id)
    {
        $homeOwnerId = Auth::user()->id;
        \Log::info('Incoming request data:', $request->all());

        try {
            // Fetch the vehicle to update
            $vehicle = Vehicle::where('id', $id)
                              ->where('home_owner_id', $homeOwnerId)
                              ->firstOrFail();

            // Validate the incoming request
            $validatedData = $request->validate([
                'brand' => 'nullable|string|max:255',
                'color' => 'nullable|string|max:255',
                'model' => 'nullable|string|max:255',
                'car_type' => 'nullable|string|max:255',
                'or_number' => 'nullable|string|max:255',
                'cr_number' => 'nullable|string|max:255',
                'plate_number' => 'nullable|string|max:255|unique:vehicles,plate_number,' . $id,
                'vehicle_img' => 'nullable|image|mimes:jpeg,png,jpg|max:33048',
                'or_img' => 'nullable|image|mimes:jpeg,png,jpg|max:22048',
                'cr_img' => 'nullable|image|mimes:jpeg,png,jpg|max:22048',
            ]);

            // Handle file uploads if they are present in the request
            if ($request->hasFile('vehicle_img')) {
                \Log::info('Vehicle image file detected.');
                // Delete the old image if it exists
                if ($vehicle->vehicle_img) {
                    Storage::disk('public')->delete($vehicle->vehicle_img);
                    \Log::info('Old vehicle image deleted.');
                }

                try {
                    // Store the new image and update the path
                    $path = $request->file('vehicle_img')->store('files/vehicle_img', 'public');
                    $validatedData['vehicle_img'] = $path;
                    \Log::info('New vehicle image stored at: ' . $path);
                } catch (\Exception $e) {
                    \Log::error('Error storing vehicle image: ' . $e->getMessage());
                    return response()->json(['error' => 'Failed to upload vehicle image'], 500);
                }
            }

            if ($request->hasFile('or_img')) {
                \Log::info('OR image file detected.');
                // Delete the old image if it exists
                if ($vehicle->or_img) {
                    Storage::disk('public')->delete($vehicle->or_img);
                    \Log::info('Old OR image deleted.');
                }

                try {
                    // Store the new image and update the path
                    $path = $request->file('or_img')->store('files/or_img', 'public');
                    $validatedData['or_img'] = $path;
                    \Log::info('New OR image stored at: ' . $path);
                } catch (\Exception $e) {
                    \Log::error('Error storing OR image: ' . $e->getMessage());
                    return response()->json(['error' => 'Failed to upload OR image'], 500);
                }
            }

            if ($request->hasFile('cr_img')) {
                \Log::info('CR image file detected.');
                // Delete the old image if it exists
                if ($vehicle->cr_img) {
                    Storage::disk('public')->delete($vehicle->cr_img);
                    \Log::info('Old CR image deleted.');
                }

                try {
                    // Store the new image and update the path
                    $path = $request->file('cr_img')->store('files/cr_img', 'public');
                    $validatedData['cr_img'] = $path;
                    \Log::info('New CR image stored at: ' . $path);
                } catch (\Exception $e) {
                    \Log::error('Error storing CR image: ' . $e->getMessage());
                    return response()->json(['error' => 'Failed to upload CR image'], 500);
                }
            }

            // Update the vehicle with the new data
            $vehicle->update($validatedData);

            \Log::info('Vehicle updated successfully.');
            return response()->json($vehicle);

        } catch (\Exception $e) {
            \Log::error('Error updating vehicle: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update vehicle: ' . $e->getMessage()], 500);
        }
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

        // Generate the full URL for the images
        $vehicles->map(function ($vehicle) {
            $vehicle->vehicle_img = url('storage/' . $vehicle->vehicle_img);
            $vehicle->or_img = url('storage/' . $vehicle->or_img);
            $vehicle->cr_img = url('storage/' . $vehicle->cr_img);
            return $vehicle;
        });

        return response()->json($vehicles);
    }

}
