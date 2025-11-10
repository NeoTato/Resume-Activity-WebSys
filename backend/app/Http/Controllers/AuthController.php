<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect; // Use Redirect facade for simple redirects

class AuthController extends Controller
{
    // Redirects to the index.php file located in the public folder
    public function showIndex()
    {
        return Redirect::to('/index.php');
    }

    // Redirects to the login.php file located in the public folder
    public function showLogin()
    {
        return Redirect::to('/login.php');
    }

    // Redirects to the register.php file located in the public folder
    public function showRegister()
    {
        return Redirect::to('/register.php');
    }

    public function login(Request $request)
    {
        // For now, after "successful" login, redirect to the dashboard.php file
        return Redirect::to('/dashboard.php');
    }

    public function register(Request $request)
    {
        // Since the logic is in register.php, redirect there after registration post
        return Redirect::to('/login.php'); // Assuming your post logic redirects to login
    }

    public function logout(Request $request)
    {
        // When logout is hit, redirect to the logout.php file to destroy sessions
        return Redirect::to('/logout.php');
    }
}