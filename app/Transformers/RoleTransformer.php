<?php

namespace App\Transformers;

use App\Models\Role;

class RoleTransformer
{
    public static function transform(Role $role)
    {
        return [
            'id' => $role->id,
            'name' => $role->name,
            'slug' => $role->slug,
            'description' => $role->description,
            'is_active' => $role->is_active,
            'permisos' => $role->permissions->map(function ($permission) {
                return PermissionsTransformer::transform($permission);
            }),
        ];
    }
}
