<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Instance;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $instance = Instance::first();

        Company::updateOrCreate(['ruc' => '1791856125001'], [
            'instance_id' => $instance->id,
            'crm_company_id' => 'CASABACA',
            's3s_id' => 'S3S_001',
            'name' => 'CASA BACA S.A.',
            'description' => 'Empresa principal Casa Baca',
            'status' => true,
        ]);

        Company::updateOrCreate(['ruc' => '1791856125002'], [
            'instance_id' => $instance->id,
            'crm_company_id' => '1001 CARROS',
            's3s_id' => 'S3S_002',
            'name' => 'CASA BACA NORTE',
            'description' => 'Empresa Casa Baca Norte',
            'status' => true,
        ]);
    }
}
