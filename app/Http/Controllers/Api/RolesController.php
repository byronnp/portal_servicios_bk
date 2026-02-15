<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Transformers\RoleTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

class RolesController extends Controller
{
    // Listar roles con sus permisos
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return $this->responder
            ->success($roles, [RoleTransformer::class, 'transform'])
            ->message('Role retrieved successfully')
            ->respond();
    }

    // Crear un nuevo rol y asignar permisos opcionalmente
    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:roles,name',
                'slug' => 'required|unique:roles,slug',
                'description' => 'required',
                'is_active' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return $this->responder
                    ->error($validator->errors()->first(), 422)
                    ->respond();
            }

            $role = Role::create(
                [
                    'name' => $request->name,
                    'slug' => $request->slug,
                    'description' => $request->description,
                    'is_active' => $request->is_active,
                ]
            );

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            return $this->responder
                ->success($role->load('permissions'),[RoleTransformer::class, 'transform'])
                ->message('Role created successfully')
                ->respond();
        }catch (Exception $e) {
            Log::error('Error creating rol: ' . $e->getMessage());
            return $this->responder
                ->error('Error creating rol', 500)
                ->respond();
        }

    }

    // Mostrar un rol específico
    public function show($id)
    {
        try {
            $role = Role::with('permissions')->findOrFail($id);

            return $this->responder
                ->success($role, [RoleTransformer::class, 'transform'])
                ->message('Role retrieved successfully')
                ->respond();
        }catch (ModelNotFoundException $e){
            return $this->responder
                ->error('El rol no pudo ser encontrado', 500)
                ->respond();
        }catch (Exception $e) {
            Log::error('Error show rol: ' . $e->getMessage());
            return $this->responder
                ->error('Error show rol', 500)
                ->respond();
        }
    }

    // Actualizar nombre y permisos
    public function update(Request $request, Role $role)
    {
        try {
            // 1. Verificar si el rol ya fue eliminado
            if ($role->trashed()) {
                return $this->responder
                    ->error('No se puede editar: el rol ya ha sido eliminado previamente.', 404)
                    ->respond();
            }

            $validator = Validator::make($request->all(), [
                'name'        => 'required|unique:roles,name,' . $role->id,
                'slug'        => 'required|unique:roles,slug,' . $role->id,
                'description' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->responder
                    ->error($validator->errors()->first(), 422)
                    ->respond();
            }

            // 2. Verificar si el rol existe o fue eliminado (Si no usas Route Model Binding)
            if (!$role || $role->trashed()) { // .trashed() solo si usas SoftDeletes
                return $this->responder
                    ->error('El rol no existe o ha sido eliminado.', 404)
                    ->respond();
            }

            $role->update(
                [
                    'name' => $request->name,
                    'slug' => $request->slug,
                    'description' => $request->description,
                ]
            );

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            return $this->responder
                ->success($role->load('permissions'),[RoleTransformer::class, 'transform'])
                ->message('Role update successfully')
                ->respond();
        }catch (Exception $e) {
            Log::error('Error update rol: ' . $e->getMessage());
            return $this->responder
                ->error('Error update rol', 500)
                ->respond();
        }
    }

    // Eliminar rol
    public function destroy(Role $role)
    {
        try {

            // 1. Verificar si el rol ya fue eliminado
            if (method_exists($role, 'trashed') && $role->trashed()) {
                return $this->responder
                    ->error('El rol ya se encuentra en la papelera o ha sido eliminado.', 404)
                    ->respond();
            }

            $role->delete();
            return $this->responder
                ->success('null')
                ->message('Role delete successfully')
                ->respond();
        }catch (Exception $e) {
            Log::error('Error al eliminar rol: ' . $e->getMessage());
            return $this->responder
                ->error('Error al eliminar rol', 500)
                ->respond();
        }
    }
}
