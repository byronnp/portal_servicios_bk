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

            // Catalog permissions
            ['name' => 'Listar tipos de catálogo', 'slug' => 'catalog-types.index', 'description' => 'Ver listado de tipos de catálogo', 'module' => 'catalog'],
            ['name' => 'Crear tipo de catálogo', 'slug' => 'catalog-types.store', 'description' => 'Crear tipo de catálogo', 'module' => 'catalog'],
            ['name' => 'Actualizar tipo de catálogo', 'slug' => 'catalog-types.update', 'description' => 'Actualizar tipo de catálogo', 'module' => 'catalog'],
            ['name' => 'Eliminar tipo de catálogo', 'slug' => 'catalog-types.destroy', 'description' => 'Eliminar tipo de catálogo', 'module' => 'catalog'],
            ['name' => 'Listar ítems de catálogo', 'slug' => 'catalog-items.index', 'description' => 'Ver listado de ítems de catálogo', 'module' => 'catalog'],
            ['name' => 'Crear ítem de catálogo', 'slug' => 'catalog-items.store', 'description' => 'Crear ítem de catálogo', 'module' => 'catalog'],
            ['name' => 'Actualizar ítem de catálogo', 'slug' => 'catalog-items.update', 'description' => 'Actualizar ítem de catálogo', 'module' => 'catalog'],
            ['name' => 'Eliminar ítem de catálogo', 'slug' => 'catalog-items.destroy', 'description' => 'Eliminar ítem de catálogo', 'module' => 'catalog'],

            // Menu permissions
            ['name' => 'Listar menús', 'slug' => 'menus.index', 'description' => 'Ver estructura de menús', 'module' => 'menu'],
            ['name' => 'Crear menú', 'slug' => 'menus.store', 'description' => 'Crear menú', 'module' => 'menu'],
            ['name' => 'Actualizar menú', 'slug' => 'menus.update', 'description' => 'Actualizar menú', 'module' => 'menu'],
            ['name' => 'Eliminar menú', 'slug' => 'menus.destroy', 'description' => 'Eliminar menú', 'module' => 'menu'],

            // Application user assignment permissions
            ['name' => 'Listar asignaciones usuario aplicación', 'slug' => 'application-users.index', 'description' => 'Ver asignaciones de aplicaciones a usuarios', 'module' => 'application-user'],
            ['name' => 'Crear asignación usuario aplicación', 'slug' => 'application-users.store', 'description' => 'Asignar aplicación a usuario', 'module' => 'application-user'],
            ['name' => 'Actualizar asignación usuario aplicación', 'slug' => 'application-users.update', 'description' => 'Actualizar asignación de aplicación a usuario', 'module' => 'application-user'],
            ['name' => 'Eliminar asignación usuario aplicación', 'slug' => 'application-users.destroy', 'description' => 'Eliminar asignación de aplicación a usuario', 'module' => 'application-user'],

            // Permission management permissions
            ['name' => 'Listar permisos', 'slug' => 'permissions.index', 'description' => 'Ver listado de permisos', 'module' => 'permission'],
            ['name' => 'Crear permiso', 'slug' => 'permissions.store', 'description' => 'Crear permiso', 'module' => 'permission'],
            ['name' => 'Actualizar permiso', 'slug' => 'permissions.update', 'description' => 'Actualizar permiso', 'module' => 'permission'],
            ['name' => 'Eliminar permiso', 'slug' => 'permissions.destroy', 'description' => 'Eliminar permiso', 'module' => 'permission'],

            //role  permissions admin Omotenashi
            ['name' => 'Listar usuarios Omotenashi', 'slug' => 'omotenashi.index', 'description' => 'Ver listado de usuarios', 'module' => 'user'],
            ['name' => 'Ver usuario Omotenashi', 'slug' => 'omotenashi.show', 'description' => 'Ver detalle de usuario', 'module' => 'user'],
            ['name' => 'Crear usuario Omotenashi', 'slug' => 'omotenashi.store', 'description' => 'Crear nuevo usuario', 'module' => 'user'],
            ['name' => 'Actualizar usuario Omotenashi', 'slug' => 'omotenashi.update', 'description' => 'Actualizar usuario existente', 'module' => 'user'],
            ['name' => 'Eliminar usuario Omotenashi', 'slug' => 'omotenashi.destroy', 'description' => 'Eliminar usuario', 'module' => 'user'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }
    }
}
