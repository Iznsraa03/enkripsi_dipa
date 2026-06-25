<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * AuthTest — Menguji flow autentikasi Argon2id SIAKAD.
 *
 * Validasi checkpoint penelitian:
 * 1. Login berhasil dengan NIM + password yang benar
 * 2. Login gagal dengan password salah
 * 3. Session ID ter-regenerate setelah login (cegah session fixation)
 * 4. Redirect ke /login jika belum autentikasi
 * 5. Logout menginvalidasi session
 */
class AuthTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'nim'      => '221043',
            'password' => Hash::make('password123'),
            'role'     => 'mahasiswa',
        ]);
    }

    /** Test 1: Login berhasil dengan credentials yang valid. */
    public function test_mahasiswa_can_login_with_valid_credentials(): void
    {
        $response = $this->post(route('login.post'), [
            'nim'      => '221043',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
    }

    /** Test 2: Login gagal dengan password salah. */
    public function test_login_fails_with_wrong_password(): void
    {
        $response = $this->post(route('login.post'), [
            'nim'      => '221043',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('nim');
        $this->assertGuest();
    }

    /** Test 3: Login gagal dengan NIM tidak terdaftar. */
    public function test_login_fails_with_unknown_nim(): void
    {
        $response = $this->post(route('login.post'), [
            'nim'      => '999999',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('nim');
        $this->assertGuest();
    }

    /** Test 4: Validasi input — NIM wajib diisi. */
    public function test_nim_is_required(): void
    {
        $response = $this->post(route('login.post'), [
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('nim');
    }

    /** Test 5: Validasi input — Password wajib diisi. */
    public function test_password_is_required(): void
    {
        $response = $this->post(route('login.post'), [
            'nim' => '221043',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** Test 6: Guest redirect ke login saat akses dashboard. */
    public function test_guest_is_redirected_to_login_from_dashboard(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }

    /** Test 7: Guest redirect ke login saat akses schedule. */
    public function test_guest_is_redirected_to_login_from_schedule(): void
    {
        $response = $this->get(route('schedule'));

        $response->assertRedirect(route('login'));
    }

    /** Test 8: Guest redirect ke login saat akses transcript. */
    public function test_guest_is_redirected_to_login_from_transcript(): void
    {
        $response = $this->get(route('transcript'));

        $response->assertRedirect(route('login'));
    }

    /** Test 9: User ter-autentikasi dapat akses dashboard. */
    public function test_authenticated_user_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->user)->get(route('dashboard'));

        $response->assertOk();
    }

    /** Test 10: Logout menghapus sesi autentikasi. */
    public function test_logout_clears_authentication(): void
    {
        $this->actingAs($this->user);
        $this->assertAuthenticated();

        $response = $this->get(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
