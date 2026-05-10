<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $portal = Application::where('slug', 'portal')->first();

        if (!$portal) {
            return;
        }

        $menus = [
            [
                'name' => 'dashboard',
                'label' => 'Dashboard',
                'path' => '/dashboard',
                'icon' => 'layout-dashboard',
                'sort_order' => 1,
            ],
            [
                'name' => 'security',
                'label' => 'Seguridad',
                'path' => '/security',
                'icon' => 'shield',
                'sort_order' => 2,
                'children' => [
                    ['name' => 'users', 'label' => 'Usuarios', 'path' => '/security/users', 'icon' => 'users', 'permission' => 'auth.users.show', 'sort_order' => 1],
                    ['name' => 'roles', 'label' => 'Roles', 'path' => '/security/roles', 'icon' => 'key-round', 'permission' => 'roles.index', 'sort_order' => 2],
                ],
            ],
            [
                'name' => 'catalogs',
                'label' => 'Catálogos',
                'path' => '/catalogs',
                'icon' => 'layers',
                'permission' => 'catalog-types.index',
                'sort_order' => 3,
                'children' => [
                    ['name' => 'catalog-types', 'label' => 'Tipos de catálogo', 'path' => '/catalogs/types', 'icon' => 'list-tree', 'permission' => 'catalog-types.index', 'sort_order' => 1],
                    ['name' => 'catalog-items', 'label' => 'Ítems de catálogo', 'path' => '/catalogs/items', 'icon' => 'list', 'permission' => 'catalog-items.index', 'sort_order' => 2],
                ],
            ],
            [
                'name' => 'applications',
                'label' => 'Aplicaciones',
                'path' => '/applications',
                'icon' => 'blocks',
                'permission' => 'applications.index',
                'sort_order' => 4,
            ],
            [
                'name' => 'menus',
                'label' => 'Menús',
                'path' => '/menus',
                'icon' => 'panel-top',
                'permission' => 'menus.index',
                'sort_order' => 5,
            ],
        ];

        foreach ($menus as $menu) {
            $parentId = $this->upsertMenu($portal->id, null, $menu, 0);

            foreach ($menu['children'] ?? [] as $child) {
                $this->upsertMenu($portal->id, $parentId, $child, 1);
            }
        }
    }

    private function upsertMenu(int $applicationId, ?int $parentId, array $menu, int $depth): int
    {
        $permissionId = isset($menu['permission'])
            ? Permission::where('slug', $menu['permission'])->value('id')
            : null;

        DB::table('menus')->updateOrInsert(
            [
                'application_id' => $applicationId,
                'name' => $menu['name'],
            ],
            [
                'parent_id' => $parentId,
                'permission_id' => $permissionId,
                'label' => $menu['label'],
                'route_name' => $menu['route_name'] ?? null,
                'path' => $menu['path'] ?? null,
                'external_url' => $menu['external_url'] ?? null,
                'icon' => $menu['icon'] ?? null,
                'component' => $menu['component'] ?? null,
                'depth' => $depth,
                'sort_order' => $menu['sort_order'] ?? 0,
                'is_visible' => true,
                'is_active' => true,
                'opens_new_tab' => $menu['opens_new_tab'] ?? false,
                'metadata' => isset($menu['metadata']) ? json_encode($menu['metadata']) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        return DB::table('menus')
            ->where('application_id', $applicationId)
            ->where('name', $menu['name'])
            ->value('id');
    }
}
