<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleUserSeeder extends Seeder
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

        $roles = Role::whereIn('slug', ['super-admin', 'user'])->get();

        foreach ($roles as $role) {
            DB::table('role_user')->updateOrInsert(
                [
                    'role_id' => $role->id,
                    'user_id' => $user->id,
                ],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
