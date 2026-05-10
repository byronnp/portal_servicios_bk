<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(['email' => 'bpilataxi@casabaca.com'], [
            'name' => 'BYRON VINICIO PILATAXI ALMACHI',
            'password' => Hash::make('Vinicio1987#'),
            'is_active' => true,
        ]);

        UserProfile::updateOrCreate(['user_id' => $user->id], [
            'user_id' => $user->id,
            'identification' => '1716128911',
            'first_name' => 'BYRON VINICIO',
            'last_name' => 'PILATAXI ALMACHI',
            'full_name' => 'BYRON VINICIO PILATAXI ALMACHI',
            'user_name' => 'BV_PILATAXI',
            'phone' => '0998765432',
            'crm_id' => 'CRM001',
            'position' => 1,
        ]);
    }
}
