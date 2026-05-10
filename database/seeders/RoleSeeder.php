<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $portalId = \App\Models\Application::where('slug', 'portal')->value('id');
        $omotenashiId = \App\Models\Application::where('slug', 'omotenashi')->value('id');

        $superAdmin = Role::updateOrCreate([
            'application_id' => $portalId,
            'slug' => 'super-admin',
        ], [
            'name' => 'Super Administrador',
            'description' => 'Acceso total al sistema',
            'is_active' => true,
        ]);
        $superAdmin->permissions()->sync(Permission::pluck('id'));

        $admin = Role::updateOrCreate([
            'application_id' => $portalId,
            'slug' => 'admin',
        ], [
            'name' => 'Administrador',
            'description' => 'Administrador del sistema',
            'is_active' => true,
        ]);
        $admin->permissions()->sync(
            Permission::whereIn('slug', [
                'auth.sessions.manage', 'auth.users.show', 'auth.users.update', 'auth.profile.update',
                'instances.index', 'instances.show', 'instances.store', 'instances.update', 'instances.destroy',
                'companies.index', 'companies.show', 'companies.store', 'companies.update', 'companies.destroy',
                'agencies.index', 'agencies.show', 'agencies.store', 'agencies.update', 'agencies.destroy',
                'applications.index', 'applications.show', 'applications.store', 'applications.update', 'applications.destroy',
                'roles.index','roles.show', 'roles.store', 'roles.update', 'roles.destroy',
                'catalog-types.index', 'catalog-types.store', 'catalog-types.update', 'catalog-types.destroy',
                'catalog-items.index', 'catalog-items.store', 'catalog-items.update', 'catalog-items.destroy',
                'menus.index', 'menus.store', 'menus.update', 'menus.destroy',
                'application-users.index', 'application-users.store', 'application-users.update', 'application-users.destroy',
                'permissions.index', 'permissions.store', 'permissions.update', 'permissions.destroy',
            ])->pluck('id')
        );

        $manager = Role::updateOrCreate([
            'application_id' => $portalId,
            'slug' => 'manager',
        ], [
            'name' => 'Gerente',
            'description' => 'Gerente con permisos de lectura y escritura',
            'is_active' => true,
        ]);
        $manager->permissions()->sync(
            Permission::whereIn('slug', [
                'instances.index', 'instances.show',
                'companies.index', 'companies.show', 'companies.store', 'companies.update',
                'agencies.index', 'agencies.show', 'agencies.store', 'agencies.update',
                'applications.index', 'applications.show', 'applications.store', 'applications.update',
                'catalog-types.index', 'catalog-items.index', 'menus.index',
            ])->pluck('id')
        );

        $user = Role::updateOrCreate([
            'application_id' => $portalId,
            'slug' => 'user',
        ], [
            'name' => 'Usuario',
            'description' => 'Usuario con permisos de solo lectura',
            'is_active' => true,
        ]);
        $user->permissions()->sync(
            Permission::whereIn('slug', [
                'instances.index', 'instances.show',
                'companies.index', 'companies.show',
                'agencies.index', 'agencies.show',
                'applications.index', 'applications.show',
                'catalog-types.index', 'catalog-items.index', 'menus.index',
            ])->pluck('id')
        );

        $omotenashiUser = Role::updateOrCreate([
            'application_id' => $omotenashiId,
            'slug' => 'user',
        ], [
            'name' => 'Usuario omotenashi',
            'description' => 'Usuario con permisos de solo lectura',
            'is_active' => true,
        ]);
        $omotenashiUser->permissions()->sync(
            Permission::whereIn('slug', [
                'omotenashi.index', 'omotenashi.show',
            ])->pluck('id')
        );
    }
}
