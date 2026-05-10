<?php

namespace App\Transformers;

use App\Models\CatalogItem;

class CatalogItemTransformer
{
    public static function transform(CatalogItem $catalogItem): array
    {
        $data = [
            'id' => $catalogItem->id,
            'catalog_type_id' => $catalogItem->catalog_type_id,
            'name' => $catalogItem->name,
            'description' => $catalogItem->description,
            's3s_code' => $catalogItem->s3s_code,
            'crm_code' => $catalogItem->crm_code,
            'icon' => $catalogItem->icon,
            'sort_order' => $catalogItem->sort_order,
            'is_active' => $catalogItem->is_active,
            'created_at' => $catalogItem->created_at?->toISOString(),
            'updated_at' => $catalogItem->updated_at?->toISOString(),
            'deleted_at' => $catalogItem->deleted_at?->toISOString(),
        ];

        if ($catalogItem->relationLoaded('type') && $catalogItem->type) {
            $data['type'] = [
                'id' => $catalogItem->type->id,
                'name' => $catalogItem->type->name,
            ];
        }

        return $data;
    }

    public static function collection($catalogItems): array
    {
        return $catalogItems->map(fn ($catalogItem) => self::transform($catalogItem))->toArray();
    }
}
