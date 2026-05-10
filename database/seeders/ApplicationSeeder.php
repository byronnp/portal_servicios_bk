<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Application::updateOrCreate(['slug' => 'portal'], [
            'name' => 'Portal de Servicios',
            'description' => 'Portal principal de gestión de servicios empresariales',
            'is_web' => true,
            'is_mobile' => false,
            'start_url' => 'https://portal.example.com',
            'icon' => '/icons/portal.png',
            'is_active' => true,
        ]);

        Application::updateOrCreate(['slug' => 'omotenashi'], [
            'name' => 'Omotenashi',
            'description' => 'Aplicación móvil para clientes y seguimiento de servicios',
            'is_web' => false,
            'is_mobile' => true,
            'start_url' => 'myapp://home',
            'icon' => '/icons/mobile-app.png',
            'is_active' => true,
        ]);

        Application::updateOrCreate(['slug' => 'avaluos'], [
            'name' => 'Avaluos',
            'description' => 'Aplicación para registro de avaluos Avaluos',
            'is_web' => true,
            'is_mobile' => true,
            'start_url' => 'https://analytics.example.com',
            'icon' => '/icons/analytics.png',
            'is_active' => true,
        ]);
    }
}
