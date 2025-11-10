<?php

// app/Http/Controllers/ResumeController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ResumeController extends Controller
{
    /**
     * Reusable method to load user resume data using Eloquent.
     */
    protected function loadResume(int $userId, bool $isAuthenticated = false)
    {
        // Use Eloquent to load the User and all associated data with one query
        $user = User::with(['profile', 'skills', 'education', 'projects'])
                    ->find($userId);

        if (!$user || !$user->profile) {
            // This is where the error occurs if the profile is missing!
            abort(404, "Resume profile not found. Please setup your profile first."); 
        }

        return view('resume', [
            'profile' => $user->profile,
            'skills' => $user->skills,
            'education' => $user->education,
            'projects' => $user->projects,
            'is_authenticated' => $isAuthenticated,
        ]);
    }

    /**
     * Handles the view for the public resume (User ID 1 by default).
     */
    public function showPublic()
    {
        // Default to user ID 1 for public view, as per your original file
        return $this->loadResume(1);
    }

    /**
     * Handles the view for the authenticated user's resume.
     */
    public function showPrivate() // <-- THIS IS THE METHOD THE ROUTE NEEDS
    {
        $userId = Auth::id();

        if (!$userId) {
            return redirect()->route('login');
        }

        return $this->loadResume($userId, true);
    }
}