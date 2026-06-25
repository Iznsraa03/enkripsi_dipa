<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $mahasiswa;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
    }

    public function test_admin_can_access_user_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.index'));
        $response->assertOk();
    }

    public function test_mahasiswa_cannot_access_user_index(): void
    {
        $response = $this->actingAs($this->mahasiswa)->get(route('admin.users.index'));
        $response->assertForbidden();
    }

    public function test_admin_can_access_mahasiswas_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.mahasiswas.index'));
        $response->assertOk();
    }

    public function test_admin_can_access_mata_kuliahs_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.mata-kuliahs.index'));
        $response->assertOk();
    }

    public function test_admin_can_access_jadwal_kuliahs_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.jadwal-kuliahs.index'));
        $response->assertOk();
    }

    public function test_admin_can_access_nilais_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.nilais.index'));
        $response->assertOk();
    }
}
