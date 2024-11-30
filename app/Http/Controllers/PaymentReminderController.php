<?php

namespace App\Http\Controllers;

use App\Models\HomeOwner;
use App\Models\PaymentReminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentReminderController extends Controller
{
 // Display a list of payment reminders
 public function index()
 {
     // Retrieve reminders with homeowner info where status is 'unpaid'
     $reminders = PaymentReminder::with('homeOwner')->where('status', 'unpaid')->get();

     // Only fetch confirmed homeowners
     $homeOwners = HomeOwner::where('status', 'confirmed')->get();

     // Get the current date
     $today = \Carbon\Carbon::today();

     // Count the number of homeowners with reminders due today
     $dueTodayCount = $reminders->filter(function ($reminder) use ($today) {
         return $reminder->due_date == $today->toDateString();
     })->count();

     // Count the number of homeowners with overdue reminders
     $overdueCount = $reminders->filter(function ($reminder) use ($today) {
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

     PaymentReminder::create([
         'home_owner_id' => $request->home_owner_id,
         'title' => $request->title,
         'amount' => $request->amount,
         'due_date' => $request->due_date,
         'status' => 'unpaid', // set default status to unpaid
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
 public function update(Request $request, PaymentReminder $paymentReminder)
 {
     $request->validate([
         'home_owner_id' => 'required|exists:home_owners,id',
         'title' => 'required|string|max:255',
         'amount' => 'required|numeric',
         'due_date' => 'required|date',
     ]);

     $paymentReminder->update($request->all());

     return redirect()->route('payment_reminders.index')->with('success', 'Payment reminder updated successfully!');
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
    $reminders = PaymentReminder::with('homeOwner')->where('status', 'paid')->get();

    // Only fetch confirmed homeowners
    $homeOwners = HomeOwner::where('status', 'confirmed')->get();

    return view('treasurer.paidlist', compact('reminders', 'homeOwners'));
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
