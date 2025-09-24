<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Files;

class SettingsController extends Controller
{
    /**
     * Index
     * ----
     * Display initial settings page
     */
    public function index()
    {
        // Render view
        return view('pages.settings.index');
    }

    /**
     * Index (PUT)
     * ----
     * Handle initial basic info settings form submission
     */
    public function index_post(Request $request)
    {
        // Validate request
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        // Make sure username isnt taken by another user
        $existing_user = User::where('username', $request->username)->first();

        if($existing_user && $existing_user->id != auth()->user()->id){
            return back()->withErrors(['username' => 'Username is already taken.'])->withInput();
        }

        // Making sure email isnt taken by another user
        $existing_email = User::where('email', $request->email)->first();

        if($existing_email && $existing_email->id != auth()->user()->id){
            return back()->withErrors(['email' => 'Email is already taken.'])->withInput();
        }

        // Update user
        $user = auth()->user();

        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;

        // Save user
        $user->save();

        // Redirect back with success message
        return back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Update Password (PUT)
     * ----
     * Handle password update form submission
     */
    public function update_password(Request $request)
    {
        // Validate request
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Check if current password is correct
        if(!Hash::check($request->current_password, auth()->user()->password)){
            return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
        }

        // Update password
        $user = auth()->user();
        $user->password = Hash::make($request->new_password);

        // Save user
        $user->save();

        // Redirect back with success message
        return back()->with('success', 'Password updated successfully.');
    }

    /**
     * Delete Account (DELETE)
     * ----
     * Handle account deletion
     */
    public function delete_account(Request $request)
    {
        // Validate request
        $request->validate([
            'password' => 'required|string',
        ]);

        // Check if password is correct
        if(!Hash::check($request->password, auth()->user()->password)){
            return back()->withErrors(['password' => 'Password is incorrect.'])->withInput();
        }

        // Logout user
        $user = auth()->user();
        auth()->logout();

        // Delete the user
        $user->delete();
        $request->session()->invalidate();

        // Redirect to home with success message
        return redirect('/')->with('success', 'Account deleted successfully.');
    }

    /**
     * Profile
     * ----
     * Display profile settings page
     */
    public function profile()
    {
        // Lets get the users current avatar and cover photo
        $user = auth()->user();

        $avatar_id = $user->avatar_id;
        $cover_id = $user->cover_id;

        // Find file record for both (if they exist)
        $avatar = null;
        $cover = null;

        // Avatar file info
        if($avatar_id){
            $avatar = Files::find($avatar_id);
        }

        // Cover file info
        if($cover_id){
            $cover = Files::find($cover_id);
        }
        
        // Render view
        return view('pages.settings.profile', compact('avatar', 'cover'));
    }

    /**
     * Update Avatar & Cover Photo (POST)
     * ----
     * Handle avatar and cover photo update form submission
     */
    public function update_avatar_cover(Request $request)
    {

    }

    /**
     * Notifications
     * ----
     * Display notifications settings page
     */
    public function notifications()
    {
        // Render view
        return view('pages.settings.notifications');
    }

    /**
     * Privacy
     * ----
     * Display privacy settings page
     */
    public function privacy()
    {
        // Render view
        return view('pages.settings.privacy');
    }

    /**
     * Security
     * ----
     * Display security settings page
     */
    public function security()
    {
        // Render view
        return view('pages.settings.security');
    }
}
