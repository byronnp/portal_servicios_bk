<?php

namespace App\Transformers;

use App\Models\Permission;

class PermissionsTransformer
{
public static function transform(Permission $permissions)
{
    return [
        'id' => $permissions->id,
        'name' => $permissions->name,
        'slug' => $permissions->slug,
        'description' => $permissions->description,
        'module' => $permissions->module,
        'is_active' => $permissions->is_active
    ];
}
}
