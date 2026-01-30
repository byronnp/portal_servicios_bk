<?php

namespace App\Transformers;

use App\Models\Company;

class CompanyTransformer
{
    public static function transform(Company $company): array
    {
        $data = [
            'id' => $company->id,
            'instance_id' => $company->instance_id,
            'crm_company_id' => $company->crm_company_id,
            's3s_id' => $company->s3s_id,
            'ruc' => $company->ruc,
            'name' => $company->name,
            'description' => $company->description,
            'status' => $company->status,
            'created_at' => $company->created_at?->toISOString(),
            'updated_at' => $company->updated_at?->toISOString(),
        ];

        if ($company->relationLoaded('instance') && $company->instance) {
            $data['instance'] = [
                'id' => $company->instance->id,
                'name' => $company->instance->name,
                'description' => $company->instance->description,
                'url' => $company->instance->url,
                'can_send_to_crm' => $company->instance->can_send_to_crm,
                'status' => $company->instance->status,
            ];
        }

        if ($company->relationLoaded('agencies') && $company->agencies) {
            $data['agencies'] = $company->agencies->map(function ($agency) {
                return [
                    'id' => $agency->id,
                    'name' => $agency->name,
                    'crm_agency_id' => $agency->crm_agency_id,
                    's3s_id' => $agency->s3s_id,
                    'status' => $agency->status,
                ];
            })->toArray();
        }

        return $data;
    }
}
