<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePostsRequest;
use App\Models\Files;
use App\Models\Posts_media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
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
    public function store(Request $request)
    {
        $validation = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'required|string',
            'privacy' => 'required|in:public,private,followers',
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:10240',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,JPEG,PNG,JPG,GIF,SVG|max:10240',
        ]);

        // If all good, create post
        $post = new Posts();

        $post->user_id = Auth::id();
        $post->slug = substr(Crypt::encrypt(Str::slug($validation['title'] ?? Str::limit($validation['content'], 30))), 0, 100);
        $post->title = $validation['title'] ?? null;
        $post->body = $validation['content'];
        $post->visibility = $validation['privacy'];
        $post->status = 'published'; // For now, all posts are published immediately
        $post->type = 'text'; // For now, all posts are standard

        // Save post
        $post->save();

        // now see if we have images or attachments to handle
        if(isset($validation['images']) && $validation['images'])
        {
            // Handle image uploads
            foreach($validation['images'] as $image)
            {
                // Create a new file record
                $file = Files::create([
                    'user_id' => Auth::id(),
                    'file_name' => $image->getClientOriginalName(),
                    'file_path' => $image->store('posts/'.$post->id.'/images'),
                    'file_type' => $image->getClientMimeType(),
                    'file_size' => $image->getSize(),
                ]);

                // Lets save the file as post media
                $media = Posts_media::create([
                    'post_id' => $post->id,
                    'file_id' => $file->id,
                    'type' => 'image',
                    'order' => 0, 
                ]);
            }
        }

        if(isset($validation['attachments']) && $validation['attachments'])
        {
            // Handle file uploads
            foreach($validation['attachments'] as $file)
            {
                // Create a new file record
                $fileRecord = Files::create([
                    'user_id' => Auth::id(),
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $file->store('posts/'.$post->id.'/files'),
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);

                // Lets save the file as post media
                $media = Posts_media::create([
                    'post_id' => $post->id,
                    'file_id' => $fileRecord->id,
                    'type' => 'file',
                    'order' => 0, 
                ]);
            }
        }

        // Return JSON response
        return response()->json([
            'success' => true,
            'message' => 'Post created successfully!',
            'post' => $post,
        ], 201);
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
