<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\HasCreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instance extends Model
{
    use SoftDeletes, Auditable, HasCreatedUpdatedBy;

    protected $fillable = [
        'name',
        'description',
        'url',
        'crm_token',
        'can_send_to_crm',
        'status',
    ];

    protected $casts = [
        'can_send_to_crm' => 'boolean',
        'status' => 'boolean',
    ];

    /**
     * Get the companies for the instance.
     */
    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }

    /**
     * Scope a query to only include active instances.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
