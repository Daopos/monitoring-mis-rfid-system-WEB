<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

class TreasurerNotif extends Notification
{
    use Queueable;
    protected $data;

    /**
     * Create a new notification instance.
     */
    public function __construct($data)
    {
        //
        $this->data = $data; // Store the entire guard object

    }

    public function sendLoginNotification()
    {
        // Create and send a raw email with the guard's username and password
        Mail::raw(
            $this->buildMessage(),
            function ($message) {
                $message->to($this->data->email) // Send to the guard's email address
                    ->from('admin@agl-heights.online', 'AGL Heights') // Send from the admin email
                    ->subject('New Treasurer Account Created');
            }
        );
    }

    protected function buildMessage()
    {
        return "Hello " . $this->data->fname . ",\n\n" .
               "Your treasurer account has been successfully created by the admin.\n" .
               "Here are your login details:\n\n" .
               "Username: " . $this->data->username . "\n" .
               "Password: " . $this->data->password . "\n\n" .
               "You can now log in to your account and start your duties.\n\n" .
               "Thank you for being part of our team!";
    }
}