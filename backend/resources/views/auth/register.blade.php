{{-- resources/views/auth/register.blade.php --}}

<x-guest-layout>
    {{-- This content is passed into the $slot variable of guest-layout --}}

    <div class="container">
        <h2>Account Creation</h2>
        <h3>Sign up to access the resume</h3>

        @if (session('status'))
            <div class="success-message">{{ session('status') }}</div>
        @endif
        
        @if ($errors->any())
            <div class="error-message">
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif
        
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <label>Username</label>
            <input type="text" name="username" required autofocus value="{{ old('username') }}">
            
            <label>Password</label>
            <input type="password" name="password" required>
            
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" required>
            
            <input type="submit" value="Register">
        </form>

        <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
        <p><a href="{{ route('home') }}">Back to Home</a></p>
    </div>
</x-guest-layout>