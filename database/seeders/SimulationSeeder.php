<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * SimulationSeeder — Data awal untuk mode SIMULASI (tanpa enkripsi).
 *
 * Demonstrasi penelitian (TANPA enkripsi):
 *  - Password di-hash dengan BCRYPT (bukan Argon2id)
 *  - Data PII (nama, email, alamat, nomor_telepon) disimpan sebagai PLAINTEXT
 *  - Nilai angka mahasiswa disimpan sebagai PLAINTEXT
 *  - IPK disimpan sebagai PLAINTEXT
 *
 * Semua data masuk ke tabel ber-prefix sim_ (koneksi mysql_simulation).
 * Akun demo: NIM 221043 / password: password123
 */
class SimulationSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Pastikan seeder ini berjalan di koneksi simulasi
        $connection = 'mysql_simulation';

        // Truncate semua tabel simulasi (urutan terbalik untuk FK constraint)
        DB::connection($connection)->statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::connection($connection)->table('ipks')->truncate();
        DB::connection($connection)->table('nilais')->truncate();
        DB::connection($connection)->table('jadwal_kuliahs')->truncate();
        DB::connection($connection)->table('mata_kuliahs')->truncate();
        DB::connection($connection)->table('mahasiswas')->truncate();
        DB::connection($connection)->table('users')->truncate();
        DB::connection($connection)->table('jurusans')->truncate();
        DB::connection($connection)->table('dosens')->truncate();
        DB::connection($connection)->table('ruangans')->truncate();
        DB::connection($connection)->statement('SET FOREIGN_KEY_CHECKS=1;');

        // ── 0. Buat Master Data (Jurusan, Dosen, Ruangan) ──────────────────────
        $jurusanId = DB::connection($connection)->table('jurusans')->insertGetId([
            'nama_jurusan' => 'Teknik Informatika',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $csvPath = base_path('datauniv baru.csv');
        $csvData = [];
        if (($handle = fopen($csvPath, "r")) !== false) {
            $header = fgetcsv($handle, 1000, ",");
            $header[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $header[0]);
            
            while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                if (count($row) >= 8) {
                    $csvData[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        $dosenNames = collect($csvData)->pluck('Dosen')->unique()->values();
        $dosens = [];
        foreach ($dosenNames as $d) {
            $dosens[$d] = DB::connection($connection)->table('dosens')->insertGetId([
                'nama_dosen' => $d,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $ruanganNames = collect($csvData)->pluck('Ruang')->unique()->values();
        $ruangans = [];
        foreach ($ruanganNames as $r) {
            $ruangans[$r] = DB::connection($connection)->table('ruangans')->insertGetId([
                'nama_ruangan' => $r,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ── 1. Buat User Admin & Mahasiswa ─────────────────────────────────────
        $adminId = DB::connection($connection)->table('users')->insertGetId([
            'nim'        => 'admin001',
            'password'   => Hash::driver('bcrypt')->make('admin123'),
            'pin'        => '123456',
            'role'       => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $userId = DB::connection($connection)->table('users')->insertGetId([
            'nim'        => '221043',
            'password'   => Hash::driver('bcrypt')->make('password123'),
            'pin'        => '123456',
            'role'       => 'mahasiswa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ── 2. Buat Profil Mahasiswa ──────────────────────────────────────────
        $mahasiswaId = DB::connection($connection)->table('mahasiswas')->insertGetId([
            'user_id'        => $userId,
            'nama'           => 'Najwa Rizqiyah Munir',
            'email'          => 'najwa.rizqiyah@mahasiswa.ac.id',
            'alamat'         => 'Jl. Merdeka No. 45, Bandung',
            'nomor_telepon'  => '081234567890',
            'jurusan_id'     => $jurusanId,
            'semester'       => 6,
            'angkatan'       => '2023',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        // ── 3. Buat Mata Kuliah ────────────────────────────────────────────────
        $mkIds = [];
        $createdMataKuliahs = collect();
        $mataKuliahGroups = collect($csvData)->groupBy('Kode');
        foreach ($mataKuliahGroups as $kode => $group) {
            $first = $group->first();
            $semester = (int) substr($first['Kelas'], 0, 1);
            if ($semester === 0) $semester = 6;

            $mkId = DB::connection($connection)->table('mata_kuliahs')->insertGetId([
                'kode_mk' => $kode,
                'nama_mk' => $first['Mata Kuliah'],
                'sks' => (int) $first['SKS'],
                'semester' => $semester,
                'jenis' => 'wajib',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $mkIds[$kode] = $mkId;
            $createdMataKuliahs->put($kode, $mkId); // to use in Nilai
        }

        // ── 4. Buat Jadwal Kuliah ──────────────────────────────────────────────
        foreach ($csvData as $j) {
            $jam = str_replace(['.', ' '], [':', ''], $j['Jam']);
            $jamParts = explode('-', $jam);
            $jam_mulai = count($jamParts) > 0 ? $jamParts[0] : '08:00';
            $jam_selesai = count($jamParts) > 1 ? $jamParts[1] : '10:00';
            
            $hari = ucfirst(strtolower($j['Hari']));

            DB::connection($connection)->table('jadwal_kuliahs')->insert([
                'mahasiswa_id'   => $mahasiswaId,
                'mata_kuliah_id' => $mkIds[$j['Kode']],
                'hari'           => $hari,
                'jam_mulai'      => $jam_mulai,
                'jam_selesai'    => $jam_selesai,
                'ruangan_id'     => $ruangans[$j['Ruang']],
                'dosen_id'       => $dosens[$j['Dosen']],
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        // ── 5. Buat Nilai Mahasiswa ───────────────────────────────────────────
        $grades = ['A', 'A-', 'B+', 'B', 'B-'];
        $scores = [95, 87, 83, 78, 75];
        $count = 0;
        foreach ($mkIds as $kode => $mkId) {
            DB::connection($connection)->table('nilais')->insert([
                'mahasiswa_id'   => $mahasiswaId,
                'mata_kuliah_id' => $mkId,
                'nilai_angka'    => (string) $scores[$count % 5],
                'grade'          => $grades[$count % 5],
                'semester_tahun' => '2023/2024 Genap',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
            $count++;
            if ($count >= 6) break;
        }

        // ── 6. Buat IPK ──────────────────────────────────────────────────────
        // `ipk` disimpan sebagai PLAINTEXT (bukan AES-256-GCM)
        DB::connection($connection)->table('ipks')->insert([
            'mahasiswa_id' => $mahasiswaId,
            'ipk'          => '3.92',  // plaintext
            'total_sks'    => 129,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        $this->command->info('✅ SimulationSeeder selesai!');
        $this->command->info('   Admin Login : NIM = admin001 | Password = admin123 | PIN = 123456');
        $this->command->info('   Mhs Login   : NIM = 221043   | Password = password123 | PIN = 123456');
        $this->command->info('   ⚠️  Password di-hash Bcrypt (bukan Argon2id)');
        $this->command->info('   ⚠️  PII mahasiswa disimpan PLAINTEXT (bukan AES-256-GCM)');
        $this->command->info('   ⚠️  Nilai angka disimpan PLAINTEXT (bukan AES-256-GCM)');
        $this->command->info('   ⚠️  IPK disimpan PLAINTEXT (bukan AES-256-GCM)');
    }
}
