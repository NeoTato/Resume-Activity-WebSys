{{-- resources/views/home.blade.php (Final version of index.php) --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Resume Manager</title>
    
    {{-- Link to custom CSS files --}}
    <link rel="stylesheet" href="{{ asset('assets/css/loginStyles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/indexStyles.css') }}">
</head>
<body>
    <div class="container">
        <h2>Resume Manager</h2>
        <h3>Choose an action below</h3>

        <div class="home-actions">
            @if (Auth::check())
                {{-- If user is logged in, show 'Go to Dashboard' --}}
                <a href="{{ route('dashboard') }}" class="btn-home btn-login-group">Go to Dashboard</a>
            @else
                {{-- If user is logged out, show 'Login / Sign Up' --}}
                <a href="{{ route('login') }}" class="btn-home btn-login-group">Login / Sign Up</a>
            @endif
            
            {{-- This link is always visible --}}
            <a href="{{ route('resume.public') }}" class="btn-home btn-view-public">View Public Resume</a>
        </div>
    </div>
</body>
</html>