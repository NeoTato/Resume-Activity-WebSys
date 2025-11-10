<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            // --- MODIFIED: Changed 'email' to 'username' for validation ---
            'username' => ['required', 'string', 'max:255', 'unique:'.User::class], 
            
            // Removed the email validation rule as it is no longer collected
            
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            // --- MODIFIED: Storing 'username' from the request ---
            'username' => $request->username,
            
            // --- MODIFIED: Setting email to null/dummy as it's not collected ---
            // Assuming your PostgreSQL users table allows NULL for the email column.
            'email' => null, 
            
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}