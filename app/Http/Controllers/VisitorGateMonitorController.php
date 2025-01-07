<?php

namespace App\Http\Controllers;

use App\Models\VisitorGateMonitor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class VisitorGateMonitorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = VisitorGateMonitor::with('visitor') // Eager load the related visitor
            ->join('visitors', 'visitor_gate_monitors.visitor_id', '=', 'visitors.id')
            ->select('visitor_gate_monitors.*');

        // Search by visitor name (first name or last name) or plate number
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('visitors.name', 'like', '%' . $request->search . '%')
                  ->orWhere('visitors.plate_number', 'like', '%' . $request->search . '%'); // Add search condition for plate number
            });
        }

        // Filter by visitor status (approved, pending, denied)
        if ($request->has('status')) {
            $status = $request->input('status');
            if ($status == 'in') {
                // Homeowners who are inside
                $query->whereNull('out');
            } else if ($status == 'out') {
                // Homeowners who are outside
                $query->whereNotNull('out');
            }
        }

        // Filter by vehicle status (with or without a plate number)
        if ($request->has('vehicle_status')) {
            $vehicleStatus = $request->input('vehicle_status');
            if ($vehicleStatus == 'with_vehicle') {
                // Visitors with a plate number (i.e., with a vehicle)
                $query->whereNotNull('visitors.plate_number');
            } else if ($vehicleStatus == 'without_vehicle') {
                // Visitors without a plate number (i.e., without a vehicle)
                $query->whereNull('visitors.plate_number');
            }
        }

        // Filter by date range for the visit date
        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('visitor_gate_monitors.in', [
                $request->from_date . ' 00:00:00',
                $request->to_date . ' 23:59:59'
            ]);
        }

        $query->orderBy('visitor_gate_monitors.in', 'DESC');

        $gateMonitors = $query->paginate(10);
        $totalEntries = $query->count();

        return view('guard.visitorentry', compact('gateMonitors', 'totalEntries'));
    }





public function indexAdmin(Request $request)
{
    $query = VisitorGateMonitor::with('visitor') // Eager load the related visitor
        ->join('visitors', 'visitor_gate_monitors.visitor_id', '=', 'visitors.id')
        ->select('visitor_gate_monitors.*');

    // Search by visitor name (first name or last name)
    if ($request->has('search') && $request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('visitors.name', 'like', '%' . $request->search . '%');
        });
    }

    // Filter by visitor status (approved, pending, denied)
    if ($request->has('status')) {
        $status = $request->input('status');

        if ($status == 'in') {
            // Homeowners who are inside
            $query->whereNull('out');
        } else if ($status == 'out') {
            // Homeowners who are outside
            $query->whereNotNull('out');
        }
    }
    // Filter by date range for the visit date
    if ($request->has('from_date') && $request->has('to_date')) {
        $query->whereBetween('visitor_gate_monitors.in', [
            $request->from_date . ' 00:00:00',
            $request->to_date . ' 23:59:59'
        ]);
    }

     // Order by the latest RFID tap (descending order)
     $query->orderBy('visitor_gate_monitors.in', 'DESC');

    $gateMonitors = $query->paginate(10);
    $totalEntries = $query->count();

    return view('admin.visitorentry', compact('gateMonitors', 'totalEntries'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(VisitorGateMonitor $visitorGateMonitor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VisitorGateMonitor $visitorGateMonitor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VisitorGateMonitor $visitorGateMonitor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VisitorGateMonitor $visitorGateMonitor)
    {
        //
    }
    public function generatePdf(Request $request)
{
    // Fetch the filtered data (reusing your index logic)
    $query = VisitorGateMonitor::with('visitor')
        ->join('visitors', 'visitor_gate_monitors.visitor_id', '=', 'visitors.id')
        ->select('visitor_gate_monitors.*');

    if ($request->has('search') && $request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('visitors.name', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->has('status') && $request->status) {
        $query->where('visitors.status', $request->status);
    }

    if ($request->has('from_date') && $request->has('to_date')) {
        $query->whereBetween('visitor_gate_monitors.in', [
            $request->from_date . ' 00:00:00',
            $request->to_date . ' 23:59:59'
        ]);
    }

    $gateMonitors = $query->get();

    // Pass data to the PDF view
    $pdf = Pdf::loadView('guard.pdf_visitor', compact('gateMonitors'));

    // Download the PDF file
    return $pdf->stream('visitor_gate_entry_list.pdf');
}
}