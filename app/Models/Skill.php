<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    use HasFactory;

    protected $table      = 'skills';
    protected $primaryKey = 'skill_id';

    public $timestamps = false;

    protected $fillable = [
        'skill_name',
    ];

    // ── Relationships ──────────────────────────────────────

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'seeker_skills',
            'skill_id',
            'seeker_id'
        );
    }

    public function jobs(): BelongsToMany
    {
        return $this->belongsToMany(
            Job::class,
            'job_skills',
            'skill_id',
            'job_id'
        );
    }
}