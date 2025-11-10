<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResumeController; // <-- ADD THIS LINE
use App\Http\Controllers\ResumeEditorController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// This route uses the HomeController to check authentication and direct the user.
Route::get('/', [HomeController::class, 'index'])->name('home');
// 1. HOME PAGE ROUTE (Replaces original index.php logic)
/*Route::get('/', function () {
    // We assume 'welcome' will be replaced by your desired home view
    return view('welcome'); 
})->name('home');
*/

// 2. DASHBOARD ROUTE (Standard Breeze route, kept)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 3. PUBLIC RESUME VIEW (Replaces public/resume.php access for unauthenticated users)
// Linked from the 'Back to Home' button/link
Route::get('/resume', [ResumeController::class, 'showPublic'])->name('resume.public');

// 4. AUTHENTICATED ROUTES
Route::middleware('auth')->group(function () {
    // Standard Breeze Profile routes (kept)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // PRIVATE RESUME VIEW (Linked from the dashboard)
    Route::get('/dashboard/resume', [ResumeController::class, 'showPrivate'])->name('resume.private');
});

// Route to display the edit form (GET request)
Route::get('/edit-resume', [ResumeEditorController::class, 'edit'])->name('resume.edit');

// Route to process the form submission (POST request)
Route::post('/edit-resume', [ResumeEditorController::class, 'update'])->name('resume.update');

// Inside the Route::middleware('auth')->group(function () { ... }); block:
Route::get('/setup-my-data', [ResumeEditorController::class, 'seedData'])->name('data.setup');

require __DIR__.'/auth.php';