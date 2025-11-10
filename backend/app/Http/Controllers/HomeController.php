<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Shows the custom home page, regardless of login status.
     * The home.blade.php view will then conditionally display links based on Auth::check().
     */
    public function index()
    {
        // We always show the 'home' view. 
        // We removed the 'redirect()->route('dashboard')' check.
        return view('home');
    }
}