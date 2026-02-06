<?php

namespace Tests\Feature\Admin;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_petugas_user(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Petugas Baru',
            'email' => 'petugas.baru@example.com',
            'role' => 'petugas',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect(route('admin.users.index'));

        $created = User::where('email', 'petugas.baru@example.com')->first();
        $this->assertNotNull($created);
        $this->assertSame('petugas', $created->role->value);
    }

    public function test_admin_cannot_create_admin_user_via_ui(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
        ]);

        $response = $this->actingAs($admin)->from(route('admin.users.create'))->post(route('admin.users.store'), [
            'name' => 'Admin Baru',
            'email' => 'admin.baru@example.com',
            'role' => 'admin',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect(route('admin.users.create'));
        $response->assertSessionHasErrors(['role']);

        $this->assertDatabaseMissing('users', [
            'email' => 'admin.baru@example.com',
        ]);
    }

    public function test_admin_can_edit_admin_user_profile_fields(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
        ]);

        $target = User::factory()->create([
            'role' => UserRole::Admin,
            'email' => 'target.admin@example.com',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.users.update', $target), [
            'name' => 'Admin Updated',
            'email' => 'target.admin@example.com',
            'password' => '',
            'password_confirmation' => '',
            'role' => 'petugas',
        ]);

        $response->assertForbidden();

        $target->refresh();
        $this->assertSame('target.admin@example.com', $target->email);
        $this->assertSame('admin', $target->role->value);
    }

    public function test_admin_can_delete_an_admin_user_if_not_last_admin(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
        ]);

        $otherAdmin = User::factory()->create([
            'role' => UserRole::Admin,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $otherAdmin));
        $response->assertForbidden();

        $this->assertDatabaseHas('users', [
            'id' => $otherAdmin->id,
        ]);
    }

    public function test_admin_cannot_delete_last_admin(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $admin));
        $response->assertForbidden();

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
        ]);
    }
}
