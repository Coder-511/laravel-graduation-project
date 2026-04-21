<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobSeekerProfile extends Model
{
    use HasFactory;

    protected $table      = 'job_seeker_profiles';
    protected $primaryKey = 'seeker_id';

    public $incrementing = false;
    public $timestamps   = false;

    protected $fillable = [
        'seeker_id',
        'city',
    ];

    // ── Relationships ──────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seeker_id');
    }
}