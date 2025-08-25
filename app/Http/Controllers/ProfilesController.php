<?php

namespace App\Http\Controllers;

use App\Models\Profiles;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProfilesRequest;
use App\Http\Requests\UpdateProfilesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profiles = Profiles::with('user')
            ->whereHas('user', function ($query) {
                $query->where('looking_for_work', 'yes');
            })
            ->latest()
            ->paginate(20);

        return view('profiles.index', compact('profiles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('profiles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProfilesRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        
        $profile = Profiles::create($validated);
        
        return redirect()->route('profiles.show', $profile)
            ->with('success', 'Profile created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Profiles $profiles)
    {
        $profiles->load('user');
        
        return view('profiles.show', compact('profiles'));
    }

    /**
     * Show user profile by username
     */
    public function showByUser(User $user)
    {
        $profile = $user->profile;
        
        if (!$profile) {
            abort(404, 'Profile not found');
        }
        
        return view('profiles.show', compact('profile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Profiles $profiles)
    {
        $this->authorize('update', $profiles);
        
        return view('profiles.edit', compact('profiles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfilesRequest $request, Profiles $profiles)
    {
        $this->authorize('update', $profiles);
        
        $validated = $request->validated();
        
        $profiles->update($validated);
        
        return redirect()->route('profiles.show', $profiles)
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profiles $profiles)
    {
        $this->authorize('delete', $profiles);
        
        $profiles->delete();
        
        return redirect()->route('profiles.index')
            ->with('success', 'Profile deleted successfully!');
    }
}