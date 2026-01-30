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
        ]);
        $superAdmin->permissions()->attach(Permission::all());

        // Create Admin role with most permissions
        $admin = Role::create([
            'name' => 'Administrador',
            'slug' => 'admin',
            'description' => 'Administrador del sistema',
        ]);
        $admin->permissions()->attach(
            Permission::whereIn('slug', [
                'auth.sessions.manage', 'auth.users.show', 'auth.users.update', 'auth.profile.update',
                'instances.index', 'instances.show', 'instances.store', 'instances.update', 'instances.destroy',
                'companies.index', 'companies.show', 'companies.store', 'companies.update', 'companies.destroy',
                'agencies.index', 'agencies.show', 'agencies.store', 'agencies.update', 'agencies.destroy',
            ])->get()
        );

        // Create Manager role
        $manager = Role::create([
            'name' => 'Gerente',
            'slug' => 'manager',
            'description' => 'Gerente con permisos de lectura y escritura',
        ]);
        $manager->permissions()->attach(
            Permission::whereIn('slug', [
                'instances.index', 'instances.show',
                'companies.index', 'companies.show', 'companies.store', 'companies.update',
                'agencies.index', 'agencies.show', 'agencies.store', 'agencies.update',
            ])->get()
        );

        // Create User role (read only)
        $user = Role::create([
            'name' => 'Usuario',
            'slug' => 'user',
            'description' => 'Usuario con permisos de solo lectura',
        ]);
        $user->permissions()->attach(
            Permission::whereIn('slug', [
                'instances.index', 'instances.show',
                'companies.index', 'companies.show',
                'agencies.index', 'agencies.show',
            ])->get()
        );
    }
}
