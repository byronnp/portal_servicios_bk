<?php

namespace App\Models;

use App\Traits\HasCreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationUserRole extends Model
{
    use SoftDeletes, HasCreatedUpdatedBy;

    protected $fillable = [
        'user_id',
        'application_id',
        'role_id',
        'assigned_at',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }

    /**
     * Relación con la Aplicación
     */
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Relación con el Rol
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
