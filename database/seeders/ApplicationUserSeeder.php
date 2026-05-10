<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'bpilataxi@casabaca.com')->first();

        if (!$user) {
            return;
        }

        foreach (['portal', 'omotenashi'] as $slug) {
            $application = Application::where('slug', $slug)->first();

            if (!$application) {
                continue;
            }

            DB::table('application_user')->updateOrInsert(
                [
                    'user_id' => $user->id,
                    'application_id' => $application->id,
                ],
                [
                    'assigned_at' => now(),
                    'assigned_by' => $user->id,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
