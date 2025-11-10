{{-- resources/views/auth/login.blade.php --}}

<x-guest-layout>
    {{-- This content is passed into the $slot variable of guest-layout --}}

    <div class="container">
        <h2>Welcome Back</h2>
        <h3>Sign in to view resume</h3>

        {{-- Session Status for Success --}}
        <x-auth-session-status class="mb-4" :status="session('status')" />
        
        {{-- Laravel Errors --}}
        @if ($errors->any())
            <div class="error-message">
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <label>Username</label>
            <input type="text" name="username" required autofocus value="{{ old('username') }}">
            
            <label>Password</label>
            <input type="password" name="password" required>
            
            <input type="submit" value="Sign In">

            <p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
            <p><a href="{{ route('home') }}">Back to Home</a></p>
        </form>
    </div>
</x-guest-layout>