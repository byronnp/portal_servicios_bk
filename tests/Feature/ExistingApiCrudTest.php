<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Company;
use App\Models\Instance;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExistingApiCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_instance_api_crud_works(): void
    {
        $token = $this->authorizedToken([
            'instances.index',
            'instances.show',
            'instances.store',
            'instances.update',
            'instances.destroy',
        ]);

        $create = $this->withToken($token)->postJson('/api/catalog/instances', [
            'name' => 'CRM Test',
            'url' => 'https://crm-test.example.com',
            'description' => 'Test instance',
            'can_send_to_crm' => true,
            'status' => true,
        ]);

        $create->assertCreated()->assertJsonPath('success', true);
        $id = $create->json('data.id');

        $this->withToken($token)->getJson('/api/catalog/instances')
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->withToken($token)->getJson("/api/catalog/instances/{$id}")
            ->assertOk()
            ->assertJsonPath('data.id', $id);

        $this->withToken($token)->patchJson("/api/catalog/instances/{$id}", [
            'description' => 'Updated instance',
        ])->assertOk()
            ->assertJsonPath('data.description', 'Updated instance');

        $this->withToken($token)->deleteJson("/api/catalog/instances/{$id}")
            ->assertOk()
            ->assertJsonPath('success', true);
    }

    public function test_company_api_crud_works(): void
    {
        $token = $this->authorizedToken([
            'companies.index',
            'companies.show',
            'companies.store',
            'companies.update',
            'companies.destroy',
        ]);
        $instance = $this->createInstance();

        $create = $this->withToken($token)->postJson('/api/catalog/companies', [
            'instance_id' => $instance->id,
            'ruc' => '1799999999001',
            'name' => 'Company Test',
            'status' => true,
        ]);

        $create->assertCreated()->assertJsonPath('success', true);
        $id = $create->json('data.id');

        $this->withToken($token)->getJson('/api/catalog/companies')
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->withToken($token)->getJson("/api/catalog/companies/{$id}/instances")
            ->assertOk()
            ->assertJsonPath('data.id', $id);

        $this->withToken($token)->patchJson("/api/catalog/companies/{$id}", [
            'description' => 'Updated company',
        ])->assertOk()
            ->assertJsonPath('data.description', 'Updated company');

        $this->withToken($token)->deleteJson("/api/catalog/companies/{$id}")
            ->assertOk()
            ->assertJsonPath('success', true);
    }

    public function test_agency_api_crud_works(): void
    {
        $token = $this->authorizedToken([
            'agencies.index',
            'agencies.show',
            'agencies.store',
            'agencies.update',
            'agencies.destroy',
        ]);
        $company = $this->createCompany();

        $create = $this->withToken($token)->postJson('/api/catalog/agencies', [
            'company_id' => $company->id,
            'name' => 'Agency Test',
            'status' => true,
        ]);

        $create->assertCreated()->assertJsonPath('success', true);
        $id = $create->json('data.id');

        $this->withToken($token)->getJson('/api/catalog/agencies')
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->withToken($token)->getJson("/api/catalog/agencies/{$id}")
            ->assertOk()
            ->assertJsonPath('data.id', $id);

        $this->withToken($token)->patchJson("/api/catalog/agencies/{$id}", [
            'description' => 'Updated agency',
        ])->assertOk()
            ->assertJsonPath('data.description', 'Updated agency');

        $this->withToken($token)->deleteJson("/api/catalog/agencies/{$id}")
            ->assertOk()
            ->assertJsonPath('success', true);
    }

    public function test_application_api_crud_works(): void
    {
        $token = $this->authorizedToken([
            'applications.index',
            'applications.show',
            'applications.store',
            'applications.update',
            'applications.destroy',
        ]);

        $create = $this->withToken($token)->postJson('/api/catalog/applications', [
            'name' => 'Application Test',
            'slug' => 'application-test',
            'description' => 'Test application',
            'is_web' => true,
            'is_mobile' => false,
            'start_url' => 'https://app.example.com',
            'is_active' => true,
        ]);

        $create->assertCreated()->assertJsonPath('success', true);
        $id = $create->json('data.id');

        $this->withToken($token)->getJson('/api/catalog/applications')
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->withToken($token)->getJson("/api/catalog/applications/{$id}")
            ->assertOk()
            ->assertJsonPath('data.id', $id);

        $this->withToken($token)->patchJson("/api/catalog/applications/{$id}", [
            'description' => 'Updated application',
        ])->assertOk()
            ->assertJsonPath('data.description', 'Updated application');

        $this->withToken($token)->deleteJson("/api/catalog/applications/{$id}")
            ->assertOk()
            ->assertJsonPath('success', true);
    }

    public function test_role_api_crud_works_with_permissions(): void
    {
        $token = $this->authorizedToken([
            'roles.index',
            'roles.show',
            'roles.store',
            'roles.update',
            'roles.destroy',
        ]);
        $permission = Permission::create([
            'name' => 'Extra Permission',
            'slug' => 'extra.permission',
            'module' => 'test',
            'is_active' => true,
        ]);

        $create = $this->withToken($token)->postJson('/api/roles', [
            'name' => 'Role Test',
            'slug' => 'role-test',
            'description' => 'Test role',
            'is_active' => true,
            'permissions' => [$permission->id],
        ]);

        $create->assertOk()->assertJsonPath('success', true);
        $id = $create->json('data.id');

        $this->withToken($token)->getJson('/api/roles')
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->withToken($token)->getJson("/api/roles/{$id}")
            ->assertOk()
            ->assertJsonPath('data.id', $id);

        $this->withToken($token)->patchJson("/api/roles/{$id}", [
            'name' => 'Role Test Updated',
            'slug' => 'role-test-updated',
            'description' => 'Updated role',
            'permissions' => [$permission->id],
        ])->assertOk()
            ->assertJsonPath('data.name', 'Role Test Updated');

        $this->withToken($token)->deleteJson("/api/roles/{$id}")
            ->assertOk()
            ->assertJsonPath('success', true);
    }

    private function authorizedToken(array $permissionSlugs): string
    {
        $application = Application::create([
            'name' => 'Portal Test',
            'slug' => 'portal-test',
            'is_web' => true,
            'is_mobile' => false,
            'is_active' => true,
        ]);

        $user = User::create([
            'name' => 'Api Tester',
            'email' => 'api-tester@example.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
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
            'name' => 'Api Admin',
            'slug' => 'api-admin',
            'description' => 'API test role',
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

        return $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password123',
        ])->json('data.access_token');
    }

    private function createInstance(): Instance
    {
        return Instance::create([
            'name' => 'Instance Fixture',
            'url' => 'https://instance-fixture.example.com',
            'status' => true,
        ]);
    }

    private function createCompany(): Company
    {
        return Company::create([
            'instance_id' => $this->createInstance()->id,
            'ruc' => '1791111111001',
            'name' => 'Company Fixture',
            'status' => true,
        ]);
    }
}
