<?php

namespace App\Transformers;

use App\Models\Instance;

class InstanceTransformer
{
    public static function transform(Instance $instance): array
    {
        $data = [
            'id' => $instance->id,
            'name' => $instance->name,
            'description' => $instance->description,
            'url' => $instance->url,
            'can_send_to_crm' => $instance->can_send_to_crm,
            'status' => $instance->status,
            'created_at' => $instance->created_at?->toISOString(),
            'updated_at' => $instance->updated_at?->toISOString(),
        ];

        if ($instance->relationLoaded('companies') && $instance->companies) {
            $data['companies'] = $instance->companies->map(function ($company) {
                return CompanyTransformer::transform($company);
            })->toArray();
        }

        return $data;
    }
}
