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
            'name' => 'required',
            'type' => 'required',
            'vehicle_type' => 'nullable',
            'brand' => 'nullable',
            'color' => 'nullable',
            'model' => 'nullable',
            'plate_number' => 'nullable',
            'rfid' => 'nullable',
        ]);

        Outsider::create(array_merge(
            $request->all(),
            ['in' => now()] // Automatically set the "in" field to the current datetime
        ));

        return redirect()->route('outsiders.index')->with('success', 'Outsider created successfully!');
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
        return redirect()->route('outsiders.index')->with('success', 'Outsider updated successfully!');
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
    return $pdf->download('outsiders_list.pdf');
}
}