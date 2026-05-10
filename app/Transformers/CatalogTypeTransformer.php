<?php

namespace App\Transformers;

use App\Models\CatalogType;

class CatalogTypeTransformer
{
    public static function transform(CatalogType $catalogType): array
    {
        $data = [
            'id' => $catalogType->id,
            'name' => $catalogType->name,
            'description' => $catalogType->description,
            'is_active' => $catalogType->is_active,
            'created_at' => $catalogType->created_at?->toISOString(),
            'updated_at' => $catalogType->updated_at?->toISOString(),
            'deleted_at' => $catalogType->deleted_at?->toISOString(),
        ];

        if ($catalogType->relationLoaded('items')) {
            $data['items'] = CatalogItemTransformer::collection($catalogType->items);
        }

        return $data;
    }

    public static function collection($catalogTypes): array
    {
        return $catalogTypes->map(fn ($catalogType) => self::transform($catalogType))->toArray();
    }
}
