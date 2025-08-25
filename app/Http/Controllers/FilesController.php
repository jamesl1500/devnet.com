<?php

namespace App\Http\Controllers;

use App\Models\Files;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFilesRequest;
use App\Http\Requests\UpdateFilesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $files = Files::where('user_id', Auth::id())
            ->latest()
            ->paginate(20);

        return view('files.index', compact('files'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('files.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFilesRequest $request)
    {
        $validated = $request->validated();
        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('uploads', 'public');
            
            $fileRecord = Files::create([
                'user_id' => Auth::id(),
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
            ]);
            
            return response()->json([
                'status' => 'success',
                'file' => $fileRecord,
                'url' => Storage::url($path)
            ]);
        }
        
        return response()->json([
            'status' => 'error',
            'message' => 'No file uploaded.'
        ], 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(Files $files)
    {
        $this->authorize('view', $files);
        
        return view('files.show', compact('files'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Files $files)
    {
        $this->authorize('update', $files);
        
        return view('files.edit', compact('files'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFilesRequest $request, Files $files)
    {
        $this->authorize('update', $files);
        
        $validated = $request->validated();
        
        $files->update($validated);
        
        return redirect()->route('files.show', $files)
            ->with('success', 'File updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Files $files)
    {
        $this->authorize('delete', $files);
        
        // Delete file from storage
        if (Storage::disk('public')->exists($files->file_path)) {
            Storage::disk('public')->delete($files->file_path);
        }
        
        $files->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'File deleted successfully!'
        ]);
    }
}