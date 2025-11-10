{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        {{-- MODIFIED: Use a specific, wider max-width (e.g., max-w-2xl) and adjusted padding --}}
        <div class="max-w-lg mx-auto p-4 sm:p-6 lg:p-8"> 
            
            {{-- REMOVED: bg-white to use the dark background of the app layout --}}
            <div class="overflow-hidden"> 
                <div class="p-0 text-gray-900 dark:text-gray-100">
                    
                    {{-- START: Custom Dashboard Content (Your original container) --}}
                    
                    <div class="dashboard-container">
                        <h1>Welcome, {{ htmlspecialchars(Auth::user()->username) }}!</h1>
                        <h2>Choose one of the actions below</h2>

                        <div class="dashboard-actions">
                            <a href="{{ route('resume.edit') }}" class="btn-dashboard btn-edit">Edit Resume</a>
                            <a href="{{ route('resume.private') }}" class="btn-dashboard btn-view">View Resume</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn-dashboard-logout">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- END: Custom Dashboard Content --}}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>