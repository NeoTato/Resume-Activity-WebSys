<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; 
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use \Exception; 

use App\Models\Profile; 
use App\Models\Skill; 
use App\Models\Education; 
use App\Models\Project; 

/**
 * @property-read Profile|null $profile
 * @property-read \Illuminate\Database\Eloquent\Collection|Skill[] $skills
 * @property-read \Illuminate\Database\Eloquent\Collection|Education[] $education
 * @property-read \Illuminate\Database\Eloquent\Collection|Project[] $projects
 * @method \Illuminate\Database\Eloquent\Builder|static load(string|array $relations)
 **/

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // Get the profile associated with the user (One-to-One).
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    // Get the skills for the user (One-to-Many).
    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class);
    }
    
    // Get the education records for the user (One-to-Many).
    public function education(): HasMany
    {
        return $this->hasMany(Education::class);
    }

    // Get the projects for the user (One-to-Many).
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}