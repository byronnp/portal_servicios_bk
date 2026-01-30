<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agency extends Model
{
    use SoftDeletes, Auditable;

    protected $fillable = [
        'company_id',
        'crm_agency_id',
        'name',
        'description',
        's3s_id',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Get the company that owns the agency.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Scope a query to only include active agencies.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope a query to search by CRM ID.
     */
    public function scopeByCrmId($query, string $crmId)
    {
        return $query->where('crm_agency_id', $crmId);
    }
}
