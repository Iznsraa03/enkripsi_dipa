<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Ipk;
use App\Models\JadwalKuliah;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Nilai;
use App\Models\User;
use App\Models\Jurusan;
use App\Models\Dosen;
use App\Models\Ruangan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * DatabaseSeeder — Data awal untuk SIAKAD Enkripsi.
 *
 * Demonstrasi penelitian:
 *  - Password di-hash dengan Argon2id
 *  - Data PII (nama, email, alamat, nomor_telepon) disimpan ter-enkripsi AES-256-GCM
 *  - Nilai angka mahasiswa disimpan ter-enkripsi AES-256-GCM
 *  - IPK disimpan ter-enkripsi AES-256-GCM
 *
 * Akun demo: NIM 221043 / password: password123
 */
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Truncate semua tabel (urutan terbalik untuk FK constraint)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Ipk::truncate();
        Nilai::truncate();
        JadwalKuliah::truncate();
        MataKuliah::truncate();
        Mahasiswa::truncate();
        User::truncate();
        Jurusan::truncate();
        Dosen::truncate();
        Ruangan::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ── 0. Buat Master Data (Jurusan, Dosen, Ruangan) ────────────────────────
        $jurusan = Jurusan::create(['nama_jurusan' => 'Teknik Informatika']);

        $csvPath = base_path('datauniv baru.csv');
        $csvData = [];
        if (($handle = fopen($csvPath, "r")) !== false) {
            $header = fgetcsv($handle, 1000, ",");
            // Remove BOM from first header item if exists
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
            $dosens[$d] = Dosen::create(['nama_dosen' => $d]);
        }

        $ruanganNames = collect($csvData)->pluck('Ruang')->unique()->values();
        $ruangans = [];
        foreach ($ruanganNames as $r) {
            $ruangans[$r] = Ruangan::create(['nama_ruangan' => $r]);
        }

        // ── 1. Buat User Admin & Mahasiswa ───────────────────────────────────────
        User::create([
            'nim'      => 'admin001',
            'password' => Hash::make('admin123'),
            'pin'      => '123456',
            'role'     => 'admin',
        ]);

        $user = User::create([
            'nim'      => '221043',
            'password' => Hash::make('password123'),
            'pin'      => '123456',
            'role'     => 'mahasiswa',
        ]);

        // ── 2. Buat Profil Mahasiswa ─────────────────────────────────────
        $mahasiswa = Mahasiswa::create([
            'user_id'       => $user->id,
            'nama'          => 'Najwa Rizqiyah Munir',
            'email'         => 'najwa.rizqiyah@mahasiswa.ac.id',
            'alamat'        => 'Jl. Merdeka No. 45, Bandung',
            'nomor_telepon' => '081234567890',
            'jurusan_id'    => $jurusan->id,
            'semester'      => 6,
            'angkatan'      => '2023',
        ]);

        // ── 3. Buat Mata Kuliah ──────────────────────────────────────────
        $createdMataKuliahs = collect();
        $mataKuliahGroups = collect($csvData)->groupBy('Kode');
        foreach ($mataKuliahGroups as $kode => $group) {
            $first = $group->first();
            $semester = (int) substr($first['Kelas'], 0, 1);
            if ($semester === 0) $semester = 6;

            $mk = MataKuliah::create([
                'kode_mk' => $kode,
                'nama_mk' => $first['Mata Kuliah'],
                'sks' => (int) $first['SKS'],
                'semester' => $semester,
                'jenis' => 'wajib',
            ]);
            $createdMataKuliahs->put($kode, $mk);
        }

        // ── 4. Buat Jadwal Kuliah ────────────────────────────────────────
        foreach ($csvData as $j) {
            $mk = $createdMataKuliahs->get($j['Kode']);
            
            $jam = str_replace(['.', ' '], [':', ''], $j['Jam']);
            $jamParts = explode('-', $jam);
            $jam_mulai = count($jamParts) > 0 ? $jamParts[0] : '08:00';
            $jam_selesai = count($jamParts) > 1 ? $jamParts[1] : '10:00';
            
            $hari = ucfirst(strtolower($j['Hari']));

            JadwalKuliah::create([
                'mahasiswa_id'  => $mahasiswa->id,
                'mata_kuliah_id' => $mk->id,
                'hari'          => $hari,
                'jam_mulai'     => $jam_mulai,
                'jam_selesai'   => $jam_selesai,
                'ruangan_id'    => $ruangans[$j['Ruang']]->id,
                'dosen_id'      => $dosens[$j['Dosen']]->id,
            ]);
        }

        // ── 5. Buat Nilai Mahasiswa ──────────────────────────────────────
        $grades = ['A', 'A-', 'B+', 'B', 'B-'];
        $scores = [95, 87, 83, 78, 75];
        $count = 0;
        foreach ($createdMataKuliahs as $mk) {
            Nilai::create([
                'mahasiswa_id'   => $mahasiswa->id,
                'mata_kuliah_id' => $mk->id,
                'nilai_angka'    => (string) $scores[$count % 5],
                'grade'          => $grades[$count % 5],
                'semester_tahun' => '2023/2024 Genap',
            ]);
            $count++;
            if ($count >= 6) break;
        }

        // ── 6. Buat IPK ─────────────────────────────────────────────────
        // `ipk` di-enkripsi otomatis oleh EncryptedCast (AES-256-GCM)
        Ipk::create([
            'mahasiswa_id' => $mahasiswa->id,
            'ipk'          => '3.92',   // → tersimpan ter-enkripsi AES-256-GCM
            'total_sks'    => 129,
        ]);

        $this->command->info('✅ Seeding selesai!');
        $this->command->info('   Admin Login : NIM = admin001 | Password = admin123 | PIN = 123456');
        $this->command->info('   Mhs Login   : NIM = 221043   | Password = password123 | PIN = 123456');
        $this->command->info('   🔒 Password di-hash Argon2id');
        $this->command->info('   🔒 PII mahasiswa ter-enkripsi AES-256-GCM');
        $this->command->info('   🔒 Nilai angka ter-enkripsi AES-256-GCM');
        $this->command->info('   🔒 IPK ter-enkripsi AES-256-GCM');
    }
}
