<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Transformers\PermissionsTransformer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    public function index()
    {
        try {
            $permissions = Permission::orderBy('module')->orderBy('slug')->get();

            return $this->responder
                ->success($permissions, [PermissionsTransformer::class, 'transform'])
                ->message('Permissions retrieved successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error retrieving permissions: '.$e->getMessage());
            return $this->responder->error('Error retrieving permissions', 500)->respond();
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:permissions,name',
                'slug' => 'required|string|max:255|unique:permissions,slug',
                'description' => 'nullable|string',
                'module' => 'nullable|string|max:255',
                'is_active' => 'boolean',
            ]);

            if ($validator->fails()) {
                return $this->responder->error($validator->errors()->first(), 422)->respond();
            }

            $permission = Permission::create($request->only([
                'name',
                'slug',
                'description',
                'module',
                'is_active',
            ]));

            return $this->responder
                ->success($permission, [PermissionsTransformer::class, 'transform'])
                ->message('Permission created successfully')
                ->statusCode(201)
                ->respond();
        } catch (Exception $e) {
            Log::error('Error creating permission: '.$e->getMessage());
            return $this->responder->error('Error creating permission', 500)->respond();
        }
    }

    public function show(string $id)
    {
        try {
            $permission = Permission::find($id);

            if (!$permission) {
                return $this->responder->error('Permission not found', 404)->respond();
            }

            return $this->responder
                ->success($permission, [PermissionsTransformer::class, 'transform'])
                ->message('Permission retrieved successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error retrieving permission: '.$e->getMessage());
            return $this->responder->error('Error retrieving permission', 500)->respond();
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $permission = Permission::find($id);

            if (!$permission) {
                return $this->responder->error('Permission not found', 404)->respond();
            }

            $validator = Validator::make($request->all(), [
                'name' => 'string|max:255|unique:permissions,name,'.$id,
                'slug' => 'string|max:255|unique:permissions,slug,'.$id,
                'description' => 'nullable|string',
                'module' => 'nullable|string|max:255',
                'is_active' => 'boolean',
            ]);

            if ($validator->fails()) {
                return $this->responder->error($validator->errors()->first(), 422)->respond();
            }

            $permission->update($request->only([
                'name',
                'slug',
                'description',
                'module',
                'is_active',
            ]));

            return $this->responder
                ->success($permission, [PermissionsTransformer::class, 'transform'])
                ->message('Permission updated successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error updating permission: '.$e->getMessage());
            return $this->responder->error('Error updating permission', 500)->respond();
        }
    }

    public function destroy(string $id)
    {
        try {
            $permission = Permission::find($id);

            if (!$permission) {
                return $this->responder->error('Permission not found', 404)->respond();
            }

            $permission->delete();

            return $this->responder
                ->success(null)
                ->message('Permission deleted successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error deleting permission: '.$e->getMessage());
            return $this->responder->error('Error deleting permission', 500)->respond();
        }
    }
}
