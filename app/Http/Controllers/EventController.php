<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Validator;
use Auth;
use Carbon\Carbon;


class EventController extends Controller
{
   
    public function index()
    {
        $id = Auth::User()->id;
        $upcomingEvents = Event::where('status', 'upcoming')->where('user_id',$id)->get();
        $completedEvents = Event::where('status', 'completed')->where('user_id',$id)->get();
        return view('events.index', compact('upcomingEvents', 'completedEvents'));
    }
    public function create()
    {
        return view('events.create');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'reminder_time' => 'required|date',
        ]);
        
        if ($validator->fails()) {
            return $validator->errors();
            // redirect()->back()
            //                  ->withErrors($validator)
            //                  ->withInput();
        }
    
        $event = new Event();
        $event->user_id = Auth::User()->id; 
        $event->title = $request->title; 
        $event->description = $request->description; 
        $event->start_time = Carbon::parse($request->start_time); 
        $event->end_time = Carbon::parse($request->end_time); 
        $event->reminder_time = Carbon::parse($request->reminder_time);
        $event->save();

        return redirect()->route('event.index')->with('success', 'Event created successfully.');
    }

    public function edit(string $id)
    {
        $event = Event::select('id','title','description','start_time','end_time','reminder_time','status')->findOrFail($id);
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, string $id)
    {
       
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'reminder_time' => 'required|date',
        ]);
        
        if ($validator->fails()) {
            return $validator->errors();
            // redirect()->back()
            //                  ->withErrors($validator)
            //                  ->withInput();
        }
    
        $event = Event::findOrFail($id);
        $event->title = $request->title; 
        $event->description = $request->description; 
        $event->start_time = $request->start_time; 
        $event->end_time = $request->end_time; 
        $event->reminder_time = $request->reminder_time;
        $event->save();

        return redirect()->route('event.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        return redirect()->route('event.index')->with('success', 'Event deleted successfully.');
    }
    
}
