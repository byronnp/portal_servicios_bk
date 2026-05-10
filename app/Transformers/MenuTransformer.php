<?php

namespace App\Transformers;

use App\Models\Menu;

class MenuTransformer
{
    public static function transform(Menu $menu): array
    {
        $data = [
            'id' => $menu->id,
            'application_id' => $menu->application_id,
            'parent_id' => $menu->parent_id,
            'permission_id' => $menu->permission_id,
            'name' => $menu->name,
            'label' => $menu->label,
            'route_name' => $menu->route_name,
            'path' => $menu->path,
            'external_url' => $menu->external_url,
            'icon' => $menu->icon,
            'component' => $menu->component,
            'depth' => $menu->depth,
            'sort_order' => $menu->sort_order,
            'is_visible' => $menu->is_visible,
            'is_active' => $menu->is_active,
            'opens_new_tab' => $menu->opens_new_tab,
            'metadata' => $menu->metadata,
            'created_at' => $menu->created_at?->toISOString(),
            'updated_at' => $menu->updated_at?->toISOString(),
            'deleted_at' => $menu->deleted_at?->toISOString(),
        ];

        if ($menu->relationLoaded('application') && $menu->application) {
            $data['application'] = [
                'id' => $menu->application->id,
                'name' => $menu->application->name,
                'slug' => $menu->application->slug,
            ];
        }

        if ($menu->relationLoaded('permission') && $menu->permission) {
            $data['permission'] = PermissionsTransformer::transform($menu->permission);
        }

        if ($menu->relationLoaded('children')) {
            $data['children'] = self::collection($menu->children);
        }

        return $data;
    }

    public static function collection($menus): array
    {
        return $menus->map(fn ($menu) => self::transform($menu))->toArray();
    }
}
