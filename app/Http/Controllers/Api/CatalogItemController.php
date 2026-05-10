<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CatalogItem;
use App\Transformers\CatalogItemTransformer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CatalogItemController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = CatalogItem::with('type')->ordered();

            if ($request->filled('catalog_type_id')) {
                $query->where('catalog_type_id', $request->catalog_type_id);
            }

            $catalogItems = $query->get();

            return $this->responder
                ->success($catalogItems, [CatalogItemTransformer::class, 'transform'])
                ->message('Catalog items retrieved successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error retrieving catalog items: '.$e->getMessage());
            return $this->responder->error('Error retrieving catalog items', 500)->respond();
        }
    }

    public function byCatalogType(string $catalogTypeId)
    {
        try {
            $catalogItems = CatalogItem::with('type')
                ->where('catalog_type_id', $catalogTypeId)
                ->ordered()
                ->get();

            return $this->responder
                ->success($catalogItems, [CatalogItemTransformer::class, 'transform'])
                ->message('Catalog items retrieved successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error retrieving catalog items by type: '.$e->getMessage());
            return $this->responder->error('Error retrieving catalog items', 500)->respond();
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'catalog_type_id' => 'required|exists:catalog_types,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                's3s_code' => 'nullable|string|max:255',
                'crm_code' => 'nullable|string|max:255',
                'icon' => 'nullable|string',
                'sort_order' => 'integer',
                'is_active' => 'boolean',
            ]);

            if ($validator->fails()) {
                return $this->responder->error($validator->errors()->first(), 422)->respond();
            }

            $catalogItem = CatalogItem::create($request->only([
                'catalog_type_id',
                'name',
                'description',
                's3s_code',
                'crm_code',
                'icon',
                'sort_order',
                'is_active',
            ]));
            $catalogItem->load('type');

            return $this->responder
                ->success($catalogItem, [CatalogItemTransformer::class, 'transform'])
                ->message('Catalog item created successfully')
                ->statusCode(201)
                ->respond();
        } catch (Exception $e) {
            Log::error('Error creating catalog item: '.$e->getMessage());
            return $this->responder->error('Error creating catalog item', 500)->respond();
        }
    }

    public function show(string $id)
    {
        try {
            $catalogItem = CatalogItem::with('type')->find($id);

            if (!$catalogItem) {
                return $this->responder->error('Catalog item not found', 404)->respond();
            }

            return $this->responder
                ->success($catalogItem, [CatalogItemTransformer::class, 'transform'])
                ->message('Catalog item retrieved successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error retrieving catalog item: '.$e->getMessage());
            return $this->responder->error('Error retrieving catalog item', 500)->respond();
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $catalogItem = CatalogItem::find($id);

            if (!$catalogItem) {
                return $this->responder->error('Catalog item not found', 404)->respond();
            }

            $validator = Validator::make($request->all(), [
                'catalog_type_id' => 'exists:catalog_types,id',
                'name' => 'string|max:255',
                'description' => 'nullable|string',
                's3s_code' => 'nullable|string|max:255',
                'crm_code' => 'nullable|string|max:255',
                'icon' => 'nullable|string',
                'sort_order' => 'integer',
                'is_active' => 'boolean',
            ]);

            if ($validator->fails()) {
                return $this->responder->error($validator->errors()->first(), 422)->respond();
            }

            $catalogItem->update($request->only([
                'catalog_type_id',
                'name',
                'description',
                's3s_code',
                'crm_code',
                'icon',
                'sort_order',
                'is_active',
            ]));
            $catalogItem->load('type');

            return $this->responder
                ->success($catalogItem, [CatalogItemTransformer::class, 'transform'])
                ->message('Catalog item updated successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error updating catalog item: '.$e->getMessage());
            return $this->responder->error('Error updating catalog item', 500)->respond();
        }
    }

    public function destroy(string $id)
    {
        try {
            $catalogItem = CatalogItem::find($id);

            if (!$catalogItem) {
                return $this->responder->error('Catalog item not found', 404)->respond();
            }

            $catalogItem->delete();

            return $this->responder
                ->success(null)
                ->message('Catalog item deleted successfully')
                ->respond();
        } catch (Exception $e) {
            Log::error('Error deleting catalog item: '.$e->getMessage());
            return $this->responder->error('Error deleting catalog item', 500)->respond();
        }
    }
}
