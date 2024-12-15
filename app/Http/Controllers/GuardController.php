<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Notifications\GuardPasswordNotif;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GuardController extends Controller
{
    //
        public function index()
        {
            $guards = Admin::where('type', 'guard')->get();
            return view('admin.guard', compact('guards'));
        }

        public function create()
        {
            return view('guards.create');
        }


        public function store(Request $request)
        {
            $request->validate([
                'username' => 'required|unique:admins',
                'email' => 'nullable|email',
                'phone' => 'nullable',
                'fname' => 'nullable',
                'mname' => 'nullable',
                'lname' => 'nullable',
            ]);

            // Generate a random password (e.g., 8 characters)
            $randomPassword = Str::random(8);

            $guard = Admin::create([
                'username' => $request->username,
                'password' => $randomPassword, // Make sure to hash the password
                'type' => 'guard',
                'email' => $request->email,
                'phone' => $request->phone,
                'fname' => $request->fname,
                'mname' => $request->mname,
                'lname' => $request->lname,
                'hired' => $request->hired,
            ]);

            // Create the notification and pass the whole $guard object to the constructor
            $notification = new GuardPasswordNotif($guard);
            $notification->sendLoginNotification();


            // You can return the random password to the user or handle it as needed
            return redirect()->route('admin.guard.index')->with('success', 'Guard created successfully.');
        }

        public function edit($id)
        {
            $guard = Admin::findOrFail($id);
            return view('guards.edit', compact('guard'));
        }

        public function update(Request $request, $id)
        {
            $guard = Admin::findOrFail($id);

            $request->validate([
                'username' => 'required|unique:admins,username,' . $guard->id,
                'email' => 'nullable|email',
                'phone' => 'nullable',
            ]);

            $guard->update($request->all());

            return redirect()->route('admin.guard.index')->with('success', 'Guard updated successfully.');
        }

        public function archive($id)
        {
            $guard = Admin::findOrFail($id);
            $guard->update(['is_archived' => true]);

            return redirect()->route('admin.guard.index')->with('success', 'Guard archived successfully.');
        }

        public function restore($id)
        {
            $guard = Admin::findOrFail($id);
            $guard->update(['is_archived' => false]);

            return redirect()->route('admin.guard.index')->with('success', 'Guard restored successfully.');
        }

        public function destroy($id)
        {
            $guard = Admin::findOrFail($id);
            $guard->delete();

            return redirect()->route('admin.guard.index')->with('success', 'Guard deleted successfully.');
        }

        public function assign($id)
        {
            // Find the guard to be activated
            $guard = Admin::findOrFail($id);

            // Deactivate all other guards where type is 'guard'
            Admin::where('active', true)
                ->where('type', 'guard')
                ->update(['active' => false]);

            // Activate the selected guard
            $guard->update(['active' => true]);

            return redirect()->route('admin.guard.index')->with('success', 'Guard assigned successfully.');
        }


    }