<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Ipk;
use App\Models\JadwalKuliah;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Nilai;
use App\Models\User;
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
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ── 1. Buat User Admin & Mahasiswa ───────────────────────────────────────
        // Password di-hash ARGON2ID oleh Laravel 'hashed' cast
        User::create([
            'nim'      => 'admin001',
            'password' => Hash::make('admin123'), // Argon2id via HASH_DRIVER env
            'role'     => 'admin',
        ]);

        $user = User::create([
            'nim'      => '221043',
            'password' => Hash::make('password123'),
            'role'     => 'mahasiswa',
        ]);

        // ── 2. Buat Profil Mahasiswa ─────────────────────────────────────
        // Data PII di-enkripsi otomatis oleh EncryptedCast (AES-256-GCM)
        $mahasiswa = Mahasiswa::create([
            'user_id'       => $user->id,
            'nama'          => 'Najwa Rizqiyah Munir',      // → tersimpan ter-enkripsi
            'email'         => 'najwa.rizqiyah@mahasiswa.ac.id', // → tersimpan ter-enkripsi
            'alamat'        => 'Jl. Merdeka No. 45, Bandung', // → tersimpan ter-enkripsi
            'nomor_telepon' => '081234567890',                 // → tersimpan ter-enkripsi
            'program_studi' => 'Teknik Informatika',
            'semester'      => 7,
            'angkatan'      => '2022',
        ]);

        // ── 3. Buat Mata Kuliah ──────────────────────────────────────────
        $mataKuliahs = [
            ['kode_mk' => 'TIF601', 'nama_mk' => 'Keamanan Sistem Informasi', 'sks' => 3, 'semester' => 7, 'jenis' => 'wajib'],
            ['kode_mk' => 'TIF602', 'nama_mk' => 'Kecerdasan Buatan',         'sks' => 3, 'semester' => 7, 'jenis' => 'wajib'],
            ['kode_mk' => 'TIF603', 'nama_mk' => 'Pemrograman Web Lanjut',    'sks' => 3, 'semester' => 7, 'jenis' => 'wajib'],
            ['kode_mk' => 'TIF604', 'nama_mk' => 'Basis Data Terdistribusi',  'sks' => 3, 'semester' => 7, 'jenis' => 'wajib'],
            ['kode_mk' => 'TIF605', 'nama_mk' => 'Metodologi Penelitian',     'sks' => 2, 'semester' => 7, 'jenis' => 'wajib'],
            ['kode_mk' => 'TIF606', 'nama_mk' => 'Skripsi',                   'sks' => 6, 'semester' => 8, 'jenis' => 'wajib'],
            ['kode_mk' => 'TIF501', 'nama_mk' => 'Algoritma & Kompleksitas',  'sks' => 3, 'semester' => 5, 'jenis' => 'wajib'],
            ['kode_mk' => 'TIF502', 'nama_mk' => 'Pemrograman Berorientasi Objek', 'sks' => 3, 'semester' => 5, 'jenis' => 'wajib'],
            ['kode_mk' => 'TIF401', 'nama_mk' => 'Struktur Data',             'sks' => 3, 'semester' => 4, 'jenis' => 'wajib'],
            ['kode_mk' => 'TIF402', 'nama_mk' => 'Sistem Operasi',            'sks' => 3, 'semester' => 4, 'jenis' => 'wajib'],
        ];

        $createdMataKuliahs = collect($mataKuliahs)->map(
            fn ($mk) => MataKuliah::create($mk)
        );

        // ── 4. Buat Jadwal Kuliah ────────────────────────────────────────
        $jadwals = [
            ['mk' => 'TIF601', 'hari' => 'Senin',  'mulai' => '08:00', 'selesai' => '10:30', 'ruangan' => 'Lab 301', 'dosen' => 'Dr. Ahmad Fauzi, M.T.'],
            ['mk' => 'TIF602', 'hari' => 'Senin',  'mulai' => '13:00', 'selesai' => '15:30', 'ruangan' => 'GD 401',  'dosen' => 'Dr. Siti Nurhaliza, M.Kom.'],
            ['mk' => 'TIF603', 'hari' => 'Selasa', 'mulai' => '08:00', 'selesai' => '10:30', 'ruangan' => 'Lab 302', 'dosen' => 'Ir. Budi Santoso, M.T.'],
            ['mk' => 'TIF604', 'hari' => 'Rabu',   'mulai' => '10:00', 'selesai' => '12:30', 'ruangan' => 'Lab 303', 'dosen' => 'Prof. Andi Rahman, Ph.D.'],
            ['mk' => 'TIF605', 'hari' => 'Kamis',  'mulai' => '09:00', 'selesai' => '10:40', 'ruangan' => 'GD 201',  'dosen' => 'Dr. Lina Wati, M.Pd.'],
            ['mk' => 'TIF601', 'hari' => 'Jumat',  'mulai' => '13:00', 'selesai' => '14:40', 'ruangan' => 'Lab 301', 'dosen' => 'Dr. Ahmad Fauzi, M.T.'],
            ['mk' => 'TIF602', 'hari' => 'Rabu',   'mulai' => '08:00', 'selesai' => '09:40', 'ruangan' => 'GD 402',  'dosen' => 'Dr. Siti Nurhaliza, M.Kom.'],
        ];

        foreach ($jadwals as $j) {
            $mk = $createdMataKuliahs->firstWhere('kode_mk', $j['mk']);
            JadwalKuliah::create([
                'mata_kuliah_id' => $mk->id,
                'hari'          => $j['hari'],
                'jam_mulai'     => $j['mulai'],
                'jam_selesai'   => $j['selesai'],
                'ruangan'       => $j['ruangan'],
                'dosen'         => $j['dosen'],
            ]);
        }

        // ── 5. Buat Nilai Mahasiswa ──────────────────────────────────────
        // `nilai_angka` di-enkripsi otomatis oleh EncryptedCast (AES-256-GCM)
        $nilaiData = [
            ['mk' => 'TIF401', 'angka' => '88', 'grade' => 'A',  'sem' => '2023/2024 Ganjil'],
            ['mk' => 'TIF402', 'angka' => '82', 'grade' => 'A-', 'sem' => '2023/2024 Ganjil'],
            ['mk' => 'TIF501', 'angka' => '91', 'grade' => 'A',  'sem' => '2023/2024 Genap'],
            ['mk' => 'TIF502', 'angka' => '85', 'grade' => 'A-', 'sem' => '2023/2024 Genap'],
            ['mk' => 'TIF601', 'angka' => '95', 'grade' => 'A',  'sem' => '2024/2025 Ganjil'],
            ['mk' => 'TIF602', 'angka' => '87', 'grade' => 'A-', 'sem' => '2024/2025 Ganjil'],
            ['mk' => 'TIF603', 'angka' => '90', 'grade' => 'A',  'sem' => '2024/2025 Ganjil'],
            ['mk' => 'TIF604', 'angka' => '83', 'grade' => 'A-', 'sem' => '2024/2025 Ganjil'],
            ['mk' => 'TIF605', 'angka' => '88', 'grade' => 'A',  'sem' => '2024/2025 Ganjil'],
        ];

        foreach ($nilaiData as $n) {
            $mk = $createdMataKuliahs->firstWhere('kode_mk', $n['mk']);
            Nilai::create([
                'mahasiswa_id'   => $mahasiswa->id,
                'mata_kuliah_id' => $mk->id,
                'nilai_angka'    => $n['angka'],   // → tersimpan ter-enkripsi AES-256-GCM
                'grade'          => $n['grade'],
                'semester_tahun' => $n['sem'],
            ]);
        }

        // ── 6. Buat IPK ─────────────────────────────────────────────────
        // `ipk` di-enkripsi otomatis oleh EncryptedCast (AES-256-GCM)
        Ipk::create([
            'mahasiswa_id' => $mahasiswa->id,
            'ipk'          => '3.92',   // → tersimpan ter-enkripsi AES-256-GCM
            'total_sks'    => 129,
        ]);

        $this->command->info('✅ Seeding selesai!');
        $this->command->info('   Admin Login : NIM = admin001 | Password = admin123');
        $this->command->info('   Mhs Login   : NIM = 221043   | Password = password123');
        $this->command->info('   🔒 Password di-hash Argon2id');
        $this->command->info('   🔒 PII mahasiswa ter-enkripsi AES-256-GCM');
        $this->command->info('   🔒 Nilai angka ter-enkripsi AES-256-GCM');
        $this->command->info('   🔒 IPK ter-enkripsi AES-256-GCM');
    }
}
