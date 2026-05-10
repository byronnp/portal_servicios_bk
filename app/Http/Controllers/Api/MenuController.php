<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Transformers\MenuTransformer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    public function index()
    {
        try {
            $menus = Menu::with(['application', 'permission', 'children.permission'])
                ->whereNull('parent_id')
                ->orderBy('sort_order')
                ->get();

            return $this->responder
                ->success($menus, [MenuTransformer::class, 'transform'])
                ->message('Menus retrieved successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error retrieving menus: '.$e->getMessage());
            return $this->responder->error('Error retrieving menus', 500)->respond();
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'application_id' => 'required|exists:applications,id',
                'parent_id' => 'nullable|exists:menus,id',
                'permission_id' => 'nullable|exists:permissions,id',
                'name' => 'required|string|max:255',
                'label' => 'required|string|max:255',
                'route_name' => 'nullable|string|max:255',
                'path' => 'nullable|string|max:255',
                'external_url' => 'nullable|string',
                'icon' => 'nullable|string',
                'component' => 'nullable|string|max:255',
                'depth' => 'integer|min:0',
                'sort_order' => 'integer',
                'is_visible' => 'boolean',
                'is_active' => 'boolean',
                'opens_new_tab' => 'boolean',
                'metadata' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return $this->responder->error($validator->errors()->first(), 422)->respond();
            }

            $exists = Menu::where('application_id', $request->application_id)
                ->where('name', $request->name)
                ->exists();

            if ($exists) {
                return $this->responder->error('Menu name already exists for this application', 422)->respond();
            }

            $menu = Menu::create($request->only([
                'application_id',
                'parent_id',
                'permission_id',
                'name',
                'label',
                'route_name',
                'path',
                'external_url',
                'icon',
                'component',
                'depth',
                'sort_order',
                'is_visible',
                'is_active',
                'opens_new_tab',
                'metadata',
            ]));
            $menu->load(['application', 'permission', 'children']);

            return $this->responder
                ->success($menu, [MenuTransformer::class, 'transform'])
                ->message('Menu created successfully')
                ->statusCode(201)
                ->respond();
        } catch (Exception $e) {
            Log::error('Error creating menu: '.$e->getMessage());
            return $this->responder->error('Error creating menu', 500)->respond();
        }
    }

    public function show(string $id)
    {
        try {
            $menu = Menu::with(['application', 'permission', 'children.permission'])->find($id);

            if (!$menu) {
                return $this->responder->error('Menu not found', 404)->respond();
            }

            return $this->responder
                ->success($menu, [MenuTransformer::class, 'transform'])
                ->message('Menu retrieved successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error retrieving menu: '.$e->getMessage());
            return $this->responder->error('Error retrieving menu', 500)->respond();
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $menu = Menu::find($id);

            if (!$menu) {
                return $this->responder->error('Menu not found', 404)->respond();
            }

            $validator = Validator::make($request->all(), [
                'application_id' => 'exists:applications,id',
                'parent_id' => 'nullable|exists:menus,id',
                'permission_id' => 'nullable|exists:permissions,id',
                'name' => 'string|max:255',
                'label' => 'string|max:255',
                'route_name' => 'nullable|string|max:255',
                'path' => 'nullable|string|max:255',
                'external_url' => 'nullable|string',
                'icon' => 'nullable|string',
                'component' => 'nullable|string|max:255',
                'depth' => 'integer|min:0',
                'sort_order' => 'integer',
                'is_visible' => 'boolean',
                'is_active' => 'boolean',
                'opens_new_tab' => 'boolean',
                'metadata' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return $this->responder->error($validator->errors()->first(), 422)->respond();
            }

            if ((int) $request->parent_id === $menu->id) {
                return $this->responder->error('A menu cannot be its own parent', 422)->respond();
            }

            $applicationId = $request->input('application_id', $menu->application_id);
            $name = $request->input('name', $menu->name);
            $exists = Menu::where('application_id', $applicationId)
                ->where('name', $name)
                ->where('id', '!=', $menu->id)
                ->exists();

            if ($exists) {
                return $this->responder->error('Menu name already exists for this application', 422)->respond();
            }

            $menu->update($request->only([
                'application_id',
                'parent_id',
                'permission_id',
                'name',
                'label',
                'route_name',
                'path',
                'external_url',
                'icon',
                'component',
                'depth',
                'sort_order',
                'is_visible',
                'is_active',
                'opens_new_tab',
                'metadata',
            ]));
            $menu->load(['application', 'permission', 'children']);

            return $this->responder
                ->success($menu, [MenuTransformer::class, 'transform'])
                ->message('Menu updated successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error updating menu: '.$e->getMessage());
            return $this->responder->error('Error updating menu', 500)->respond();
        }
    }

    public function destroy(string $id)
    {
        try {
            $menu = Menu::find($id);

            if (!$menu) {
                return $this->responder->error('Menu not found', 404)->respond();
            }

            $menu->delete();

            return $this->responder
                ->success(null)
                ->message('Menu deleted successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error deleting menu: '.$e->getMessage());
            return $this->responder->error('Error deleting menu', 500)->respond();
        }
    }
}
