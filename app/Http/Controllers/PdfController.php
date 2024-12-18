<?php

namespace App\Http\Controllers;

use App\Models\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
     // Display a list of PDFs
     public function index()
     {
         $pdfs = Pdf::all();
         return view('pdfs.index', compact('pdfs'));
     }

     // Show the form for creating a new PDF
     public function create()
     {
         return view('pdfs.create');
     }

     // Store a new PDF
     public function store(Request $request)
     {
         // Validate the request data
         $request->validate([
             'name' => 'required|string|max:255',
             'file' => 'required|mimes:pdf|max:2048',
         ]);

         // Check if a PDF already exists in the database
         if (Pdf::count() > 0) {
             return redirect()->route('admin.dashboard')->with('error', 'Only one PDF is allowed.');
         }

         // Store the PDF file
         $filePath = $request->file('file')->store('public/pdfs');
         $fileName = $request->file('file')->getClientOriginalName();

         // Save to DB
         Pdf::create([
             'name' => $request->name,
             'file_path' => $filePath,
         ]);

         return redirect()->route('admin.dashboard')->with('success', 'PDF uploaded successfully');
     }


     // Show a specific PDF
     public function show(Pdf $pdf)
     {
         return response()->file(storage_path('app/' . $pdf->file_path));
     }

     // Show the form for editing a PDF
     public function edit(Pdf $pdf)
     {
         return view('pdfs.edit', compact('pdf'));
     }

     // Update the PDF name
     public function update(Request $request, Pdf $pdf)
     {
         $request->validate([
             'name' => 'required|string|max:255',
         ]);

         $pdf->update(['name' => $request->name]);

         return redirect()->route('admin.dashboard')->with('success', 'PDF updated successfully');
     }

     // Delete a PDF
     public function destroy(Pdf $pdf)
     {
         // Delete file from storage
         \Storage::delete($pdf->file_path);

         // Delete record from DB
         $pdf->delete();

         return redirect()->route('admin.dashboard')->with('success', 'PDF deleted successfully');
     }


     public function download()
{
    // Get the first PDF (only one exists)
    $pdf = Pdf::first();

    // Check if a PDF exists
    if (!$pdf) {
        return response()->json(['error' => 'PDF not found'], 404);
    }

    // Get the file path
    $filePath = storage_path('app/' . $pdf->file_path);

    // Check if file exists
    if (!file_exists($filePath)) {
        return response()->json(['error' => 'File not found'], 404);
    }

    // Return the file for download
    return response()->download($filePath, $pdf->name);
}

}