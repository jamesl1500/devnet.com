<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostsRequest;
use App\Http\Requests\UpdatePostsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Posts::with(['user', 'group', 'cover'])
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->latest()
            ->paginate(20);

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostsRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        $validated['slug'] = Str::slug($validated['title']);
        
        $post = Posts::create($validated);
        
        return redirect()->route('posts.show', $post)
            ->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Posts $posts)
    {
        $posts->load(['user', 'group', 'cover', 'comments.user']);
        
        return view('posts.show', compact('posts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Posts $posts)
    {
        $this->authorize('update', $posts);
        
        return view('posts.edit', compact('posts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostsRequest $request, Posts $posts)
    {
        $this->authorize('update', $posts);
        
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['title']);
        
        $posts->update($validated);
        
        return redirect()->route('posts.show', $posts)
            ->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Posts $posts)
    {
        $this->authorize('delete', $posts);
        
        $posts->delete();
        
        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully!');
    }
}
