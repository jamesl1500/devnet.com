<?php

namespace App\Http\Controllers;

use App\Models\Follows;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFollowsRequest;
use App\Http\Requests\UpdateFollowsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $following = Follows::where('follower_id', Auth::id())
            ->with('followable')
            ->latest()
            ->paginate(20);

        return view('follows.index', compact('following'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Following is typically done via AJAX
        return response()->json(['error' => 'Use store method instead'], 405);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFollowsRequest $request)
    {
        $validated = $request->validated();
        $validated['follower_id'] = Auth::id();
        
        // Check if already following
        $existingFollow = Follows::where([
            'follower_id' => Auth::id(),
            'followable_type' => $validated['followable_type'],
            'followable_id' => $validated['followable_id'],
        ])->first();
        
        if ($existingFollow) {
            return response()->json([
                'status' => 'error',
                'message' => 'Already following this item.'
            ], 409);
        }
        
        $follow = Follows::create($validated);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully followed!',
            'follow' => $follow
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Follows $follows)
    {
        return view('follows.show', compact('follows'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Follows $follows)
    {
        // Following doesn't typically need editing
        return response()->json(['error' => 'Method not supported'], 405);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFollowsRequest $request, Follows $follows)
    {
        // Following doesn't typically need updating
        return response()->json(['error' => 'Method not supported'], 405);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Follows $follows)
    {
        $this->authorize('delete', $follows);
        
        $follows->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully unfollowed!'
        ]);
    }

    /**
     * Unfollow by followable type and ID
     */
    public function unfollow(Request $request)
    {
        $validated = $request->validate([
            'followable_type' => 'required|string',
            'followable_id' => 'required|integer',
        ]);

        $follow = Follows::where([
            'follower_id' => Auth::id(),
            'followable_type' => $validated['followable_type'],
            'followable_id' => $validated['followable_id'],
        ])->first();

        if (!$follow) {
            return response()->json([
                'status' => 'error',
                'message' => 'Not following this item.'
            ], 404);
        }

        $follow->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully unfollowed!'
        ]);
    }
}