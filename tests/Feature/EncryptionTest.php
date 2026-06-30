<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Mahasiswa;
use App\Models\User;
use App\Services\EncryptionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * EncryptionTest — Memvalidasi implementasi AES-256-GCM sesuai metodologi penelitian.
 *
 * Checkpoint penelitian yang diuji:
 * 1. EncryptionService menghasilkan output yang berbeda dengan plaintext (terenkripsi)
 * 2. Data di database disimpan sebagai JSON payload (bukan plaintext)
 * 3. EncryptionService dapat mendekripsi kembali ke nilai asli
 * 4. Setiap enkripsi menghasilkan IV yang berbeda (keamanan)
 * 5. Model Mahasiswa otomatis enkripsi saat save dan dekripsi saat read
 */
class EncryptionTest extends TestCase
{
    use RefreshDatabase;

    private EncryptionService $encryptionService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->encryptionService = app(EncryptionService::class);
    }

    /** Test 1: EncryptionService menghasilkan output terenkripsi (bukan plaintext). */
    public function test_encrypt_does_not_return_plaintext(): void
    {
        $plaintext = 'Najwa Rizqiyah Munir';
        $encrypted = $this->encryptionService->encrypt($plaintext);

        $this->assertNotEquals($plaintext, $encrypted);
        $this->assertStringContainsString('"iv"', $encrypted);
        $this->assertStringContainsString('"tag"', $encrypted);
        $this->assertStringContainsString('"data"', $encrypted);
    }

    /** Test 2: EncryptionService dapat mendekripsi kembali ke nilai asli. */
    public function test_decrypt_returns_original_plaintext(): void
    {
        $plaintext = 'Najwa Rizqiyah Munir';
        $encrypted = $this->encryptionService->encrypt($plaintext);
        $decrypted = $this->encryptionService->decrypt($encrypted);

        $this->assertEquals($plaintext, $decrypted);
    }

    /** Test 3: Setiap enkripsi menghasilkan IV yang berbeda (nonce unik). */
    public function test_each_encryption_produces_unique_iv(): void
    {
        $plaintext = 'Data Mahasiswa';

        $enc1 = json_decode($this->encryptionService->encrypt($plaintext), true);
        $enc2 = json_decode($this->encryptionService->encrypt($plaintext), true);

        $this->assertNotEquals($enc1['iv'], $enc2['iv'], 'IV harus berbeda setiap enkripsi (nonce unik)');
        $this->assertNotEquals($enc1['data'], $enc2['data'], 'Ciphertext harus berbeda karena IV berbeda');
    }

    /** Test 4: Output enkripsi adalah JSON valid dengan struktur yang benar. */
    public function test_encrypted_output_is_valid_json_with_required_fields(): void
    {
        $encrypted = $this->encryptionService->encrypt('test data');
        $decoded   = json_decode($encrypted, true);

        $this->assertIsArray($decoded);
        $this->assertArrayHasKey('iv', $decoded);
        $this->assertArrayHasKey('tag', $decoded);
        $this->assertArrayHasKey('data', $decoded);
        $this->assertNotEmpty($decoded['iv']);
        $this->assertNotEmpty($decoded['tag']);
        $this->assertNotEmpty($decoded['data']);
    }

    /** Test 5: isEncrypted() mengembalikan true untuk payload yang valid. */
    public function test_is_encrypted_detects_valid_payload(): void
    {
        $encrypted = $this->encryptionService->encrypt('test');
        $this->assertTrue($this->encryptionService->isEncrypted($encrypted));
    }

    /** Test 6: isEncrypted() mengembalikan false untuk plaintext. */
    public function test_is_encrypted_returns_false_for_plaintext(): void
    {
        $this->assertFalse($this->encryptionService->isEncrypted('Najwa Rizqiyah Munir'));
        $this->assertFalse($this->encryptionService->isEncrypted('plain text biasa'));
    }

    /** Test 7: Mahasiswa model menyimpan PII ter-enkripsi ke database (bukan plaintext). */
    public function test_mahasiswa_model_stores_encrypted_data_in_database(): void
    {
        $user = User::factory()->create();

        Mahasiswa::create([
            'user_id'       => $user->id,
            'nama'          => 'Najwa Rizqiyah Munir',
            'email'         => 'najwa@student.ac.id',
            'alamat'        => 'Jl. Merdeka No. 1',
            'nomor_telepon' => '08123456789',
            'program_studi' => 'Teknik Informatika',
            'semester'      => 7,
            'angkatan'      => '2022',
        ]);

        // Verifikasi: nilai di database adalah ciphertext (bukan plaintext)
        $rawFromDb = \Illuminate\Support\Facades\DB::table('mahasiswas')->first();

        $this->assertStringNotContainsString(
            'Najwa Rizqiyah Munir',
            $rawFromDb->nama,
            'Nama TIDAK BOLEH tersimpan sebagai plaintext di database'
        );
        $this->assertStringContainsString(
            '"iv"',
            $rawFromDb->nama,
            'Nama harus tersimpan sebagai JSON AES-256-GCM payload'
        );
        $this->assertStringContainsString('"iv"', $rawFromDb->email);
        $this->assertStringContainsString('"iv"', $rawFromDb->alamat);
        $this->assertStringContainsString('"iv"', $rawFromDb->nomor_telepon);
    }

    /** Test 8: Mahasiswa model otomatis mendekripsi saat dibaca. */
    public function test_mahasiswa_model_auto_decrypts_on_read(): void
    {
        $user = User::factory()->create();

        Mahasiswa::create([
            'user_id'       => $user->id,
            'nama'          => 'Najwa Rizqiyah Munir',
            'email'         => 'najwa@student.ac.id',
            'alamat'        => 'Jl. Merdeka No. 1',
            'nomor_telepon' => '08123456789',
            'program_studi' => 'Teknik Informatika',
            'semester'      => 7,
            'angkatan'      => '2022',
        ]);

        // Baca kembali dari database melalui Eloquent
        $mahasiswa = Mahasiswa::first();

        // Verifikasi: nilai yang dibaca sudah ter-dekripsi
        $this->assertEquals('Najwa Rizqiyah Munir', $mahasiswa->nama);
        $this->assertEquals('najwa@student.ac.id', $mahasiswa->email);
        $this->assertEquals('Jl. Merdeka No. 1', $mahasiswa->alamat);
        $this->assertEquals('08123456789', $mahasiswa->nomor_telepon);
    }

    /** Test 9: Dekripsi dengan payload rusak/tidak valid akan throw exception. */
    public function test_decrypt_throws_exception_for_invalid_payload(): void
    {
        $this->expectException(\Throwable::class);

        $this->encryptionService->decrypt('bukan-json-valid');
    }

    /** Test 10: PIN disimpan terenkripsi di database dan otomatis didekripsi. */
    public function test_user_pin_is_stored_encrypted_in_database(): void
    {
        $user = User::factory()->create([
            'nim'      => '221043',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'pin'      => '123456',
        ]);

        // Verifikasi raw database tidak memiliki PIN bernilai plaintext
        $rawUser = \Illuminate\Support\Facades\DB::table('users')->where('id', $user->id)->first();
        $this->assertNotEquals('123456', $rawUser->pin);
        $this->assertStringContainsString('"iv"', $rawUser->pin);

        // Verifikasi model Eloquent mendekripsi secara otomatis
        $loadedUser = User::find($user->id);
        $this->assertEquals('123456', $loadedUser->pin);
    }

    /** Test 11: Endpoint verifikasi PIN merespon dengan benar. */
    public function test_pin_verification_endpoints(): void
    {
        $user = User::factory()->create([
            'nim'      => '221043',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'pin'      => '123456',
            'role'     => 'mahasiswa',
        ]);

        // Belum login - dialihkan
        $response = $this->postJson(route('transcript.verify-pin'), ['pin' => '123456']);
        $response->assertStatus(401); // Unauthorized (karena route diproteksi middleware auth.mahasiswa)

        // Login
        $this->actingAs($user);

        // PIN salah
        $response = $this->postJson(route('transcript.verify-pin'), ['pin' => '999999']);
        $response->assertStatus(422);
        $response->assertJsonPath('success', false);

        // PIN benar
        $response = $this->postJson(route('transcript.verify-pin'), ['pin' => '123456']);
        $response->assertOk();
        $response->assertJsonPath('success', true);
    }

    /** Test 12: Download transkrip hanya dapat diakses jika PIN terverifikasi. */
    public function test_transcript_download_requires_pin_verification(): void
    {
        $user = User::factory()->create([
            'nim'      => '221043',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'pin'      => '123456',
            'role'     => 'mahasiswa',
        ]);

        // Hubungkan profile mahasiswa
        Mahasiswa::create([
            'user_id'       => $user->id,
            'nama'          => 'Najwa Rizqiyah',
            'email'         => 'najwa@student.ac.id',
            'alamat'        => 'Bandung',
            'nomor_telepon' => '0812345',
            'program_studi' => 'Informatika',
            'semester'      => 7,
            'angkatan'      => '2022',
        ]);

        $this->actingAs($user);

        // Langsung coba unduh tanpa verifikasi PIN - dialihkan
        $response = $this->get(route('transcript.download'));
        $response->assertRedirect(route('transcript'));
        $this->assertTrue(session()->has('errors'));

        // Jalankan verifikasi PIN sukses
        $this->postJson(route('transcript.verify-pin'), ['pin' => '123456'])->assertOk();

        // Coba unduh kembali - harus sukses dan mengembalikan file HTML
        $response = $this->get(route('transcript.download'));
        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename="Transkrip_Resmi_Najwa_Rizqiyah_221043.html"');
    }
}
