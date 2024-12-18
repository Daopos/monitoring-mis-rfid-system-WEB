<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HomeOwner;
use App\Models\PaymentReminder;
use App\Models\HomeownerNotification;
use Carbon\Carbon;

class GeneratePaymentReminders extends Command
{
    protected $signature = 'generate:payment-reminders';
    protected $description = 'Generate payment reminders for all homeowners';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $homeOwners = HomeOwner::all();
        $fixedTitle = 'Association Fee';
        $fixedAmount = 300;
        $dueDate = Carbon::now()->startOfMonth()->addDays(14); // Fixed to the 15th of the current month

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

        $this->info('Payment reminders generated successfully for all homeowners!');
    }
}