<?php

namespace App\Http\Controllers;

use App\Models\HomeOwner;
use App\Models\HomeownerNotification;
use App\Models\PaymentReminder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
             $query->whereDate('due_date', $today);
         } elseif ($request->filter == 'overdue') {
             $query->whereDate('due_date', '<', $today);
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
         return $reminder->due_date == $today->toDateString();
     })->count();

     $overdueCount = $allReminders->filter(function ($reminder) use ($today) {
         return $reminder->due_date < $today->toDateString();
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
     $request->validate([
         'home_owner_id' => 'required|exists:home_owners,id',
         'title' => 'required|string|max:255',
         'amount' => 'required|numeric',
         'due_date' => 'required|date',
     ]);

     $paymentReminder = PaymentReminder::create([
        'home_owner_id' => $request->home_owner_id,
        'title' => $request->title,
        'amount' => $request->amount,
        'due_date' => $request->due_date,
        'status' => 'unpaid', // Default status
    ]);

      // Add a notification for the homeowner
    HomeownerNotification::create([
        'home_owner_id' => $request->home_owner_id, // Use the homeowner ID from the request
        'title' => 'New Payment Reminder',
        'message' => "A new payment reminder has been created: {$paymentReminder->title}. Due date: {$paymentReminder->due_date}.",
        'is_read' => false,
    ]);

     return redirect()->route('payment_reminders.index')->with('success', 'Payment reminder created successfully!');
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


public function indexPaid()
{
    // Retrieve reminders with homeowner info where status is 'paid'
    $reminders = PaymentReminder::with('homeOwner')->where('status', 'paid')->paginate(10);

    // Calculate the total amount of all reminders with 'paid' status
    $totalAmount = PaymentReminder::where('status', 'paid')->sum('amount');

    // Only fetch confirmed homeowners
    $homeOwners = HomeOwner::where('status', 'confirmed')->get();

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
}
