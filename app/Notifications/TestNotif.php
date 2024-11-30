<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

class TestNotif extends Notification
{
    use Queueable;

    protected $email;
    protected $loginDate;
    protected $device;

    public function __construct($email)
    {
        $this->email = $email;
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
                $message->to($this->email) // Use the student's email address
                    ->from('admin@agl-heights.online', 'Qma Email Security') // Send from the noreply address
                    ->subject('Login Notification');
            }
        );
    }

    protected function buildMessage()
    {
        return "Hello ,\n\n" .
               "You have successfully logged into your account on .\n" .
               "Device: \n\n" .
               "If this was not you, please report it to the registrar's office and take the necessary actions.\n\n" .
               "Thank you for using our application!";
    }
}
