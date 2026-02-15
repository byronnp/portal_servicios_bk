<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AplicationUserRole extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminGlobal = User::where('email','bpilataxi@casabaca.com')->first();
        $appPortal = Application::where('slug','portal')->first();
        $roleAdmin = Role::where('name','Super Administrador')->first();
        $roleUsuario = Role::where('name','Usuario')->first();
        $asignaciones = [
            [
                'user_id' => $adminGlobal->id,
                'application_id' => $appPortal->id,
                'role_id' => $roleAdmin->id,
                'assigned_by' => $adminGlobal->id,
            ],
            [
                'user_id' => $adminGlobal->id,
                'application_id' => $appPortal->id,
                'role_id' => $roleUsuario->id,
                'assigned_by' => $adminGlobal->id,
            ]
        ];

        foreach ($asignaciones as $asignacion) {
            DB::table('application_user_role')->updateOrInsert(
                [
                    'user_id' => $asignacion['user_id'],
                    'application_id' => $asignacion['application_id'],
                    'role_id' => $asignacion['role_id'],
                ],
                [
                    'assigned_at' => Carbon::now(),
                    'assigned_by' => $asignacion['assigned_by'],
                    'is_active' => true,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
        }
    }
}
