<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Eventdo;
use App\Models\HomeOwner;
use App\Models\Household;
use App\Models\Visitor;
use App\Notifications\TestNotif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    //

    public function showLoginForm()
    {
        // Check if the user is already authenticated using Laravel's Auth
        if (Auth::check()) {
            // If authenticated, redirect to the admin dashboard
            return redirect()->route('admin.dashboard');
        }

        // If not authenticated, show the login form
        return view('admin.adminlogin');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        // Find the admin by username
        $admin = Admin::where('username', $credentials['username'])->first();

        // Check if admin exists, passwords match, and the type is 'admin'
        if ($admin && $admin->password === $credentials['password'] && $admin->type === 'admin') {
            // Log the admin in using Laravel's Auth::login() to manage the session
            Auth::guard('admin')->login($admin);

            // $email = 'proctantgaming@gmail.com';

            // $notification = new TestNotif(   $email);
            // $notification->sendLoginNotification();

            // Redirect to admin dashboard
            return redirect()->route('admin.dashboard');
        } else {
            // If authentication fails, redirect back with an error
            return redirect()->route('admin.login')->with('error', 'Login failed. Please check your credentials.');
        }
    }

    public function logout()
{
    // Log the admin out of the admin guard
    Auth::guard('admin')->logout();

    // Redirect to the admin login page with a success message
    return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
}


public function logoutGuard()
{
    // Log the admin out of the admin guard
    Auth::guard('guard')->logout();

    // Redirect to the admin login page with a success message
    return redirect()->route('guard.login')->with('success', 'Logged out successfully.');
}

public function logoutTreasurer()
{
    // Log the admin out of the admin guard
    Auth::guard('treasurer')->logout();

    // Redirect to the admin login page with a success message
    return redirect()->route('treasurer.login')->with('success', 'Logged out successfully.');
}
    public function showGuardLoginForm()
    {
        // Check if the guard user is already authenticated
        if (Auth::guard('guard')->check()) {
            // If authenticated, redirect to the guard dashboard
            return redirect()->route('guard.dashboard'); // Adjust this route to point to the guard dashboard
        }

        // If not authenticated, show the guard login form
        return view('guard.login'); // Ensure this view exists
    }


    public function guardlogin(Request $request) {
        $credentials = $request->only('username', 'password');

        $admin = Admin::where('username', $credentials['username'])->first();

        if ($admin && $admin->password == $credentials['password'] && $admin->type == 'guard') {
            Auth::guard('guard')->login($admin);


            return redirect()->route('guard.dashboard');
        } else {
            return redirect()->route('guard.login')->with('error', 'Login failed');
        }
    }

    public function showTreasurerLoginForm()
    {
        // Check if the guard user is already authenticated
        if (Auth::guard('treasurer')->check()) {
            // If authenticated, redirect to the guard dashboard
            return redirect()->route('treasurer.dashboard'); // Adjust this route to point to the guard dashboard
        }

        // If not authenticated, show the guard login form
        return view('treasurer.login'); // Ensure this view exists
    }

    public function treasurerlogin(Request $request) {
        $credentials = $request->only('username', 'password');

        $admin = Admin::where('username', $credentials['username'])->first();

        if ($admin && $admin->password == $credentials['password'] && $admin->type == 'treasurer') {
            Auth::guard('treasurer')->login($admin);


            return redirect()->route('treasurer.dashboard');
        } else {
            return redirect()->route('treasurer.login')->with('error', 'Login failed');
        }
    }


    public function getDashboard() {
        // Get total number of homeowners
        $totalHomeowners = HomeOwner::count();

        // Get total number of events
        $totalEvents = Eventdo::count();

        // Get total number of homeowners with RFID
        $homeownersWithRFID = HomeOwner::whereNotNull('rfid')->count();

        // Get total number of homeowners without RFID
        $homeownersWithoutRFID = HomeOwner::whereNull('rfid')->count();

        $household = Household::count();


        $visitor = Visitor::count();


        // Return the data to the view
        return view('admin.admindashboard', [
            'totalHomeowners' => $totalHomeowners,
            'totalEvents' => $totalEvents,
            'homeownersWithRFID' => $homeownersWithRFID,
            'homeownersWithoutRFID' => $homeownersWithoutRFID,
            'household' => $household,
            'visitor' => $visitor

        ]);
    }


    public function getguardDashboard() {

        return view('guard.dashboard');
    }

    public function getTreasurerDashboard() {
        return view('treasurer.dashboard');

    }
}