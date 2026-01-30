<?php

namespace App\Transformers;

use App\Models\UserProfile;

class UserProfileTransformer
{
    public static function transform(UserProfile $profile): array
    {
        return [
            'id' => $profile->id,
            'user_id' => $profile->user_id,
            'identification' => $profile->identification,
            'first_name' => $profile->first_name,
            'last_name' => $profile->last_name,
            'full_name' => $profile->full_name,
            'user_name' => $profile->user_name,
            'phone' => $profile->phone,
            'crm_id' => $profile->crm_id,
            'position' => $profile->position,
            'created_at' => $profile->created_at?->toISOString(),
            'updated_at' => $profile->updated_at?->toISOString(),
        ];
    }
}
