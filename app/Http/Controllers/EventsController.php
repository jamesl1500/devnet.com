<?php

namespace App\Http\Controllers;

use App\Models\Events;
use App\Models\Event_users;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventsRequest;
use App\Http\Requests\UpdateEventsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Events::with(['users', 'comments'])
            ->where('start_time', '>=', now())
            ->latest('start_time')
            ->paginate(20);

        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventsRequest $request)
    {
        $validated = $request->validated();
        $validated['creator_id'] = Auth::id();
        
        $event = Events::create($validated);
        
        // Add creator as organizer
        Event_users::create([
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'status' => 'going',
            'role' => 'organizer',
        ]);
        
        return redirect()->route('events.show', $event)
            ->with('success', 'Event created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Events $events)
    {
        $events->load(['users.user', 'comments.user']);
        
        $userStatus = null;
        if (Auth::check()) {
            $userStatus = Event_users::where([
                'event_id' => $events->id,
                'user_id' => Auth::id(),
            ])->first();
        }
        
        return view('events.show', compact('events', 'userStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Events $events)
    {
        $this->authorize('update', $events);
        
        return view('events.edit', compact('events'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventsRequest $request, Events $events)
    {
        $this->authorize('update', $events);
        
        $validated = $request->validated();
        
        $events->update($validated);
        
        return redirect()->route('events.show', $events)
            ->with('success', 'Event updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Events $events)
    {
        $this->authorize('delete', $events);
        
        $events->delete();
        
        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully!');
    }

    /**
     * Join or update status for an event
     */
    public function updateStatus(Request $request, Events $event)
    {
        $validated = $request->validate([
            'status' => 'required|in:going,interested,not_going',
        ]);

        $eventUser = Event_users::updateOrCreate([
            'event_id' => $event->id,
            'user_id' => Auth::id(),
        ], [
            'status' => $validated['status'],
            'role' => 'attendee',
        ]);
        
        return response()->json([
            'status' => 'success',
            'user_status' => $eventUser->status,
            'message' => 'Status updated successfully!'
        ]);
    }
}