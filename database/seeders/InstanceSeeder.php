<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Instance;

class InstanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Instance::updateOrCreate(['url' => 'www.google.com'], [
            'name' => 'CRM_CASABACA',
            'description' => 'INSTANCIA DE CASABACA',
            'crm_token' => '91|bbbbbbbbbbbbbbbbbbb',
            'can_send_to_crm' => true,
            'status' => true,
        ]);
    }
}
