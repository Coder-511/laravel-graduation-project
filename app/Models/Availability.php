<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Availability extends Model
{
    use HasFactory;

    protected $primaryKey = 'availability_id';

    protected $fillable = [
        'seeker_id',
        'available_date',
        'available_time',
    ];

    protected $casts = [
        'available_date' => 'date',
    ];

    // ── Relationships ──────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seeker_id');
    }
}