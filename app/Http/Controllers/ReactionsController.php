<?php

namespace App\Http\Controllers;

use App\Models\Reactions;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReactionsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reactable_type' => 'required|string',
            'reactable_id' => 'required|integer',
            'type' => 'required|in:like,dislike,love,laugh,angry,sad,surprised',
        ]);

        $validated['user_id'] = Auth::id();

        // Check if user already reacted to this item
        $existingReaction = Reactions::where([
            'user_id' => Auth::id(),
            'reactable_type' => $validated['reactable_type'],
            'reactable_id' => $validated['reactable_id'],
        ])->first();

        if ($existingReaction) {
            if ($existingReaction->type === $validated['type']) {
                // Remove reaction if same type
                $existingReaction->delete();
                return response()->json(['status' => 'removed']);
            } else {
                // Update reaction type
                $existingReaction->update(['type' => $validated['type']]);
                return response()->json(['status' => 'updated', 'reaction' => $existingReaction]);
            }
        }

        $reaction = Reactions::create($validated);
        
        return response()->json(['status' => 'created', 'reaction' => $reaction]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reactions $reaction)
    {
        $this->authorize('delete', $reaction);
        
        $reaction->delete();
        
        return response()->json(['status' => 'deleted']);
    }
}