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
        // Crear aplicaciones
        $app1 = Application::create([
            'name' => 'Portal de Servicios',
            'description' => 'Portal principal de gestión de servicios empresariales',
            'is_web' => true,
            'is_mobile' => false,
            'start_url' => 'https://portal.example.com',
            'icon' => '/icons/portal.png',
            'is_active' => true,
        ]);

        $app2 = Application::create([
            'name' => 'App Móvil Clientes',
            'description' => 'Aplicación móvil para clientes y seguimiento de servicios',
            'is_web' => false,
            'is_mobile' => true,
            'start_url' => 'myapp://home',
            'icon' => '/icons/mobile-app.png',
            'is_active' => true,
        ]);

        $app3 = Application::create([
            'name' => 'Dashboard Analytics',
            'description' => 'Panel de análisis y reportes en tiempo real - Multi-plataforma',
            'is_web' => true,
            'is_mobile' => true,
            'start_url' => 'https://analytics.example.com',
            'icon' => '/icons/analytics.png',
            'is_active' => true,
        ]);

        // Obtener el usuario 1
        $user = User::find(1);

        if ($user) {
            // Asignar 2 de las 3 aplicaciones al usuario 1
            $user->applications()->attach($app1->id, [
                'assigned_at' => now(),
                'assigned_by' => 1, // Auto-asignado por el mismo usuario
                'is_active' => true,
            ]);

            $user->applications()->attach($app2->id, [
                'assigned_at' => now(),
                'assigned_by' => 1,
                'is_active' => true,
            ]);

            // app3 NO se asigna al usuario 1
        }
    }
}
