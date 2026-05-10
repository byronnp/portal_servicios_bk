<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CatalogType;
use App\Transformers\CatalogTypeTransformer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CatalogTypeController extends Controller
{
    public function index()
    {
        try {
            $catalogTypes = CatalogType::with('items')->orderBy('name')->get();

            return $this->responder
                ->success($catalogTypes, [CatalogTypeTransformer::class, 'transform'])
                ->message('Catalog types retrieved successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error retrieving catalog types: '.$e->getMessage());
            return $this->responder->error('Error retrieving catalog types', 500)->respond();
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:catalog_types,name',
                'description' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            if ($validator->fails()) {
                return $this->responder->error($validator->errors()->first(), 422)->respond();
            }

            $catalogType = CatalogType::create($request->only(['name', 'description', 'is_active']));

            return $this->responder
                ->success($catalogType, [CatalogTypeTransformer::class, 'transform'])
                ->message('Catalog type created successfully')
                ->statusCode(201)
                ->respond();
        } catch (Exception $e) {
            Log::error('Error creating catalog type: '.$e->getMessage());
            return $this->responder->error('Error creating catalog type', 500)->respond();
        }
    }

    public function show(string $id)
    {
        try {
            $catalogType = CatalogType::with('items')->find($id);

            if (!$catalogType) {
                return $this->responder->error('Catalog type not found', 404)->respond();
            }

            return $this->responder
                ->success($catalogType, [CatalogTypeTransformer::class, 'transform'])
                ->message('Catalog type retrieved successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error retrieving catalog type: '.$e->getMessage());
            return $this->responder->error('Error retrieving catalog type', 500)->respond();
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $catalogType = CatalogType::find($id);

            if (!$catalogType) {
                return $this->responder->error('Catalog type not found', 404)->respond();
            }

            $validator = Validator::make($request->all(), [
                'name' => 'string|max:255|unique:catalog_types,name,'.$id,
                'description' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            if ($validator->fails()) {
                return $this->responder->error($validator->errors()->first(), 422)->respond();
            }

            $catalogType->update($request->only(['name', 'description', 'is_active']));

            return $this->responder
                ->success($catalogType, [CatalogTypeTransformer::class, 'transform'])
                ->message('Catalog type updated successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error updating catalog type: '.$e->getMessage());
            return $this->responder->error('Error updating catalog type', 500)->respond();
        }
    }

    public function destroy(string $id)
    {
        try {
            $catalogType = CatalogType::find($id);

            if (!$catalogType) {
                return $this->responder->error('Catalog type not found', 404)->respond();
            }

            $catalogType->delete();

            return $this->responder
                ->success(null)
                ->message('Catalog type deleted successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error deleting catalog type: '.$e->getMessage());
            return $this->responder->error('Error deleting catalog type', 500)->respond();
        }
    }
}
