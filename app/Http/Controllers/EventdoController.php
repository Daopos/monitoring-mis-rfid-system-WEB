<?php

namespace App\Http\Controllers;

use App\Models\Eventdo;
use Illuminate\Http\Request;

class EventdoController extends Controller
{
    public function index()
    {
        $events = EventDo::all();
        return view('admin.event', compact('events'));
    }

    // Show the form for creating a new resource
    public function create()
    {
        return view('admin.eventcreate');
    }

    // Store a newly created resource in storage
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'start' => 'required|date',
                'end' => 'required|date|after:start',
            ]);

            EventDo::create($request->all());

            return redirect()->route('eventdos.index')->with('success', 'Event created successfully.');
        } catch (\Exception $e) {
            // Flash an error message
            return redirect()->route('eventdos.index')->with('error', 'Error adding Activity. Please try again.');
        }
    }

    // Display the specified resource
    public function show(EventDo $eventdo)
    {
        return view('eventdos.show', compact('eventdo'));
    }

    // Show the form for editing the specified resource
    public function edit(EventDo $eventdo)
    {
        return view('eventdos.edit', compact('eventdo'));
    }

    // Update the specified resource in storage
    public function update(Request $request, $id)
{
    $event = EventDo::findOrFail($id);
    $event->title = $request->title;
    $event->start = $request->start;
    // $event->end = $request->end; // If you have 'end' field
    // $event->status = $request->status; // If you have 'status' field
    $event->save();

    return redirect()->route('eventdos.index')->with('success', 'Event updated successfully!');
}

    // Remove the specified resource from storage
    public function destroy(EventDo $eventdo)
    {
        $eventdo->delete();
        return redirect()->route('eventdos.index')->with('success', 'Event deleted successfully.');
    }


    //api
    public function getEventAPI()
    {
        $events = EventDo::orderBy('created_at', 'desc')->get(); // Retrieve all events ordered by created_at descending

        return response()->json($events); // Return as JSON
    }

}