<?php

namespace App\Http\Controllers;

use App\Models\HomeOwner;
use App\Models\HomeownerNotification;
use App\Models\PaymentReminder;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class PaymentReminderController extends Controller
{
 // Display a list of payment reminders
 public function index(Request $request)
 {
     // Get the current date
     $today = Carbon::today();

     // Start query to get reminders
     $query = PaymentReminder::with('homeOwner')
         ->where('status', 'unpaid');

     // Handle search for homeowner's name
     if ($request->has('search') && $request->search != '') {
         $query->whereHas('homeOwner', function($q) use ($request) {
             $q->where('fname', 'like', '%' . $request->search . '%')
               ->orWhere('lname', 'like', '%' . $request->search . '%');
         });
     }

     // Filter by due date
     if ($request->has('filter') && in_array($request->filter, ['due_today', 'overdue'])) {
         if ($request->filter == 'due_today') {
             $query->whereDate('due_date', $today->toDateString()); // Ensure date comparison
         } elseif ($request->filter == 'overdue') {
             $query->whereDate('due_date', '<', $today->toDateString());
         }
     }

     $query->orderBy('created_at', 'desc'); // Change 'due_date' to 'created_at' if needed

     // Paginate the results
     $reminders = $query->paginate(10);

     // Fetch confirmed homeowners
     $homeOwners = HomeOwner::where('status', 'confirmed')->get();

     // Count the number of homeowners with reminders due today and overdue
     $allReminders = PaymentReminder::with('homeOwner')->where('status', 'unpaid')->get();
     $dueTodayCount = $allReminders->filter(function ($reminder) use ($today) {
         return Carbon::parse($reminder->due_date)->isToday(); // Parse the due_date as Carbon
     })->count();

     $overdueCount = $allReminders->filter(function ($reminder) use ($today) {
         return Carbon::parse($reminder->due_date)->isBefore($today); // Parse the due_date as Carbon
     })->count();

     // Pass data to the view
     return view('treasurer.payment', compact('reminders', 'homeOwners', 'dueTodayCount', 'overdueCount'));
 }



 // Show the form to create a new payment reminder
 public function create()
 {
     $homeOwners = HomeOwner::all(); // Get list of homeowners to assign to reminders
     return view('payment_reminders.create', compact('homeOwners'));
 }

 // Store a new payment reminder
 public function store(Request $request)
 {
     // Fetch all homeowners
     $homeOwners = HomeOwner::all();
     $fixedTitle = 'Association Fee';
     $fixedAmount = 300;
     $dueDate = now()->startOfMonth()->addDays(14); // Fixed to the 15th of the current month

     foreach ($homeOwners as $homeOwner) {
         // Check if the homeowner already has an unpaid reminder
         $existingReminder = PaymentReminder::where('home_owner_id', $homeOwner->id)
             ->where('status', 'unpaid')
             ->first();

         if ($existingReminder) {
             // Add the fixed amount to the existing reminder
             $existingReminder->increment('amount', $fixedAmount);
         } else {
             // Create a new reminder
             PaymentReminder::create([
                 'home_owner_id' => $homeOwner->id,
                 'title' => $fixedTitle,
                 'amount' => $fixedAmount,
                 'due_date' => $dueDate,
                 'status' => 'unpaid', // Default status
             ]);
         }

         // Add a notification for the homeowner
         HomeownerNotification::create([
             'home_owner_id' => $homeOwner->id,
             'title' => 'New Payment Reminder',
             'message' => "A new payment reminder has been created: {$fixedTitle}. Due date: {$dueDate->format('Y-m-d')}.",
             'is_read' => false,
         ]);
     }

     return redirect()->route('payment_reminders.index')
         ->with('success', 'Payment reminders for "Association Fee" created for all homeowners successfully!');
 }

 // Show the form to edit a payment reminder
 public function edit(PaymentReminder $paymentReminder)
 {
     $homeOwners = HomeOwner::all();
     return view('payment_reminders.edit', compact('paymentReminder', 'homeOwners'));
 }

 // Update a payment reminder
 public function update(Request $request, $id)
 {
     $request->validate([
         'title' => 'required|string|max:255',
         'amount' => 'required|numeric',
         'due_date' => 'required|date',
     ]);

     $reminder = PaymentReminder::findOrFail($id);
     $reminder->title = $request->title;
     $reminder->amount = $request->amount;
     $reminder->due_date = $request->due_date;
     $reminder->save();

     return redirect()->back()->with('success', 'Payment reminder updated successfully!');
 }


 // Delete a payment reminder
 public function destroy(PaymentReminder $paymentReminder)
 {
     $paymentReminder->delete();
     return redirect()->route('payment_reminders.index')->with('success', 'Payment reminder deleted successfully!');
 }

 public function markAsPaid(PaymentReminder $paymentReminder)
{
    $paymentReminder->update(['status' => 'paid']);

    return redirect()->route('payment_reminders.index')->with('success', 'Payment reminder marked as paid.');
}


public function indexPaid(Request $request)
{
    // Get the selected month if it's provided in the request
    $monthFilter = $request->input('month_filter');

    // Initialize the query for reminders
    $query = PaymentReminder::with('homeOwner')->where('status', 'paid');

    // If a month filter is provided, filter by the updated_at (payment date)
    if ($monthFilter) {
        // Filter reminders for the selected month
        $query->whereMonth('updated_at', $monthFilter);
    }

    // Paginate the results, 10 per page
    $reminders = $query->paginate(10);

    // Calculate the total amount of all reminders with 'paid' status for the selected month (if applicable)
    $totalAmount = $query->sum('amount');

    // Fetch only confirmed homeowners
    $homeOwners = HomeOwner::where('status', 'confirmed')->get();

    // Return the view with the reminders, homeowners, and total amount
    return view('treasurer.paidlist', compact('reminders', 'homeOwners', 'totalAmount'));
}


// API

public function getHomeownerReminders()
    {
        $id = Auth::user()->id; // Get the authenticated homeowner

        // Fetch payment reminders where `home_owner_id` matches the authenticated homeowner's ID
        $reminders = PaymentReminder::where('home_owner_id', $id)->get();

        return response()->json($reminders);
    }



    public function generateReport(Request $request)
    {
        // Fetch data based on filters
        $query = PaymentReminder::where('status', 'Paid');

        // Apply month filter if provided
        if ($request->has('month_filter') && $request->input('month_filter') != '') {
            $query->whereMonth('updated_at', $request->input('month_filter'));
        }

        // Retrieve filtered data
        $reminders = $query->get();

        // Generate PDF using the view
        $pdf = Pdf::loadView('treasurer.paidlist_report', compact('reminders'));

        // Return the PDF for streaming
        return $pdf->stream('paid_list_report.pdf');
    }

}