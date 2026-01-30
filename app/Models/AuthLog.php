<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuthLog extends Model
{
    use Auditable;
    protected $fillable = [
        'user_id',
        'token',
        'ip_address',
        'user_agent',
        'login_at',
        'logout_at',
        'is_active',
        'logout_type',
    ];

    protected $casts = [
        'login_at' => 'datetime',
        'logout_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the auth log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include active sessions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Mark session as logged out.
     */
    public function markAsLoggedOut(string $logoutType = 'manual'): void
    {
        $this->update([
            'is_active' => false,
            'logout_at' => now(),
            'logout_type' => $logoutType,
        ]);
    }
}
