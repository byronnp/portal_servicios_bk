<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Auth permissions
            ['name' => 'Gestionar sesiones', 'slug' => 'auth.sessions.manage', 'description' => 'Ver y gestionar sesiones activas', 'module' => 'auth'],
            ['name' => 'Ver usuario', 'slug' => 'auth.users.show', 'description' => 'Ver datos de cualquier usuario', 'module' => 'auth'],
            ['name' => 'Activar usuarios', 'slug' => 'auth.users.activate', 'description' => 'Activar usuarios', 'module' => 'auth'],
            ['name' => 'Desactivar usuarios', 'slug' => 'auth.users.deactivate', 'description' => 'Desactivar usuarios', 'module' => 'auth'],
            ['name' => 'Actualizar usuario', 'slug' => 'auth.users.update', 'description' => 'Actualizar datos del usuario', 'module' => 'auth'],
            ['name' => 'Actualizar perfil', 'slug' => 'auth.profile.update', 'description' => 'Actualizar perfil del usuario', 'module' => 'auth'],

            // Instance permissions
            ['name' => 'Listar instancias', 'slug' => 'instances.index', 'description' => 'Ver listado de instancias', 'module' => 'catalog'],
            ['name' => 'Ver instancia', 'slug' => 'instances.show', 'description' => 'Ver detalle de instancia', 'module' => 'catalog'],
            ['name' => 'Crear instancia', 'slug' => 'instances.store', 'description' => 'Crear nueva instancia', 'module' => 'catalog'],
            ['name' => 'Actualizar instancia', 'slug' => 'instances.update', 'description' => 'Actualizar instancia existente', 'module' => 'catalog'],
            ['name' => 'Eliminar instancia', 'slug' => 'instances.destroy', 'description' => 'Eliminar instancia', 'module' => 'catalog'],

            // Company permissions
            ['name' => 'Listar compañías', 'slug' => 'companies.index', 'description' => 'Ver listado de compañías', 'module' => 'catalog'],
            ['name' => 'Ver compañía', 'slug' => 'companies.show', 'description' => 'Ver detalle de compañía', 'module' => 'catalog'],
            ['name' => 'Crear compañía', 'slug' => 'companies.store', 'description' => 'Crear nueva compañía', 'module' => 'catalog'],
            ['name' => 'Actualizar compañía', 'slug' => 'companies.update', 'description' => 'Actualizar compañía existente', 'module' => 'catalog'],
            ['name' => 'Eliminar compañía', 'slug' => 'companies.destroy', 'description' => 'Eliminar compañía', 'module' => 'catalog'],

            // Agency permissions
            ['name' => 'Listar agencias', 'slug' => 'agencies.index', 'description' => 'Ver listado de agencias', 'module' => 'catalog'],
            ['name' => 'Ver agencia', 'slug' => 'agencies.show', 'description' => 'Ver detalle de agencia', 'module' => 'catalog'],
            ['name' => 'Crear agencia', 'slug' => 'agencies.store', 'description' => 'Crear nueva agencia', 'module' => 'catalog'],
            ['name' => 'Actualizar agencia', 'slug' => 'agencies.update', 'description' => 'Actualizar agencia existente', 'module' => 'catalog'],
            ['name' => 'Eliminar agencia', 'slug' => 'agencies.destroy', 'description' => 'Eliminar agencia', 'module' => 'catalog'],

            // Application permissions
            ['name' => 'Listar aplicaciones', 'slug' => 'applications.index', 'description' => 'Ver listado de aplicaciones', 'module' => 'catalog'],
            ['name' => 'Ver aplicación', 'slug' => 'applications.show', 'description' => 'Ver detalle de aplicación', 'module' => 'catalog'],
            ['name' => 'Crear aplicación', 'slug' => 'applications.store', 'description' => 'Crear nueva aplicación', 'module' => 'catalog'],
            ['name' => 'Actualizar aplicación', 'slug' => 'applications.update', 'description' => 'Actualizar aplicación existente', 'module' => 'catalog'],
            ['name' => 'Eliminar aplicación', 'slug' => 'applications.destroy', 'description' => 'Eliminar aplicación', 'module' => 'catalog'],

            //role  permissions
            ['name' => 'Ver listado de roles', 'slug' => 'roles.index', 'description' => 'Ver listado de roles', 'module' => 'role'],
            ['name' => 'Ver detalle de rol', 'slug' => 'roles.show', 'description' => 'Ver detalle de rol', 'module' => 'role'],
            ['name' => 'Crear rol', 'slug' => 'roles.store', 'description' => 'Crear nuevo rol', 'module' => 'role'],
            ['name' => 'Actualizar rol', 'slug' => 'roles.update', 'description' => 'Actualizar rol existente', 'module' => 'role'],
            ['name' => 'Eliminar rol', 'slug' => 'roles.destroy', 'description' => 'Eliminar rol', 'module' => 'role'],

            //role  permissions admin Omotenashi
            ['name' => 'Ver listado de usuarios', 'slug' => 'omotenashi.index', 'description' => 'Ver listado de usuarios', 'module' => 'user'],
            ['name' => 'Ver detalle de usuario', 'slug' => 'omotenashi.show', 'description' => 'Ver detalle de usuario', 'module' => 'user'],
            ['name' => 'Crear usuario', 'slug' => 'omotenashi.store', 'description' => 'Crear nuevo usuario', 'module' => 'user'],
            ['name' => 'Actualizar usuario', 'slug' => 'omotenashi.update', 'description' => 'Actualizar usuario existente', 'module' => 'user'],
            ['name' => 'Eliminar usuario', 'slug' => 'omotenashi.destroy', 'description' => 'Eliminar usuario', 'module' => 'user'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
