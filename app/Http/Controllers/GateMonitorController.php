<?php

namespace App\Http\Controllers;

use App\Models\GateMonitor;
use App\Models\HomeOwner;
use App\Models\Household;
use App\Models\HouseholdGateMonitor;
use App\Models\Visitor;
use App\Models\VisitorGateMonitor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GateMonitorController extends Controller
{
    //
    public function index()
    {
        // Retrieve GateMonitor entries for homeowners
        $gateMonitors = GateMonitor::with('owner')->get();

        // Retrieve VisitorGateMonitor entries for visitors
        $visitorGateMonitors = VisitorGateMonitor::with(['visitor.homeowner'])->get();

        return view('guard.dashboard', compact('gateMonitors', 'visitorGateMonitors'));
    }



    public function store(Request $request)
{
    $rfid = $request->input('owner_id');

    \Log::info('RFID entry received:', ['rfid' => $rfid]);

    try {
        // Try to find the home owner by RFID
        $homeOwner = HomeOwner::where('rfid', $rfid)->first();

        if ($homeOwner) {
            // Handle homeowner logic
            return $this->handleHomeOwnerEntry($homeOwner);
        }

        // If not found, try to find the visitor by RFID
        $visitor = Visitor::where('rfid', $rfid)->first();

        if ($visitor) {
            // Handle visitor logic
            return $this->handleVisitorEntry($visitor);
        }

        // If not found, try to find the household by RFID
        $household = Household::where('rfid', $rfid)->first();

        if ($household) {
            // Handle household logic
            return $this->handleHouseholdEntry($household);
        }

        // Log error if neither is found
        \Log::error('Home owner, visitor, and household not found for RFID:', ['rfid' => $rfid]);
        return redirect()->route('gate-monitors.index')->with('error', 'RFID not found for homeowner, visitor, or household.');
    } catch (\Exception $e) {
        \Log::error('Error processing GateMonitor:', ['error' => $e->getMessage()]);
        return redirect()->route('gate-monitors.index')->with('error', 'Failed to record RFID entry.');
    }
}

private function handleHouseholdEntry($household)
{
    // Get the existing HouseholdGateMonitor entry for the household
    $householdGateMonitor = HouseholdGateMonitor::where('household_id', $household->id)->latest()->first();

    if (!$householdGateMonitor) {
        // No HouseholdGateMonitor entry exists; create a new entry
        $householdGateMonitor = HouseholdGateMonitor::create([
            'household_id' => $household->id,
            'in' => now(),
        ]);

        \Log::info('New HouseholdGateMonitor created for household:', ['id' => $householdGateMonitor->id]);
        return redirect()->route('gate-monitors.index')
            ->with('success', 'Household RFID entry recorded successfully (entry time).')
            ->with('household', $household); // Pass household details to session
    } elseif ($householdGateMonitor->in && !$householdGateMonitor->out) {
        // Update the existing entry with exit time
        $householdGateMonitor->update(['out' => now()]);

        \Log::info('HouseholdGateMonitor updated for household with exit time:', ['id' => $householdGateMonitor->id]);
        return redirect()->route('gate-monitors.index')
            ->with('success', 'Household RFID entry recorded successfully (exit time).')
            ->with('household', $household); // Pass household details to session
    } else {
        // Create a new entry if both in and out exist
        $newHouseholdGateMonitor = HouseholdGateMonitor::create([
            'household_id' => $household->id,
            'in' => now(),
        ]);

        \Log::info('New HouseholdGateMonitor created for existing household entry:', ['id' => $newHouseholdGateMonitor->id]);
        return redirect()->route('gate-monitors.index')
            ->with('success', 'New household RFID entry recorded successfully.')
            ->with('household', $household); // Pass household details to session
    }
}

    private function handleHomeOwnerEntry($homeOwner)
    {
        // Get the existing GateMonitor entry for the home owner
        $gateMonitor = GateMonitor::where('owner_id', $homeOwner->id)->latest()->first();

        if (!$gateMonitor) {
            // No GateMonitor entry exists; create a new entry
            $gateMonitor = GateMonitor::create([
                'owner_id' => $homeOwner->id,
                'in' => now(),
            ]);

            \Log::info('New GateMonitor created for homeowner:', ['id' => $gateMonitor->id]);
            return redirect()->route('gate-monitors.index')
                ->with('success', 'Homeowner RFID entry recorded successfully (entry time).')
                ->with('homeOwner', $homeOwner);
        } elseif ($gateMonitor->in && !$gateMonitor->out) {
            // Update the existing entry with exit time
            $gateMonitor->update(['out' => now()]);

            \Log::info('GateMonitor updated for homeowner with exit time:', ['id' => $gateMonitor->id]);
            return redirect()->route('gate-monitors.index')
                ->with('success', 'Homeowner RFID entry recorded successfully (exit time).')
                ->with('homeOwner', $homeOwner);
        } else {
            // Create a new entry if both in and out exist
            $newGateMonitor = GateMonitor::create([
                'owner_id' => $homeOwner->id,
                'in' => now(),
            ]);

            \Log::info('New GateMonitor created for existing homeowner entry:', ['id' => $newGateMonitor->id]);
            return redirect()->route('gate-monitors.index')
                ->with('success', 'New homeowner RFID entry recorded successfully.')
                ->with('homeOwner', $homeOwner);
        }
    }

    private function handleVisitorEntry($visitor)
    {
        // Get the existing VisitorGateMonitor entry for the visitor
        $visitorGateMonitor = VisitorGateMonitor::where('visitor_id', $visitor->id)->latest()->first();

        if (!$visitorGateMonitor) {
            // No VisitorGateMonitor entry exists; create a new entry
            $visitorGateMonitor = VisitorGateMonitor::create([
                'visitor_id' => $visitor->id,
                'in' => now(),
            ]);

            $homeowner = $visitor->homeowner; // Fetch the associated homeowner

            \Log::info('New VisitorGateMonitor created for visitor:', ['id' => $visitorGateMonitor->id]);
            return redirect()->route('gate-monitors.index')
                ->with('success', 'Visitor RFID entry recorded successfully (entry time).')
                ->with('visitor', $visitor)
                ->with('homeowner', $homeowner); // Pass homeowner details to the session
        } elseif ($visitorGateMonitor->in && !$visitorGateMonitor->out) {
            // Update the existing entry with exit time
            $visitorGateMonitor->update(['out' => now()]);

            $homeowner = $visitor->homeowner; // Fetch the associated homeowner
            \Log::info('Visitor found:', ['visitor' => $visitor]);
            \Log::info('VisitorGateMonitor updated for visitor with exit time:', ['id' => $visitorGateMonitor->id]);
            return redirect()->route('gate-monitors.index')
    ->with('success', 'Visitor RFID entry recorded successfully (exit time).')
    ->with('visitor', $visitor)
    ->with('homeowner', $homeowner);
if ($visitor) {
    \Log::info('Visitor found:', ['visitor' => $visitor]);
    return $this->handleVisitorEntry($visitor);
}
        } else {
            // Create a new entry if both in and out exist
            $newVisitorGateMonitor = VisitorGateMonitor::create([
                'visitor_id' => $visitor->id,
                'in' => now(),
            ]);

            $homeowner = $visitor->homeowner; // Fetch the associated homeowner

            \Log::info('New VisitorGateMonitor created for existing visitor entry:', ['id' => $newVisitorGateMonitor->id]);
            return redirect()->route('gate-monitors.index')
                ->with('success', 'New visitor RFID entry recorded successfully.')
                ->with('visitor', $visitor)
                ->with('homeowner', $homeowner); // Pass homeowner details to the session
        }

        if ($visitor) {
            \Log::info('Visitor found:', ['visitor' => $visitor]);
            return $this->handleVisitorEntry($visitor);
        }
    }


public function getAllEntry(Request $request)
{
    // Initialize the query
    $query = GateMonitor::with('owner');

    // Filter by those who haven't exited yet
    if ($request->has('status') && $request->input('status') == 'in') {
        $query->whereNull('out');
    }

    // Search by owner's name
    if ($request->has('search') && $request->input('search') != '') {
        $searchTerm = $request->input('search');
        $terms = explode(' ', $searchTerm);

        $query->whereHas('owner', function ($q) use ($terms) {
            foreach ($terms as $term) {
                $q->where(function ($query) use ($term) {
                    $query->where('fname', 'like', '%' . $term . '%')
                          ->orWhere('lname', 'like', '%' . $term . '%');
                });
            }
        });
    }

    // Filter by date range
    if ($request->has('from_date') && $request->has('to_date')) {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $query->whereBetween('in', [
            \Carbon\Carbon::parse($fromDate)->startOfDay(),
            \Carbon\Carbon::parse($toDate)->endOfDay()
        ]);
    }

    // Get the total count of entries for the current query
    $totalEntries = $query->count();

    // Order by 'in' column in descending order and paginate results
    $gateMonitors = $query->orderBy('in', 'desc')->paginate(10);

    return view('admin.adminmonitor', compact('gateMonitors', 'totalEntries'));
}


public function getAllEntryGuard(Request $request)
{
    // Initialize the query
    $query = GateMonitor::with('owner');

    // Filter by those who haven't exited yet
    if ($request->has('status') && $request->input('status') == 'in') {
        $query->whereNull('out');
    }

    // Search by owner's name
    if ($request->has('search') && $request->input('search') != '') {
        $searchTerm = $request->input('search');
        $terms = explode(' ', $searchTerm);

        $query->whereHas('owner', function ($q) use ($terms) {
            foreach ($terms as $term) {
                $q->where(function ($query) use ($term) {
                    $query->where('fname', 'like', '%' . $term . '%')
                          ->orWhere('lname', 'like', '%' . $term . '%');
                });
            }
        });
    }

    // Filter by date range
    if ($request->has('from_date') && $request->has('to_date')) {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $query->whereBetween('in', [
            \Carbon\Carbon::parse($fromDate)->startOfDay(),
            \Carbon\Carbon::parse($toDate)->endOfDay()
        ]);
    }

    // Get the total count of entries for the current query
    $totalEntries = $query->count();

    // Order by 'in' column in descending order and paginate results
    $gateMonitors = $query->orderBy('in', 'desc')->paginate(10);

    return view('guard.entry', compact('gateMonitors', 'totalEntries'));
}

public function getAllEntryAPI()
{
    try {
        // Retrieve the authenticated home_owner ID
        $homeOwnerId = Auth::user()->id;

        // Retrieve the gate monitors for the authenticated home_owner
        $gateMonitors = GateMonitor::where('owner_id', $homeOwnerId) // Assuming `home_owner_id` is the foreign key in the GateMonitor model
            ->get();

        // Check if data exists
        if ($gateMonitors->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No gate monitor entries found for the authenticated home owner.'
            ], 404);
        }

        // Return the data in a standardized response format
        return response()->json( $gateMonitors
        , 200);

    } catch (\Exception $e) {
        // Handle any errors or exceptions that occur during the query
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while fetching data.',
            'error' => $e->getMessage()
        ], 500);
    }
}


public function generatePDF(Request $request)
    {
        // Fetch data based on filters
        $query = GateMonitor::with('owner');

        // Apply filters
        if ($request->has('status') && $request->input('status') == 'in') {
            $query->whereNull('out');
        }

        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('in', [
                \Carbon\Carbon::parse($request->input('from_date'))->startOfDay(),
                \Carbon\Carbon::parse($request->input('to_date'))->endOfDay()
            ]);
        }

        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            $query->whereHas('owner', function ($q) use ($searchTerm) {
                $q->where('fname', 'like', '%' . $searchTerm . '%')
                  ->orWhere('lname', 'like', '%' . $searchTerm . '%');
            });
        }

        $gateMonitors = $query->orderBy('in', 'desc')->get();

        // Generate PDF
        $pdf = Pdf::loadView('guard.pdf_report', compact('gateMonitors'));

        // Return the PDF for download
        return $pdf->download('gate_entry_report.pdf');
    }

}