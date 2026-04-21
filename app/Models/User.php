<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'user_type',
        'profile_picture',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // ── Role helpers ───────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->user_type === 'Admin';
    }

    public function isJobOwner(): bool
    {
        return $this->user_type === 'JobOwner';
    }

    public function isJobSeeker(): bool
    {
        return $this->user_type === 'JobSeeker';
    }

    // ── Relationships ──────────────────────────────────────

    public function jobSeekerProfile(): HasOne
    {
        return $this->hasOne(JobSeekerProfile::class, 'seeker_id');
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'seeker_skills', 'seeker_id', 'skill_id');
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class, 'owner_id');
    }

    public function approvedJobs(): HasMany
    {
        return $this->hasMany(Job::class, 'approved_by');
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(Availability::class, 'seeker_id');
    }
}