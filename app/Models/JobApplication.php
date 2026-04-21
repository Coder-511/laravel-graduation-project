<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $table = 'job_applications';
    protected $primaryKey = 'application_id';

    protected $fillable = [
        'job_id',
        'seeker_id',
        'status',
        'match_score',
        'message',
    ];

    protected $casts = [
        'match_score' => 'decimal:2',
    ];

    // ─── Relationships ───────────────────────────────────────────

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id', 'job_id');
    }

    public function seeker()
    {
        return $this->belongsTo(User::class, 'seeker_id', 'id');
    }

    // ─── Scopes ──────────────────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'Accepted');
    }
}