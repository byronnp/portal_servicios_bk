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
        // Create Super Admin role with all permissions
        $superAdmin = Role::create([
            'name' => 'Super Administrador',
            'slug' => 'super-admin',
            'description' => 'Acceso total al sistema',
            'application_id' => 1,
        ]);
        $superAdmin->permissions()->attach(Permission::all());

        // Create Admin role with most permissions
        $admin = Role::create([
            'name' => 'Administrador',
            'slug' => 'admin',
            'description' => 'Administrador del sistema',
            'application_id' => 1,
        ]);
        $admin->permissions()->attach(
            Permission::whereIn('slug', [
                'auth.sessions.manage', 'auth.users.show', 'auth.users.update', 'auth.profile.update',
                'instances.index', 'instances.show', 'instances.store', 'instances.update', 'instances.destroy',
                'companies.index', 'companies.show', 'companies.store', 'companies.update', 'companies.destroy',
                'agencies.index', 'agencies.show', 'agencies.store', 'agencies.update', 'agencies.destroy',
                'applications.index', 'applications.show', 'applications.store', 'applications.update', 'applications.destroy',
                'roles.index','roles.show', 'roles.store', 'roles.update', 'roles.destroy',
            ])->get()
        );

        // Create Manager role
        $manager = Role::create([
            'name' => 'Gerente',
            'slug' => 'manager',
            'description' => 'Gerente con permisos de lectura y escritura',
            'application_id' => 1,
        ]);
        $manager->permissions()->attach(
            Permission::whereIn('slug', [
                'instances.index', 'instances.show',
                'companies.index', 'companies.show', 'companies.store', 'companies.update',
                'agencies.index', 'agencies.show', 'agencies.store', 'agencies.update',
                'applications.index', 'applications.show', 'applications.store', 'applications.update',
            ])->get()
        );

        // Create User role (read only)
        $user = Role::create([
            'name' => 'Usuario',
            'slug' => 'user',
            'description' => 'Usuario con permisos de solo lectura',
            'application_id' => 1,
        ]);
        $user->permissions()->attach(
            Permission::whereIn('slug', [
                'instances.index', 'instances.show',
                'companies.index', 'companies.show',
                'agencies.index', 'agencies.show',
                'applications.index', 'applications.show',
            ])->get()
        );

        // Create User role (read only)
        $user = Role::create([
            'name' => 'Usuario omotenashi',
            'slug' => 'user',
            'description' => 'Usuario con permisos de solo lectura',
            'application_id' => 2,
        ]);
        $user->permissions()->attach(
            Permission::whereIn('slug', [
                'instances.index', 'instances.show',
                'companies.index', 'companies.show',
                'agencies.index', 'agencies.show',
                'applications.index', 'applications.show',
            ])->get()
        );
    }
}
