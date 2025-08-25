<?php

namespace App\Http\Controllers;

use App\Models\Threads;
use App\Models\Thread_user;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThreadsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $threads = Threads::whereHas('users', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->with(['users'])
            ->latest('last_message_at')
            ->paginate(20);

        return view('threads.index', compact('threads'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'is_group' => 'boolean',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
        ]);

        $validated['created_by'] = Auth::id();
        
        $thread = Threads::create($validated);
        
        // Add creator to thread
        Thread_user::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'role' => 'owner',
        ]);
        
        // Add other users to thread
        foreach ($validated['user_ids'] as $userId) {
            if ($userId !== Auth::id()) {
                Thread_user::create([
                    'thread_id' => $thread->id,
                    'user_id' => $userId,
                    'role' => 'member',
                ]);
            }
        }
        
        return redirect()->route('threads.show', $thread)
            ->with('success', 'Thread created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Threads $thread)
    {
        $this->authorize('view', $thread);
        
        $thread->load(['users', 'messages.user']);
        
        // Mark messages as read
        $thread->messages()
            ->where('user_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        return view('threads.show', compact('thread'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Threads $thread)
    {
        $this->authorize('update', $thread);
        
        return view('threads.edit', compact('thread'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Threads $thread)
    {
        $this->authorize('update', $thread);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);
        
        $thread->update($validated);
        
        return redirect()->route('threads.show', $thread)
            ->with('success', 'Thread updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Threads $thread)
    {
        $this->authorize('delete', $thread);
        
        $thread->delete();
        
        return redirect()->route('threads.index')
            ->with('success', 'Thread deleted successfully!');
    }
}