<?php

namespace App\Http\Controllers;

use App\Models\Messages;
use App\Models\Threads;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessagesRequest;
use App\Http\Requests\UpdateMessagesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Messages are typically shown within threads
        return redirect()->route('threads.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('messages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMessagesRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        
        // Verify user is part of the thread
        $thread = Threads::findOrFail($validated['thread_id']);
        $this->authorize('view', $thread);
        
        $message = Messages::create($validated);
        
        // Update thread's last message time
        $thread->update(['last_message_at' => now()]);
        
        return response()->json([
            'status' => 'success',
            'message' => $message->load('user'),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Messages $messages)
    {
        $this->authorize('view', $messages);
        
        $messages->load(['user', 'thread']);
        
        return view('messages.show', compact('messages'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Messages $messages)
    {
        $this->authorize('update', $messages);
        
        return view('messages.edit', compact('messages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMessagesRequest $request, Messages $messages)
    {
        $this->authorize('update', $messages);
        
        $validated = $request->validated();
        
        $messages->update($validated);
        
        return response()->json([
            'status' => 'success',
            'message' => $messages->load('user'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Messages $messages)
    {
        $this->authorize('delete', $messages);
        
        $messages->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Message deleted successfully!'
        ]);
    }
}
