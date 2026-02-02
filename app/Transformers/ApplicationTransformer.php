<?php

namespace App\Transformers;

use App\Models\Application;

class ApplicationTransformer
{
    public static function transform(Application $application): array
    {
        return [
            'id' => $application->id,
            'name' => $application->name,
            'slug' => $application->slug,
            'description' => $application->description,
            'hash' => $application->hash,
            'token' => $application->token,
            'is_web' => $application->is_web,
            'is_mobile' => $application->is_mobile,
            'start_url' => $application->start_url,
            'icon' => $application->icon,
            'is_active' => $application->is_active,
            'created_at' => $application->created_at?->toISOString(),
            'updated_at' => $application->updated_at?->toISOString(),
            'deleted_at' => $application->deleted_at?->toISOString(),
        ];
    }
}
