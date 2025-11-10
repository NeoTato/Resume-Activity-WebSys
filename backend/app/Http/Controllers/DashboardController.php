<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect; // Import the Redirect facade

class DashboardController extends Controller
{
    public function index()
    {
        // Change from 'return view('dashboard');' to a direct redirect to the PHP file
        return Redirect::to('/dashboard.php');
    }
}