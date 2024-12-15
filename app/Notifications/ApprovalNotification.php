<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

class ApprovalNotification extends Notification
{
    use Queueable;

    protected $homeowner;
    protected $loginDate;
    protected $device;

    public function __construct($homeowner)
    {
        $this->homeowner = $homeowner;
    }

    public function via($notifiable)
    {
        // You can return an empty array since we're sending a raw email
        return [];
    }

    public function toMail($notifiable)
    {
        // This method will not be called anymore, so you can omit it.
    }

    public function sendLoginNotification()
    {
        // Create a plain text email
        Mail::raw(
            $this->buildMessage(),
            function ($message) {
                $message->to($this->homeowner->email) // Use the student's email address
                    ->from('admin@agl-heights.online', 'Agl Heights') // Send from the noreply address
                    ->subject('Account Approval Confirmation');
            }
        );
    }

    protected function buildMessage()
    {
        return "Greetings!\n\n" .
           "We are pleased to inform you that your account has been successfully approved.\n" .
           "You can now enjoy full access to all the services and featured offers.\n\n" .
           "Thank you for being a valued member of AGL Heights Subdivision.";
    }
}