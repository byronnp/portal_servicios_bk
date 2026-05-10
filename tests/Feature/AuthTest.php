<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'identification' => '1000000001',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'first_name' => 'Test',
            'last_name' => 'User',
            'user_name' => 'test_user',
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => ['user', 'access_token', 'token_type', 'expires_in'],
                'message',
            ]);

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
        $this->assertDatabaseHas('user_profiles', ['user_name' => 'test_user']);
    }

    public function test_register_validates_required_profile_fields(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'email' => 'invalid',
            'password' => 'short',
        ]);

        $response->assertUnprocessable()
            ->assertJsonPath('success', false);
    }

    public function test_user_can_login_and_access_me_endpoint(): void
    {
        [$user] = $this->createAuthorizedUser(['auth.users.show']);

        $login = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $login->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['data' => ['access_token', 'token_type', 'expires_in']]);

        $token = $login->json('data.access_token');

        $this->withToken($token)
            ->getJson('/api/me')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.email', $user->email);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        [$user] = $this->createAuthorizedUser();

        $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])->assertUnauthorized()
            ->assertJsonPath('success', false);
    }

    public function test_protected_endpoint_requires_token(): void
    {
        $this->getJson('/api/me')
            ->assertUnauthorized()
            ->assertJsonPath('success', false);
    }

    public function test_user_can_refresh_and_logout(): void
    {
        [$user] = $this->createAuthorizedUser();
        $token = $this->loginToken($user);

        $refresh = $this->withToken($token)->postJson('/api/refresh');
        $refresh->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['data' => ['access_token', 'token_type', 'expires_in']]);

        $newToken = $refresh->json('data.access_token');

        $this->withToken($newToken)->postJson('/api/logout')
            ->assertOk()
            ->assertJsonPath('success', true);
    }

    private function createAuthorizedUser(array $permissionSlugs = []): array
    {
        $application = Application::create([
            'name' => 'Portal Test',
            'slug' => 'portal-test',
            'is_web' => true,
            'is_mobile' => false,
            'is_active' => true,
        ]);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        UserProfile::create([
            'user_id' => $user->id,
            'identification' => '1000000001',
            'first_name' => 'Test',
            'last_name' => 'User',
            'user_name' => 'test_user',
        ]);

        DB::table('application_user')->insert([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'assigned_at' => now(),
            'assigned_by' => $user->id,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $role = Role::create([
            'application_id' => $application->id,
            'name' => 'Admin Test',
            'slug' => 'admin-test',
            'description' => 'Test role',
            'is_active' => true,
        ]);

        DB::table('role_user')->insert([
            'role_id' => $role->id,
            'user_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($permissionSlugs as $slug) {
            $permission = Permission::create([
                'name' => $slug,
                'slug' => $slug,
                'module' => 'test',
                'is_active' => true,
            ]);

            $role->permissions()->attach($permission->id);
        }

        return [$user, $application, $role];
    }

    private function loginToken(User $user): string
    {
        return $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password123',
        ])->json('data.access_token');
    }
}
