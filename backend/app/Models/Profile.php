<?php

// app/Models/Profile.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    /**
     * Indicates if the model should be timestamped.
     * This is set to false because the existing 'profiles' table lacks
     * 'created_at' and 'updated_at' columns.
     */
    public $timestamps = false; 

    protected $fillable = [
        'user_id',
        'fullname',       // Used in edit_resume.php
        'email',          // Used in edit_resume.php
        'phone',          // Used in edit_resume.php
        'location',       // Used in edit_resume.php
        'summary',        // Used in edit_resume.php
        'profile_picture',// Used in edit_resume.php
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}