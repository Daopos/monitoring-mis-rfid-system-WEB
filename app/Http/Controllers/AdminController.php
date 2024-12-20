<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Eventdo;
use App\Models\HomeOwner;
use App\Models\Household;
use App\Models\Message;
use App\Models\Officer;
use App\Models\Pdf;
use App\Models\Visitor;
use App\Models\VisitorGateMonitor;
use App\Notifications\TestNotif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    // Find the admin user by username
    $admin = Admin::where('username', $credentials['username'])->first();

    // Check if the admin exists, password matches, type is 'guard', and is_archived is false
    if ($admin &&
        $admin->password == $credentials['password'] &&
        $admin->type == 'guard' &&
        !$admin->is_archived) {

        // Log out the current user (if any)
        Auth::guard('guard')->login($admin);

        // Redirect to the guard dashboard
        return redirect()->route('guard.dashboard');
    } else {
        // If login fails, redirect back with an error message
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
        $totalHomeowners = HomeOwner::where('status', 'confirmed')->count();
        // Get total number of events
        $totalEvents = Eventdo::count();

        // Get total number of homeowners with RFID
        $homeownersWithRFID = HomeOwner::whereNotNull('rfid')->count();

        // Get total number of homeowners without RFID
        $homeownersWithoutRFID = HomeOwner::where('status', 'confirmed')->whereNull('rfid')->count();

        $household = Household::count() + $totalHomeowners;

        $currentVisitors = VisitorGateMonitor::whereNotNull('in')
        ->whereNull('out')
        ->count();
        $pdfs = Pdf::all();
// Count unread messages for admin
$unreadMessages = Message::with('homeOwner') // Eager load the homeowner relationship
    ->where('recipient_role', 'admin')
    ->where('is_seen', false)
    ->orderBy('created_at', 'desc')
    ->take(5) // Fetch only the top 5 messages
    ->get();
// Pass data to the view
return view('admin.admindashboard', [
'totalHomeowners' => $totalHomeowners,
'totalEvents' => $totalEvents,
'homeownersWithRFID' => $homeownersWithRFID,
'homeownersWithoutRFID' => $homeownersWithoutRFID,
'household' => $household,
'visitor' => $currentVisitors,
'unreadMessages' => $unreadMessages, // Pass the unread message count
'pdfs' => $pdfs, // Pass the unread message count

]);
    }


    public function getguardDashboard() {

        return view('guard.dashboard');
    }

    public function getTreasurerDashboard() {
        // Fetch all distinct years where payments exist
        $years = DB::table('payment_reminders')
            ->selectRaw('YEAR(updated_at) as year')
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->pluck('year');

        // Fetch the current year data (or default to the latest year)
        $selectedYear = request('year', now()->year);

        $monthlyCollections = DB::table('payment_reminders')
            ->selectRaw('MONTH(updated_at) as month, SUM(amount) as total_collected')
            ->where('status', 'paid')
            ->whereYear('updated_at', $selectedYear)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        return view('treasurer.dashboard', [
            'monthlyCollections' => $monthlyCollections,
            'years' => $years,
            'selectedYear' => $selectedYear,
        ]);
    }



    public function getOfficerAPI()
    {
        // Get all guards that are not archived
        $guards = Admin::where('is_archived', 0)
            ->where('type', 'guard')
            ->get();

        // Define the sorted order of positions
        $positionOrder = [
            'President',
            'Vice President',
            'Secretary',
            'Asst. Secretary',
            'Treasurer',
            'Asst. Treasurer',
            'Auditors',
            'Sgt. at Arms',
            'P.R.O',
            'Business Managers',
        ];

        // Get all homeowners with their position from the officers table and sort based on the defined order
        $homeowners = Officer::with('homeowner')
            ->get()
            ->sortBy(function ($officer) use ($positionOrder) {
                // Ensure that positions not in the order are placed at the end
                return array_search($officer->position, $positionOrder) !== false ? array_search($officer->position, $positionOrder) : count($positionOrder);
            });

        // Transform guards and homeowners into a common structure
        $guardsArray = $guards->map(function ($guard) {
            return [
                'id' => $guard->id,
                'fname' => $guard->fname, // Adjust according to your Admin model's attributes
                'lname' => $guard->lname, // Assuming the relationship is defined
                'phone' => $guard->phone, // Assuming the relationship is defined
                'mname' => $guard->mname, // Assuming the relationship is defined
                'type' => 'guard',
                'active' => $guard->active, // Assuming the relationship is defined
                // Add other necessary fields
            ];
        });

        $homeownersArray = $homeowners->map(function ($officer) {
            return [
                'id' => $officer->id,
                'fname' => $officer->homeowner->fname, // Assuming the relationship is defined
                'lname' => $officer->homeowner->lname, // Assuming the relationship is defined
                'mname' => $officer->homeowner->mname, // Assuming the relationship is defined
                'phone' => $officer->homeowner->phone, // Assuming the relationship is defined
                'position' => $officer->position,
                // Add other necessary fields
            ];
        });

        // Merge guards and homeowners (guards should appear first)
        $officers = $guardsArray->merge($homeownersArray);

        // Return the combined result as a JSON response
        return response()->json($officers);
    }






}
