<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Event;
use Carbon\Carbon;

class ImportEventController extends Controller
{
    public function index()
    {
        return view('events.import');
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

        $validated['start_time'] = Carbon::parse($validated['start_time'])->format('Y-m-d H:i:s');
        $validated['end_time'] = Carbon::parse($validated['end_time'])->format('Y-m-d H:i:s');
        $validated['reminder_time'] = Carbon::parse($validated['reminder_time'])->format('Y-m-d H:i:s');

        Event::create($validated);
        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }
    
    private function formatDate($dateString)
    {
        return Carbon::createFromFormat('Y-m-d\TH:i:s.u\Z', $dateString)->format('Y-m-d H:i:s');
    }
}
