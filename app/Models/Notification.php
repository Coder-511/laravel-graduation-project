<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table      = 'notifications';
    protected $primaryKey = 'notification_id';
    public    $timestamps = false;         // we only have created_at, no updated_at

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read'    => 'boolean',
        'created_at' => 'datetime',
    ];

    // ─── Relationships ───────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // ─── Scopes ──────────────────────────────────────────────────

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    // ─── Helper: create a notification in one line ────────────────

    public static function notify(int $userId, string $title, string $message): self
    {
        return self::create([
            'user_id' => $userId,
            'title'   => $title,
            'message' => $message,
        ]);
    }
}