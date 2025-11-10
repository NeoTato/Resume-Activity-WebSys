<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Resume</title>
    
    {{-- Compiled Vite assets for Tailwind/JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Custom Stylesheets --}}
    <link rel="stylesheet" href="{{ asset('assets/css/resumeStyles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/editStyles.css') }}">
</head>
<body>
    <div class="container">
        <h1>Edit Your Resume</h1>
        <p>Changes saved here will be visible on both your public resume page and view resume page.</p>

        <div class="dashboard-link-container">
            <a href="{{ route('dashboard') }}" class="btn-back">Back to Dashboard</a>
        </div>

        {{-- Laravel Session Status for Success --}}
        @if (session('status'))
            <div class="success-message">{{ session('status') }}</div>
        @endif
        
        {{-- Laravel Error Bag for Failure/Validation --}}
        @if ($errors->any())
            <div class="error-message">
                @if ($errors->has('update_failed'))
                    {{ $errors->first('update_failed') }}
                @else
                    Please correct the errors below.
                @endif
            </div>
        @endif

        {{-- The form action points to the update method in ResumeEditorController --}}
        <form action="{{ route('resume.update') }}" method="POST" enctype="multipart/form-data" class="edit-form">
            @csrf {{-- CRITICAL: Laravel's required CSRF token --}}
            
            <h2>Profile</h2>

            {{-- 1. Display the CURRENT Profile Picture Path (as a hidden field) --}}
            <input type="hidden" id="profile_pic" name="profile_pic" value="{{ old('profile_pic', htmlspecialchars($profile->profile_picture ?? '')) }}">

            {{-- 2. New Label for the File Upload Area --}}
            <label for="profile_pic_file">Upload New Profile Picture</label>

            {{-- 3. Custom Styled File Input Area --}}
            <div class="file-upload-container">
                {{-- This is the visible, styled button --}}
                <label for="profile_pic_file" class="btn-back btn-browse-style">Choose File</label>
                
                {{-- This is the functional file input, hidden from view --}}
                <input type="file" id="profile_pic_file" name="profile_pic_file" accept="image/*" style="display: none;">
                
                {{-- Display the selected filename --}}
                <span id="file-name-display" class="file-name-display">No file chosen</span>
            </div>
            
            <label for="fullname">Full Name</label>
            {{-- Use old() for persistence on error; use profile object property --}}
            <input type="text" id="fullname" name="fullname" value="{{ old('fullname', htmlspecialchars($profile->fullname ?? '')) }}">
            
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', htmlspecialchars($profile->email ?? '')) }}">
            
            <label for="phone">Phone</label>
            <input type="tel" id="phone" name="phone" value="{{ old('phone', htmlspecialchars($profile->phone ?? '')) }}">
            
            <label for="location">Location</label>
            <input type="text" id="location" name="location" value="{{ old('location', htmlspecialchars($profile->location ?? '')) }}">
            
            <label for="summary">Summary</label>
            <textarea id="summary" name="summary" rows="5">{{ old('summary', htmlspecialchars($profile->summary ?? '')) }}</textarea>

            <hr class="form-divider">

            <h2>Skills</h2>
            <div id="skills-container">
                @foreach ($skills as $skill)
                    <div class="dynamic-item">
                        <input type="text" name="skills[]" value="{{ htmlspecialchars($skill->skill_name) }}" placeholder="e.g., Python">
                        <button type="button" class="btn-remove" onclick="removeItem(this)">Remove</button>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn-add" onclick="addSkill()">Add Skill</button>
            
            <hr class="form-divider">

            <h2>Education</h2>
            <div id="education-container">
                @foreach ($education as $edu)
                    <div class="dynamic-item">
                        <div class="item-fields">
                            <label>Program/Degree</label>
                            <input type="text" name="edu_program[]" value="{{ htmlspecialchars($edu->program) }}" placeholder="Program/Degree">
                            <label>University/School</label>
                            <input type="text" name="edu_university[]" value="{{ htmlspecialchars($edu->university) }}" placeholder="University/School">
                            <label>Start Year</label>
                            <input type="number" name="edu_start[]" value="{{ htmlspecialchars($edu->start_year) }}" placeholder="Start Year (e.g., 2020)">
                            <label>End Year</label>
                            <input type="number" name="edu_end[]" value="{{ htmlspecialchars($edu->end_year) }}" placeholder="End Year (e.g., 2024 or 0 for Present)">
                        </div>
                        <button type="button" class="btn-remove" onclick="removeItem(this)">Remove</button>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn-add" onclick="addEducation()">Add Education</button>

            <hr class="form-divider">

            <h2>Projects</h2>
            <div id="projects-container">
                @foreach ($projects as $proj)
                    <div class="dynamic-item">
                        <div class="item-fields">
                            <label>Project Title</label>
                            <input type="text" name="project_title[]" value="{{ htmlspecialchars($proj->title) }}" placeholder="Project Title">
                            <label>Description</label>
                            <textarea name="project_desc[]" placeholder="Project Description">{{ htmlspecialchars($proj->description) }}</textarea>
                        </div>
                        <button type="button" class="btn-remove" onclick="removeItem(this)">Remove</button>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn-add" onclick="addProject()">Add Project</button>
            
            <button type="submit" class="btn-save">Save All Changes</button>
        </form>
    </div>

    {{-- The Javascript functions for dynamic fields --}}
    <script>
        function removeItem(button) {
            button.closest('.dynamic-item').remove();
        }

        function addSkill() {
            const container = document.getElementById('skills-container');
            const newItem = document.createElement('div');
            newItem.className = 'dynamic-item';
            newItem.innerHTML = `
                <input type="text" name="skills[]" placeholder="e.g., New Skill">
                <button type="button" class="btn-remove" onclick="removeItem(this)">Remove</button>
            `;
            container.appendChild(newItem);
        }

        function addEducation() {
            const container = document.getElementById('education-container');
            const newItem = document.createElement('div');
            newItem.className = 'dynamic-item';
            newItem.innerHTML = `
                <div class="item-fields">
                    <label>Program/Degree</label>
                    <input type="text" name="edu_program[]" placeholder="Program/Degree">
                    <label>University/School</label>
                    <input type="text" name="edu_university[]" placeholder="University/School">
                    <label>Start Year</label>
                    <input type="number" name="edu_start[]" placeholder="Start Year (e.g., 2020)">
                    <label>End Year</label>
                    <input type="number" name="edu_end[]" placeholder="End Year (e.g., 2024 or 0 for Present)">
                </div>
                <button type="button" class="btn-remove" onclick="removeItem(this)">Remove</button>
            `;
            container.appendChild(newItem);
        }

        function addProject() {
            const container = document.getElementById('projects-container');
            const newItem = document.createElement('div');
            newItem.className = 'dynamic-item';
            newItem.innerHTML = `
                <div class="item-fields">
                    <label>Project Title</label>
                    <input type="text" name="project_title[]" placeholder="Project Title">
                    <label>Description</label>
                    <textarea name="project_desc[]" placeholder="Project Description"></textarea>
                </div>
                <button type="button" class="btn-remove" onclick="removeItem(this)">Remove</button>
            `;
            container.appendChild(newItem);
        }
    </script>
</body>
</html>