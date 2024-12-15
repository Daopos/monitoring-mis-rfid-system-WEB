<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

class GuardPasswordNotif extends Notification
{
    use Queueable;

    protected $guard;

    public function __construct($guard)
    {
        $this->guard = $guard; // Store the entire guard object
    }

    public function sendLoginNotification()
    {
        // Create and send a raw email with the guard's username and password
        Mail::raw(
            $this->buildMessage(),
            function ($message) {
                $message->to($this->guard->email) // Send to the guard's email address
                    ->from('admin@agl-heights.online', 'AGL Heights') // Send from the admin email
                    ->subject('New Guard Account Created');
            }
        );
    }

    protected function buildMessage()
    {
        return "Hello " . $this->guard->fname . ",\n\n" .
               "Your guard account has been successfully created by the admin.\n" .
               "Here are your login details:\n\n" .
               "Username: " . $this->guard->username . "\n" .
               "Password: " . $this->guard->password . "\n\n" .
               "You can now log in to your account and start your duties.\n\n" .
               "Thank you for being part of our team!";
    }
}