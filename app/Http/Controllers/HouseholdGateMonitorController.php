<?php

namespace App\Http\Controllers;

use App\Models\HouseholdGateMonitor;
use Illuminate\Http\Request;

class HouseholdGateMonitorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = HouseholdGateMonitor::with('household.homeOwner') // Eager load 'homeOwner' of 'household'
            ->join('households', 'household_gate_monitors.household_id', '=', 'households.id')
            ->select('household_gate_monitors.*');

        // Search by homeowner name (first name or last name)
        if ($request->has('search') && $request->search) {
            $query->whereHas('household', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status (e.g., active or inactive)
        if ($request->has('status') && $request->status) {
            $query->where('households.status', $request->status);
        }

        // Filter by date range for entry
        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('household_gate_monitors.in', [
                $request->from_date . ' 00:00:00',
                $request->to_date . ' 23:59:59'
            ]);
        }

        $gateMonitors = $query->paginate(10);
        $totalEntries = $query->count();

        return view('guard.householdentry', compact('gateMonitors', 'totalEntries'));  // Note: 'gateMonitors' here
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
    public function show(HouseholdGateMonitor $householdGateMonitor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HouseholdGateMonitor $householdGateMonitor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HouseholdGateMonitor $householdGateMonitor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HouseholdGateMonitor $householdGateMonitor)
    {
        //
    }
}
