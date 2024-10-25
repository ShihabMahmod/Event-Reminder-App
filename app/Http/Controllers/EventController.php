<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
   
    public function index()
    {
        $upcomingEvents = Event::where('status', 'upcoming')->get();
        $completedEvents = Event::where('status', 'completed')->get();
        return view('events.index', compact('upcomingEvents', 'completedEvents'));
    }
    public function create()
    {
        return view('events.create');
    }
    public function store(Request $request)
    {


        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'ticket_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'reminder_time' => 'required|date',
        ]);

        Event::create($validated);
        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }

    public function edit(string $id)
    {
        $event = Event::select('id','title','ticket_price','description','start_time','end_time','reminder_time','status')->findOrFail($id);
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, string $id)
    {
       
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'ticket_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'reminder_time' => 'required|date',
        ]);
        $event = Event::findOrFail($id);
        $event->update($validated);

        return redirect()->route('events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }
    
}
