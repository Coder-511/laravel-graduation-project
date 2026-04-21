<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobShift extends Model
{
    use HasFactory;

    protected $table      = 'job_shifts';
    protected $primaryKey = 'shift_id';

    protected $fillable = [
        'job_id',
        'shift_date',
        'shift_start',
        'shift_end',
    ];

    protected $casts = [
        'shift_date' => 'date',
    ];

    // ── Relationships ──────────────────────────────────────

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'job_id', 'job_id');
    }
}