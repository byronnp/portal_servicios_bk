<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Agency;
use App\Models\Company;

class AgencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company1 = Company::where('ruc', '1791856125001')->first();
        $company2 = Company::where('ruc', '1791856125002')->first();

        // Agencias para CASA BACA S.A.
        Agency::updateOrCreate(['crm_agency_id' => 'CRM_AG_001'], [
            'company_id' => $company1->id,
            'name' => 'Agencia Quito Centro',
            'description' => 'Agencia principal en Quito Centro',
            's3s_id' => 'S3S_AG_001',
            'status' => true,
        ]);

        Agency::updateOrCreate(['crm_agency_id' => 'CRM_AG_002'], [
            'company_id' => $company1->id,
            'name' => 'Agencia Quito Sur',
            'description' => 'Agencia en el sur de Quito',
            's3s_id' => 'S3S_AG_002',
            'status' => true,
        ]);

        Agency::updateOrCreate(['crm_agency_id' => 'CRM_AG_003'], [
            'company_id' => $company1->id,
            'name' => 'Agencia Guayaquil',
            'description' => 'Agencia en Guayaquil',
            's3s_id' => 'S3S_AG_003',
            'status' => true,
        ]);

        // Agencias para CASA BACA NORTE
        Agency::updateOrCreate(['crm_agency_id' => 'CRM_AG_004'], [
            'company_id' => $company2->id,
            'name' => 'Agencia Quito Norte',
            'description' => 'Agencia en el norte de Quito',
            's3s_id' => 'S3S_AG_004',
            'status' => true,
        ]);

        Agency::updateOrCreate(['crm_agency_id' => 'CRM_AG_005'], [
            'company_id' => $company2->id,
            'name' => 'Agencia Cumbayá',
            'description' => 'Agencia en Cumbayá',
            's3s_id' => 'S3S_AG_005',
            'status' => true,
        ]);
    }
}
