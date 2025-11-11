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
        'fullname',       
        'email',          
        'phone',          
        'location',       
        'summary',        
        'profile_picture',
    ];

    // Get the user that owns the profile.
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}