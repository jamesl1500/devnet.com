<?php

namespace App\Http\Controllers;

use App\Models\Groups;
use App\Models\Group_users;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGroupsRequest;
use App\Http\Requests\UpdateGroupsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = Groups::with(['icon', 'cover'])
            ->where('visibility', 'public')
            ->withCount('users')
            ->latest()
            ->paginate(20);

        return view('groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('groups.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGroupsRequest $request)
    {
        $validated = $request->validated();
        $validated['owner_id'] = Auth::id();
        $validated['slug'] = Str::slug($validated['name']);
        
        $group = Groups::create($validated);
        
        // Add creator as owner
        Group_users::create([
            'group_id' => $group->id,
            'user_id' => Auth::id(),
            'role' => 'owner',
            'joined_at' => now(),
        ]);
        
        return redirect()->route('groups.show', $group)
            ->with('success', 'Group created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Groups $groups)
    {
        $groups->load(['icon', 'cover', 'users.user']);
        
        $userMembership = null;
        if (Auth::check()) {
            $userMembership = Group_users::where([
                'group_id' => $groups->id,
                'user_id' => Auth::id(),
            ])->first();
        }
        
        return view('groups.show', compact('groups', 'userMembership'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Groups $groups)
    {
        $this->authorize('update', $groups);
        
        return view('groups.edit', compact('groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGroupsRequest $request, Groups $groups)
    {
        $this->authorize('update', $groups);
        
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);
        
        $groups->update($validated);
        
        return redirect()->route('groups.show', $groups)
            ->with('success', 'Group updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Groups $groups)
    {
        $this->authorize('delete', $groups);
        
        $groups->delete();
        
        return redirect()->route('groups.index')
            ->with('success', 'Group deleted successfully!');
    }

    /**
     * Join a group
     */
    public function join(Groups $group)
    {
        if ($group->visibility === 'private') {
            return response()->json([
                'status' => 'error',
                'message' => 'This group requires approval to join.'
            ], 403);
        }

        $membership = Group_users::firstOrCreate([
            'group_id' => $group->id,
            'user_id' => Auth::id(),
        ], [
            'role' => 'member',
            'joined_at' => now(),
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully joined the group!',
            'membership' => $membership
        ]);
    }

    /**
     * Leave a group
     */
    public function leave(Groups $group)
    {
        $membership = Group_users::where([
            'group_id' => $group->id,
            'user_id' => Auth::id(),
        ])->first();

        if (!$membership) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not a member of this group.'
            ], 404);
        }

        if ($membership->role === 'owner') {
            return response()->json([
                'status' => 'error',
                'message' => 'Group owners cannot leave their own group.'
            ], 403);
        }

        $membership->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully left the group!'
        ]);
    }
}