<?php

namespace App\Http\Controllers;

use App\Models\Tags;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tags::with('icon')
            ->withCount(['taggables'])
            ->orderBy('taggables_count', 'desc')
            ->paginate(50);

        return view('tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'icon_id' => 'nullable|exists:files,id',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        $tag = Tags::create($validated);
        
        return redirect()->route('tags.show', $tag)
            ->with('success', 'Tag created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tags $tag)
    {
        $tag->load('icon');
        
        // Get tagged content
        $taggedPosts = $tag->taggables()
            ->where('taggable_type', 'App\Models\Posts')
            ->with('taggable.user')
            ->latest()
            ->paginate(20);
            
        return view('tags.show', compact('tag', 'taggedPosts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tags $tag)
    {
        return view('tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tags $tag)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $tag->id,
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'icon_id' => 'nullable|exists:files,id',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        $tag->update($validated);
        
        return redirect()->route('tags.show', $tag)
            ->with('success', 'Tag updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tags $tag)
    {
        $tag->delete();
        
        return redirect()->route('tags.index')
            ->with('success', 'Tag deleted successfully!');
    }
}