<?php

namespace App\Http\Controllers;

use App\Models\Outsider;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class OutsiderController extends Controller
{

    public function indexAdmin(Request $request)
    {
        $query = Outsider::query();

        // Search by name
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by date range
        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('in', [
                $request->from_date . ' 00:00:00',
                $request->to_date . ' 23:59:59'
            ]);
        }

        // Sort by the latest entry first
        $query->orderBy('in', 'desc');

        // Fetch outsiders with pagination, preserving search and filter parameters
        $outsiders = $query->paginate(10);

        // Return view with the query parameters for search and filters
        return view('admin.outsiderentry', [
            'outsiders' => $outsiders,
            'search' => $request->search,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date
        ]);
    }

    public function index(Request $request)
{
    $query = Outsider::query();

    // Search by name
    if ($request->has('search') && $request->search) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // Filter by date range
    if ($request->has('from_date') && $request->has('to_date')) {
        $query->whereBetween('in', [
            $request->from_date . ' 00:00:00',
            $request->to_date . ' 23:59:59'
        ]);
    }

    // Sort by the latest entry first
    $query->orderBy('in', 'desc');

    // Fetch outsiders with pagination, preserving search and filter parameters
    $outsiders = $query->paginate(10);

    // Return view with the query parameters for search and filters
    return view('guard.outsider', [
        'outsiders' => $outsiders,
        'search' => $request->search,
        'from_date' => $request->from_date,
        'to_date' => $request->to_date
    ]);
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'type' => 'required|string',
        'vehicle_type' => 'nullable|string',
        'brand' => 'nullable|string',
        'color' => 'nullable|string',
        'model' => 'nullable|string',
        'plate_number' => 'nullable|string',
        'rfid' => 'nullable|string',
        'type_id' => 'required|string|max:255',
        'valid_id' => 'nullable|image|max:22048',
        'profile_img' => 'nullable|image|max:22048',
    ]);

    if ($request->type === 'Other') {
        $request->validate([
            'other_type' => 'required|string|max:255',
        ]);
    }

    // Handle file uploads if provided
    $validIdPath = $request->hasFile('valid_id') ? $request->file('valid_id')->store('valid_ids', 'public') : null;
    $profileImgPath = $request->hasFile('profile_img') ? $request->file('profile_img')->store('profile_images', 'public') : null;

    // Create a new outsider record
    Outsider::create([
        'name' => $request->name,
        'type' => $request->type,
        'vehicle_type' => $request->vehicle_type,
        'brand' => $request->brand,
        'color' => $request->color,
        'model' => $request->model,
        'plate_number' => $request->plate_number,
        'rfid' => $request->rfid,
        'type_id' => $request->type_id,
        'valid_id' => $validIdPath,
        'profile_img' => $profileImgPath,
        'in' => now(), // Automatically set the "in" field to the current datetime
    ]);

    // Redirect with a success message
    return redirect()->route('outsiders.index')->with('success', 'Service provider created successfully!');
}




    public function edit($id)
    {
        $outsider = Outsider::findOrFail($id);
        return response()->json($outsider);
    }

    public function update(Request $request, $id)
    {
        // Find the outsider by ID
        $outsider = Outsider::findOrFail($id);

        // Validate the incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            // Add other validation rules as needed
        ]);

        // Update the outsider with new data
        $outsider->update($request->all());

        // Redirect with success message
        return redirect()->route('outsiders.index')->with('success', 'Service provider updated successfully!');
    }

    public function destroy($id)
    {
        Outsider::destroy($id);
        return redirect()->route('outsiders.index');
    }

    public function updateOut($id)
{
    // Find the outsider by ID
    $outsider = Outsider::findOrFail($id);

    // Update the 'out' field with the current datetime
    $outsider->update(['out' => now()]);

    // Redirect back with a success message
    return redirect()->route('outsiders.index')->with('success', 'Out time updated successfully!');
}


public function generatePdf(Request $request)
{
    $query = Outsider::query();

    // Apply search and filters
    if ($request->has('search') && $request->search) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }
    if ($request->has('from_date') && $request->has('to_date')) {
        $query->whereBetween('in', [
            $request->from_date . ' 00:00:00',
            $request->to_date . ' 23:59:59'
        ]);
    }

    $outsiders = $query->get(); // Fetch all matching outsiders

    // Load the view for PDF
    $pdf = Pdf::loadView('guard.outsiders-pdf', compact('outsiders'));

    // Download or Stream the PDF
    return $pdf->stream('outsiders_list.pdf');
}
}
