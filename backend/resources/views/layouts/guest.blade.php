<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Resume Manager') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('assets/css/loginStyles.css') }}">
    </head>
    
    {{-- The body classes are REMOVED to prevent Tailwind background conflict --}}
    <body class="font-sans antialiased">
        
        {{-- The outer container div is REMOVED to allow your custom .container inside the view to define the layout --}}

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg" style="display: none;">
            {{-- This component used to hold the Laravel logo; we are removing the visual clutter. --}}
        </div>

        {{-- This renders the custom content (login form, register form) we defined in auth/login.blade.php --}}
        <main>
            {{ $slot }}
        </main>
        
    </body>
</html>