<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'application_id',
        'parent_id',
        'permission_id',
        'name',
        'label',
        'route_name',
        'path',
        'external_url',
        'icon',
        'component',
        'depth',
        'sort_order',
        'is_visible',
        'is_active',
        'opens_new_tab',
        'metadata',
    ];

    protected $casts = [
        'depth' => 'integer',
        'sort_order' => 'integer',
        'is_visible' => 'boolean',
        'is_active' => 'boolean',
        'opens_new_tab' => 'boolean',
        'metadata' => 'array',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('sort_order');
    }

    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }
}
