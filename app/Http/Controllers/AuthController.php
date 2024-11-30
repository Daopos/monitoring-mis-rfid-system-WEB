<?php

namespace App\Http\Controllers;

use App\Models\HomeOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //

    public function homeOwnerLogin(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Find the user by email
        $user = HomeOwner::where('email', $request->email)->first();

        // Check if user exists and if password matches
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.'
            ], 401); // Return 401 Unauthorized status code
        }

        // Check if the user is confirmed
        if ($user->status !== 'confirmed') {
            return response()->json([
                'message' => 'Your account is not confirmed. Please wait for approval.'
            ], 403); // Return 403 Forbidden status code
        }

        // Generate token for the user
        $token = $user->createToken($user->fname, ['role:homeowner'])->plainTextToken;

        // Get the image URL
        $imageUrl = $user->image ? asset('storage/' . $user->image) : null;

        // Return user details, token, and image URL
        return response()->json([
            'user' => [
                'id' => $user->id,
                'fname' => $user->fname,
                'lname' => $user->lname,
                'email' => $user->email,
                'phone' => $user->phone,
                'birthdate' => $user->birthdate, // Include birthdate
                'gender' => $user->gender,
                'rfid' => $user->rfid,
                'phase' => $user->phase,
                'image' => $imageUrl, // Include the image URL
                // Add any additional fields you want to return
                'plate' => $user->plate,
                'extension' => $user->extension,
                'mname' => $user->mname,
                'block' => $user->block,
                'lot' => $user->lot,
                'number' => $user->number,
                'position' => $user->position,
                'status' => $user->status,
            ],
            'token' => $token,
        ]);
    }


    public function homeOwnerRegister(Request $request) {
        // Validate the incoming request
        $fields = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'fname' => 'required',
            'lname' => 'required',
            'phone' => 'required',
            'birthdate' => 'required|date',
            'gender' => 'required',
            'phase' => 'required',
            'plate' => 'nullable|string',
            'extension' => 'nullable|string',
            'mname' => 'nullable|string',
            'block' => 'nullable|string',
            'lot' => 'nullable|string',
            'number' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validate as an image
            'document_image' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Validate document as an image, make it nullable
        ], [
            'email.required' => 'Please provide your email address.',
            'password.required' => 'A password is required.',
            'fname.required' => 'First name cannot be blank.',
            'lname.required' => 'Last name cannot be blank.',
            'phone.required' => 'Phone number is required.',
            'birthdate.required' => 'Birthdate is required.',
            'gender.required' => 'Please specify your gender.',
            'phase.required' => 'Phase is required.',
            'plate.string' => 'Plate number must be a string.',
            'extension.string' => 'Extension must be a string.',
            'mname.string' => 'Middle name must be a string.',
            'block.string' => 'Block must be a string.',
            'lot.string' => 'Lot must be a string.',
            'number.string' => 'Number must be a string.',
        ]);

        // Set default values
        $fields['status'] = 'pending';
        $fields['position'] = 'Resident';
        $fields['password'] = bcrypt($fields['password']); // Hash password
        // Handle image upload for 'document_image' if provided
        if ($request->hasFile('document_image')) {
            $fields['document_image'] = $request->file('document_image')->store('documents', 'public');
        }

        // Handle image upload for 'image' if provided
        if ($request->hasFile('image')) {
            $fields['image'] = $request->file('image')->store('images', 'public');
        }

        try {
            // Create a new homeowner record
            $user = HomeOwner::create($fields);

            // Return success response with the created homeowner data (optional)
            return response()->json(['message' => "Homeowner registered successfully", 'data' => $user], 201);
        } catch (\Exception $e) {
            // Handle failure (e.g., database error)
            return response()->json(['message' => 'Failed to register homeowner', 'error' => $e->getMessage()], 500);
        }


    }



    public function homeOwnerLogout(Request $request) {
        // Revoke the user's token
        $request->user()->currentAccessToken()->delete();

        // Return a response confirming logout
        return response()->json([
            'message' => 'Successfully logged out.',
        ], 200); // Return 200 OK status code
    }


}