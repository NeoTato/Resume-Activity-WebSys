<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Profile; 
use App\Models\Skill;      
use App\Models\Education;  
use App\Models\Project;    
use \Exception; 

class ResumeEditorController extends Controller
{
    /**
     * Replicates the setup.php logic to seed initial resume data for the logged-in user.
     * NOTE: This should only be used once for initial setup.
     */
    public function seedData()
    {
        $user = Auth::user();
        $userId = $user->id;

        // Use DB::transaction for safety, although the original used raw PHP queries
        try {
            DB::transaction(function () use ($userId) {
                
                // 1. Insert/Update Profile Data (from setup.php / fix_profile.php)
                Profile::updateOrCreate(
                    ['user_id' => $userId],
                    [
                        'fullname' => 'Eon Maxell P. Busque',
                        'email' => 'emp.busque38@gmail.com',
                        'phone' => '+63 976 054 2971',
                        'location' => 'Tayabas City, Quezon, Philippines',
                        'summary' => "A third-year college student taking Computer Science in Batangas State University who has a passion for software development. \n\n Currently learning Php and Flutter.",
                        'profile_picture' => 'assets/images/eon-profile-picture.png'
                    ]
                );

                // 2. Insert Skills
                Skill::where('user_id', $userId)->delete();
                $skills = ["Python", "Java", "HTML", "CSS", "MySQL", "C#"];
                $skillsToInsert = array_map(fn($s) => ['user_id' => $userId, 'skill_name' => $s], $skills);
                Skill::insert($skillsToInsert);

                // 3. Insert Education
                Education::where('user_id', $userId)->delete();
                Education::create([
                    'user_id' => $userId, 
                    'program' => 'Bachelor of Science in Computer Science', 
                    'start_year' => 2023, 
                    'end_year' => 2027, 
                    'university' => 'Batangas State University - The National Engineering University - Alangilan'
                ]);

                // 4. Insert Projects
                Project::where('user_id', $userId)->delete();
                Project::create([
                    'user_id' => $userId, 
                    'title' => 'Wellness Tracker', 
                    'description' => 'A wellness tracker integrating MySQL to monitor meals, workouts, and sleep, promoting health and well-being.'
                ]);
                Project::create([
                    'user_id' => $userId, 
                    'title' => 'AGAPAY: Arduino-based Water Agitator', 
                    'description' => 'Designed and developed an Arduino-powered water agitator system with a team, focusing on backend using C++ and Python.'
                ]);

            });
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Data Seeding Failed: ' . $e->getMessage());
        }

        return redirect()->route('dashboard')->with('status', 'Profile and Resume Data Successfully Seeded/Restored!');
    }


    /**
     * Display the resume editing form.
     */
    public function edit()
    {
        /** @var \App\Models\User $user */ // For IDE type hinting
        $user = Auth::user();

        // The IDE now knows $user is an App\Models\User, allowing load() and relationships to resolve.
        $user->load(['profile', 'skills', 'education', 'projects']); 

        return view('edit_resume', [
            'user' => $user,
            'profile' => $user->profile ?? new Profile(), 
            'skills' => $user->skills,
            'education' => $user->education,
            'projects' => $user->projects,
        ]);
    }

    /**
     * Handle the form submission and update the resume data.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */ // For IDE type hinting
        $user = Auth::user();
        $userId = $user->id; // Use the user object ID

        // Validate all fields, including the file
        $request->validate([
            'fullname' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
            'summary' => 'nullable|string',
            'profile_pic' => 'nullable|string|max:255', // Existing path/string
            'profile_pic_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // New file validation
        ]);

        // Start the transaction
        try {
            DB::transaction(function () use ($request, $userId, $user) {
                
                // --- PROFILE PICTURE HANDLING ---
                $profilePicturePath = $request->input('profile_pic'); // Start with existing hidden path

                if ($request->hasFile('profile_pic_file')) {
                    $imageFile = $request->file('profile_pic_file');
                    
                    // Generate unique filename: timestamp-user_id.extension
                    $filename = time() . '-' . $userId . '.' . $imageFile->getClientOriginalExtension();
                    
                    // Store the file in public/profiles folder on the public disk
                    $path = $imageFile->storeAs('profiles', $filename, 'public'); 

                    // Set the profile picture path to the storage location
                    $profilePicturePath = 'storage/' . $path;
                    
                    // NOTE: Add logic here in the future to delete the old profile image file.
                }

                // 1. Update Profile 
                Profile::updateOrCreate(
                    ['user_id' => $userId],
                    [
                        'fullname' => $request->fullname ?? null,
                        'email' => $request->email ?? null,
                        'phone' => $request->phone ?? null,
                        'location' => $request->location ?? null,
                        'summary' => $request->summary ?? null,
                        'profile_picture' => $profilePicturePath, // Updated path is saved here
                    ]
                );

                // 2. Update Skills (DELETE then INSERT)
                $user->skills()->delete();
                if (!empty($request->skills)) {
                    $skillsToInsert = [];
                    foreach ($request->skills as $skillName) {
                        if (!empty(trim($skillName))) {
                            $skillsToInsert[] = ['user_id' => $userId, 'skill_name' => trim($skillName)];
                        }
                    }
                    if (!empty($skillsToInsert)) {
                        $user->skills()->insert($skillsToInsert);
                    }
                }

                // 3. Update Education (DELETE then INSERT)
                $user->education()->delete();
                if (!empty($request->edu_program)) {
                    $educationToInsert = [];
                    for ($i = 0; $i < count($request->edu_program); $i++) {
                        if (!empty(trim($request->edu_program[$i]))) {
                            $educationToInsert[] = [
                                'user_id' => $userId,
                                'program' => $request->edu_program[$i],
                                'university' => $request->edu_university[$i],
                                'start_year' => $request->edu_start[$i] ? (int)$request->edu_start[$i] : null,
                                'end_year' => $request->edu_end[$i] ? (int)$request->edu_end[$i] : null,
                            ];
                        }
                    }
                    if (!empty($educationToInsert)) {
                        $user->education()->insert($educationToInsert);
                    }
                }

                // 4. Update Projects (DELETE then INSERT)
                $user->projects()->delete();
                if (!empty($request->project_title)) {
                    $projectsToInsert = [];
                    for ($i = 0; $i < count($request->project_title); $i++) {
                        if (!empty(trim($request->project_title[$i]))) {
                            $projectsToInsert[] = [
                                'user_id' => $userId,
                                'title' => $request->project_title[$i],
                                'description' => $request->project_desc[$i],
                            ];
                        }
                    }
                    if (!empty($projectsToInsert)) {
                        $user->projects()->insert($projectsToInsert);
                    }
                }
            });

            // If the transaction succeeds
            return redirect()->route('resume.edit')->with('status', 'Resume updated successfully!');

        } catch (\Exception $e) {
            // Revert all changes on failure
            return redirect()->back()->withInput()->withErrors(['update_failed' => 'Update Failed: ' . $e->getMessage()]);
        }
    }
}