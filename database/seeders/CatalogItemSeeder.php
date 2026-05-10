<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $itemsByType = [
            'Tipos de documento' => [
                ['name' => 'Cédula', 's3s_code' => 'CED', 'crm_code' => 'CEDULA', 'sort_order' => 1],
                ['name' => 'RUC', 's3s_code' => 'RUC', 'crm_code' => 'RUC', 'sort_order' => 2],
                ['name' => 'Pasaporte', 's3s_code' => 'PAS', 'crm_code' => 'PASSPORT', 'sort_order' => 3],
            ],
            'Estados civiles' => [
                ['name' => 'Soltero', 's3s_code' => 'SOL', 'crm_code' => 'SINGLE', 'sort_order' => 1],
                ['name' => 'Casado', 's3s_code' => 'CAS', 'crm_code' => 'MARRIED', 'sort_order' => 2],
                ['name' => 'Divorciado', 's3s_code' => 'DIV', 'crm_code' => 'DIVORCED', 'sort_order' => 3],
            ],
            'Tipos de aplicación' => [
                ['name' => 'Web', 's3s_code' => 'WEB', 'crm_code' => 'WEB', 'icon' => 'monitor', 'sort_order' => 1],
                ['name' => 'Móvil', 's3s_code' => 'MOBILE', 'crm_code' => 'MOBILE', 'icon' => 'smartphone', 'sort_order' => 2],
            ],
        ];

        foreach ($itemsByType as $typeName => $items) {
            $typeId = DB::table('catalog_types')->where('name', $typeName)->value('id');

            if (!$typeId) {
                continue;
            }

            foreach ($items as $item) {
                DB::table('catalog_items')->updateOrInsert(
                    [
                        'catalog_type_id' => $typeId,
                        'name' => $item['name'],
                    ],
                    [
                        'description' => $item['description'] ?? null,
                        's3s_code' => $item['s3s_code'] ?? null,
                        'crm_code' => $item['crm_code'] ?? null,
                        'icon' => $item['icon'] ?? null,
                        'sort_order' => $item['sort_order'] ?? 0,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
