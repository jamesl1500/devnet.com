<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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


}
