<?php

// app/Models/Education.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Education extends Model
{
    protected $table = 'educations'; // Explicitly set table name if necessary

    protected $fillable = [
        'user_id',
        'program',        // Used in edit_resume.php
        'university',     // Used in edit_resume.php
        'start_year',     // Used in edit_resume.php
        'end_year',       // Used in edit_resume.php
    ];

    /**
     * Get the user that owns the education record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
