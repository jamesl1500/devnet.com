<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentsRequest;
use App\Http\Requests\UpdateCommentsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comments::with(['user', 'parent'])
            ->whereNull('parent_id')
            ->latest()
            ->paginate(20);

        return view('comments.index', compact('comments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('comments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentsRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        
        $comment = Comments::create($validated);
        
        return response()->json([
            'status' => 'success',
            'comment' => $comment->load('user'),
            'message' => 'Comment posted successfully!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comments $comments)
    {
        $comments->load(['user', 'parent', 'children.user']);
        
        return view('comments.show', compact('comments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comments $comments)
    {
        $this->authorize('update', $comments);
        
        return view('comments.edit', compact('comments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentsRequest $request, Comments $comments)
    {
        $this->authorize('update', $comments);
        
        $validated = $request->validated();
        
        $comments->update($validated);
        
        return response()->json([
            'status' => 'success',
            'comment' => $comments->load('user'),
            'message' => 'Comment updated successfully!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comments $comments)
    {
        $this->authorize('delete', $comments);
        
        $comments->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Comment deleted successfully!'
        ]);
    }
}