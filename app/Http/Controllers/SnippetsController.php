<?php

namespace App\Http\Controllers;

use App\Models\Snippets;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SnippetsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $snippets = Snippets::with('user')
            ->where('is_public', 'yes')
            ->latest()
            ->paginate(20);

        return view('snippets.index', compact('snippets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('snippets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'language' => 'required|string|max:50',
            'code' => 'required|string',
            'description' => 'nullable|string',
            'is_public' => 'required|in:yes,no',
        ]);

        $validated['user_id'] = Auth::id();
        
        $snippet = Snippets::create($validated);
        
        return redirect()->route('snippets.show', $snippet)
            ->with('success', 'Snippet created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Snippets $snippet)
    {
        $snippet->load('user');
        
        return view('snippets.show', compact('snippet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Snippets $snippet)
    {
        $this->authorize('update', $snippet);
        
        return view('snippets.edit', compact('snippet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Snippets $snippet)
    {
        $this->authorize('update', $snippet);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'language' => 'required|string|max:50',
            'code' => 'required|string',
            'description' => 'nullable|string',
            'is_public' => 'required|in:yes,no',
        ]);
        
        $snippet->update($validated);
        
        return redirect()->route('snippets.show', $snippet)
            ->with('success', 'Snippet updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Snippets $snippet)
    {
        $this->authorize('delete', $snippet);
        
        $snippet->delete();
        
        return redirect()->route('snippets.index')
            ->with('success', 'Snippet deleted successfully!');
    }

    /**
     * Fork a snippet
     */
    public function fork(Snippets $snippet)
    {
        $forkedSnippet = $snippet->replicate();
        $forkedSnippet->user_id = Auth::id();
        $forkedSnippet->forked_from_id = $snippet->id;
        $forkedSnippet->version = 1;
        $forkedSnippet->save();
        
        return redirect()->route('snippets.show', $forkedSnippet)
            ->with('success', 'Snippet forked successfully!');
    }
}