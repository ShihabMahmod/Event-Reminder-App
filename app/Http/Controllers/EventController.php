<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Validator;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
   
    public function index()
    {
        try {
            $id = Auth::User()->id;
            $upcomingEvents = Event::where('status', 'upcoming')->where('user_id',$id)->get();
            $completedEvents = Event::where('status', 'completed')->where('user_id',$id)->get();
            return view('events.index', compact('upcomingEvents', 'completedEvents'));

        } catch (\Exception $e) {
            \Log::error('Failed to fetch events: ' . $e->getMessage());
            return back()->withErrors('Failed to fetch events. Please try again later.');
        }

        
    }
    public function create()
    {
        return view('events.create');
    }
    public function store(Request $request)
    {
        $validator = $this->validateRequest($request);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $event = new Event();
            $event->user_id = Auth::User()->id; 
            $event->title = $request->title; 
            $event->description = $request->description; 
            $event->start_time = Carbon::parse($request->start_time); 
            $event->end_time = Carbon::parse($request->end_time); 
            $event->reminder_time = Carbon::parse($request->reminder_time);
            $event->save();

            return response()->json(['success' => 'Event created successfully.'], 200);

        } catch (\Exception $e) {
            \Log::error('Failed to create event: ' . $e->getMessage());
            return back()->withErrors('Failed to create event. Please try again later.');
        }
    }

    public function edit(string $id)
    {
        try {

            $event = Event::select('id','title','description','start_time','end_time','reminder_time','status')->findOrFail($id);
            return view('events.edit', compact('event'));

        } catch (ModelNotFoundException $e) {
            \Log::error('Event not found: ' . $e->getMessage());
            return response()->json(['errors' => $e->getMessage()], 422);

        } catch (\Exception $e) {
            \Log::error('Failed to fetch event for editing: ' . $e->getMessage());
            return response()->json(['errors' => $e->getMessage()], 422);
        }

        
    }

    public function update(Request $request, string $id)
    {
        $validator = $this->validateRequest($request);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $event = Event::findOrFail($id);
            $event->title = $request->title; 
            $event->description = $request->description; 
            $event->start_time = $request->start_time; 
            $event->end_time = $request->end_time; 
            $event->reminder_time = $request->reminder_time;
            $event->save();

            return response()->json(['success' => 'Event Update successfully.'], 200);

        } catch (ModelNotFoundException $e) {
            \Log::error('Event not found for update: ' . $e->getMessage());
            return response()->json(['errors' => $e->getMessage()], 422);
            

        } catch (\Exception $e) {
            \Log::error('Failed to update event: ' . $e->getMessage());
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }

    public function destroy(string $id)
    {
        try {

            $event = Event::findOrFail($id);
            $event->delete();
            return redirect()->route('event.index')->with('success', 'Event deleted successfully.');

        } catch (ModelNotFoundException $e) {
           
            \Log::error('Event not found for deletion: ' . $e->getMessage());
            return back()->withErrors('Event not found.');
        } catch (\Exception $e) {
           
            \Log::error('Failed to delete event: ' . $e->getMessage());
            return back()->withErrors('Failed to delete event. Please try again later.');
        }
        
    }

    protected function validateRequest(Request $request)
    {
        return  Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'reminder_time' => 'required|date',
        ]);
    }
    
}
