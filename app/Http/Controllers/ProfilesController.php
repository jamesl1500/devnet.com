<?php

namespace App\Http\Controllers;

use App\Models\Profiles;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProfilesRequest;
use App\Http\Requests\UpdateProfilesRequest;

class ProfilesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProfilesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Profiles $profiles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Profiles $profiles)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfilesRequest $request, Profiles $profiles)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profiles $profiles)
    {
        //
    }
}
