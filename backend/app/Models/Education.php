<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Education extends Model
{
    protected $table = 'educations'; 

    protected $fillable = [
        'user_id',
        'program',        
        'university',     
        'start_year',     
        'end_year',       
    ];

    //Get the user that owns the education record.
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
