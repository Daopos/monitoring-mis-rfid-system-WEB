<?php

namespace App\Models;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
class HomeOwner extends Model implements CanResetPassword
{
    use HasFactory, Notifiable, HasApiTokens, CanResetPasswordTrait;

    protected $fillable = [
        'fname',
        'lname',
        'phone',
        'email',
        'birthdate',  // Updated to match the request validation
        'status',
        'phase',
        'gender',
        'plate',
        'extension',
        'mname',
        'block',
        'lot',
        'number',
        'image',        // Image field for the homeowner's profile
        'position',     // Default value 'Resident'
        'rfid',         // If applicable, depending on your application
        'password',     // For storing the hashed password
        'document_image',     // Add the document field if storing files (if relevant)
    ];
      /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // public function gateMonitors()
    // {
    //     return $this->hasMany(GateMonitor::class);
    // }

    public function gateMonitors()
{
    return $this->hasMany(GateMonitor::class, 'owner_id');
}

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'home_owner_id')
                    ->where('sender_role', 'home_owner');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'home_owner_id')
                    ->where('recipient_role', 'home_owner');
    }

    public function messages() {
        return $this->hasMany(Message::class);
    }

    public function visitors()
    {
        return $this->hasMany(Visitor::class, 'home_owner_id');
    }


    public function vehicles()
{
    return $this->hasMany(Vehicle::class);
}

public function households()
{
    return $this->hasMany(Household::class, 'home_owner_id');
}

public function hasUnreadMessages($recipientRole)
{
    return $this->messages()
        ->where('recipient_role', $recipientRole)
        ->where('is_seen', false)
        ->exists();
}

}