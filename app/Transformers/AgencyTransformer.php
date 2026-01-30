<?php

namespace App\Transformers;

use App\Models\Agency;

class AgencyTransformer
{
    public static function transform(Agency $agency): array
    {
        return [
            'id' => $agency->id,
            'company_id' => $agency->company_id,
            'crm_agency_id' => $agency->crm_agency_id,
            'name' => $agency->name,
            'description' => $agency->description,
            's3s_id' => $agency->s3s_id,
            'status' => $agency->status,
            'company' => $agency->company ? CompanyTransformer::transform($agency->company) : null,
            'created_at' => $agency->created_at?->toISOString(),
            'updated_at' => $agency->updated_at?->toISOString(),
        ];
    }
}
