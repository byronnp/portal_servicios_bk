<?php

namespace App\Transformers;

use App\Models\User;

class UserTransformer
{
    public static function transform(User $user): array
    {
        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_active' => $user->is_active,
            'email_verified_at' => $user->email_verified_at?->toISOString(),
            'created_at' => $user->created_at?->toISOString(),
            'updated_at' => $user->updated_at?->toISOString(),
        ];

        if ($user->relationLoaded('profile') && $user->profile) {
            $data['profile'] = UserProfileTransformer::transform($user->profile);
        }

        return $data;
    }
}
