<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    use Auditable;

    protected $fillable = [
        'user_id',
        'identification',
        'first_name',
        'last_name',
        'full_name',
        'user_name',
        'phone',
        'crm_id',
        'position',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Generar automáticamente el nombre completo
        static::saving(function ($profile) {
            if ($profile->first_name && $profile->last_name) {
                $profile->full_name = $profile->first_name . ' ' . $profile->last_name;
            }
        });
    }

    /**
     * Get the user that owns the profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to search by user_name.
     */
    public function scopeByUserName($query, string $userName)
    {
        return $query->where('user_name', $userName);
    }

    /**
     * Scope a query to search by crm_id.
     */
    public function scopeByCrmId($query, string $crmId)
    {
        return $query->where('crm_id', $crmId);
    }
}
