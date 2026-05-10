<?php

namespace App\Transformers;

use App\Models\ApplicationUser;

class ApplicationUserTransformer
{
    public static function transform(ApplicationUser $applicationUser): array
    {
        $data = [
            'id' => $applicationUser->id,
            'user_id' => $applicationUser->user_id,
            'application_id' => $applicationUser->application_id,
            'assigned_at' => $applicationUser->assigned_at?->toISOString(),
            'assigned_by' => $applicationUser->assigned_by,
            'is_active' => $applicationUser->is_active,
            'created_at' => $applicationUser->created_at?->toISOString(),
            'updated_at' => $applicationUser->updated_at?->toISOString(),
        ];

        if ($applicationUser->relationLoaded('user') && $applicationUser->user) {
            $data['user'] = UserTransformer::transform($applicationUser->user);
        }

        if ($applicationUser->relationLoaded('application') && $applicationUser->application) {
            $data['application'] = ApplicationTransformer::transform($applicationUser->application);
        }

        if ($applicationUser->relationLoaded('assignedBy') && $applicationUser->assignedBy) {
            $data['assigned_by_user'] = UserTransformer::transform($applicationUser->assignedBy);
        }

        return $data;
    }

    public static function collection($applicationUsers): array
    {
        return $applicationUsers->map(fn ($applicationUser) => self::transform($applicationUser))->toArray();
    }
}
