<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- We will load resumeStyles.css, but first we should link to the compiled Laravel assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js']) 
    <link rel="stylesheet" href="{{ asset('assets/css/resumeStyles.css') }}">
    
    <title>{{ htmlspecialchars($profile->fullname ?? 'Resume') }}</title>
</head>

<body>
    <div class="container">
        <header>    
            <div class="profile-pic">
                {{-- Access data directly via Eloquent object properties --}}
                <img src="{{ asset($profile->profile_picture ?? 'assets/images/default-profile.png') }}" alt="profile picture">
            </div>
        </header>
        <main>
            <h1>
                {{ htmlspecialchars($profile->fullname ?? '') }}
            </h1>

            <section id="contact">
                <p>
                    <a href="mailto:{{ htmlspecialchars($profile->email ?? '') }}">
                        <img src="{{ asset('assets/images/mail.png') }}" alt="Email icon" width="22px">
                        {{ htmlspecialchars($profile->email ?? '') }}
                    </a>
                    <a href="tel:{{ htmlspecialchars($profile->phone ?? '') }}">
                        <img src="{{ asset('assets/images/phone.png') }}" alt="Phone icon" width="22px">
                        {{ htmlspecialchars($profile->phone ?? '') }}
                    </a>
                    <span>
                        <img src="{{ asset('assets/images/location.png') }}" alt="Location icon" width="22px">
                        {{ htmlspecialchars($profile->location ?? '') }}
                    </span>
                </p>
            </section>

            <hr>

            <section>
                <p>
                    {{-- FIX: Ensures newlines are converted to <br> tags and rendered unescaped --}}
                    {!! nl2br(htmlspecialchars($profile->summary ?? '')) !!}
                </p>
            </section>

            <hr>

            <section>
                <h2>Projects</h2>
                <dl>
                    {{-- Blade foreach loop replaces PHP foreach loop --}}
                    @foreach ($projects as $project)
                        <dt><h3>{{ htmlspecialchars($project->title) }}</h3></dt>
                        <dd>{{ htmlspecialchars($project->description) }}</dd>
                    @endforeach
                </dl>
            </section>

            <hr>

            <section>
                <h2> Skills </h2>
                <ul> 
                    {{-- Blade foreach loop replaces PHP foreach loop --}}
                    @foreach ($skills as $skill)
                        <li>{{ htmlspecialchars($skill->skill_name) }}</li>
                    @endforeach
                </ul>
            </section>
            
            <hr>

            <section>
                <h2>Education</h2>
                <dl>
                    {{-- Blade foreach loop replaces PHP foreach loop --}}
                    @foreach ($education as $edu)
                        <dt>
                            {{ htmlspecialchars($edu->program) }} 
                            ({{ htmlspecialchars($edu->start_year) }} - {{ htmlspecialchars($edu->end_year) }})
                        </dt>
                        <dd>
                            {{ htmlspecialchars($edu->university) }}
                        </dd>
                    @endforeach
                </dl>
            </section>

            <hr>

        </main>
        <footer>
            <p>&copy; Eon Busque</p>
            <div class="footer-buttons">
                {{-- Conditional logic based on is_authenticated flag from the controller --}}
                @if ($is_authenticated)
                    <a href="{{ route('dashboard') }}" class="btn-back">Back to Dashboard</a>
                @else
                    <a href="{{ route('home') }}" class="btn-back">Back to Home</a>
                @endif
                <a href="{{ asset('assets/resume/Busque-Resume.pdf') }}" class="btn-download" download>Download Resume</a>
            </div>
        </footer>
    </div>
</body>
</html>