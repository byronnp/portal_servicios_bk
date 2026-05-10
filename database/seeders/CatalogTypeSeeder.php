<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Tipos de documento', 'description' => 'Catálogo de identificaciones personales y tributarias'],
            ['name' => 'Estados civiles', 'description' => 'Catálogo de estados civiles'],
            ['name' => 'Tipos de aplicación', 'description' => 'Clasificación de aplicaciones por plataforma'],
        ];

        foreach ($types as $type) {
            DB::table('catalog_types')->updateOrInsert(
                ['name' => $type['name']],
                [
                    'description' => $type['description'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
