<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\HasCreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Application extends Model
{
    use SoftDeletes, Auditable, HasCreatedUpdatedBy;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'hash',
        'token',
        'is_web',
        'is_mobile',
        'start_url',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_web' => 'boolean',
        'is_mobile' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Generar automáticamente slug, hash y token al crear
        static::creating(function ($application) {
            if (empty($application->slug)) {
                $application->slug = Str::slug($application->name);
            }

            if (empty($application->hash)) {
                $application->hash = Str::uuid()->toString();
            }

            if (empty($application->token)) {
                $application->token = Str::random(64);
            }
        });
    }

    /**
     * The users that belong to the application.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'application_user')
            ->withPivot('assigned_at', 'assigned_by', 'is_active')
            ->withTimestamps();
    }

    /**
     * Get active users for this application.
     */
    public function activeUsers(): BelongsToMany
    {
        return $this->users()->wherePivot('is_active', true);
    }

    /**
     * Scope a query to only include active applications.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter web applications.
     */
    public function scopeWeb($query)
    {
        return $query->where('is_web', true);
    }

    /**
     * Scope a query to filter mobile applications.
     */
    public function scopeMobile($query)
    {
        return $query->where('is_mobile', true);
    }

    /**
     * Scope a query to filter applications available on both platforms.
     */
    public function scopeMultiPlatform($query)
    {
        return $query->where('is_web', true)->where('is_mobile', true);
    }

    /**
     * Check if application is available on web.
     */
    public function isWeb(): bool
    {
        return $this->is_web;
    }

    /**
     * Check if application is available on mobile.
     */
    public function isMobile(): bool
    {
        return $this->is_mobile;
    }

    /**
     * Check if application is multi-platform.
     */
    public function isMultiPlatform(): bool
    {
        return $this->is_web && $this->is_mobile;
    }

    /**
     * Regenerate application token.
     */
    public function regenerateToken(): string
    {
        $this->token = Str::random(64);
        $this->save();
        return $this->token;
    }
}
