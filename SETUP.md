# Panduan Setup Proyek Enkripsi Dipa (Laravel)

Dokumen ini berisi panduan langkah demi langkah untuk melakukan pengesetan (*setup*) proyek **enkripsi_dipa** di lingkungan **Lokal (Native PHP & MySQL)**.

---

## Prasyarat System
- **PHP** >= 8.2 (Pastikan ekstensi `argon2`, `pdo_mysql`, `mbstring`, `openssl`, `curl`, `fileinfo` aktif).
- **Composer** (v2.x)
- **Database MySQL / MariaDB** (misalnya via Laragon, XAMPP, atau MySQL Service)
- **Node.js** (v18+) & **NPM**

---

## Langkah-langkah Setup

1. **Clone / Masuk ke Direktori Proyek**
   ```bash
   cd /path/to/enkripsi_dipa
   ```

2. **Salin File Environment Configuration**
   ```bash
   cp .env.example .env
   ```

3. **Instal Dependensi PHP**
   ```bash
   composer install
   ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Konfigurasi Database pada File `.env`**
   Buka file `.env` dan sesuaikan kredensial MySQL lokal Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=enkripsi_dipa
   DB_USERNAME=root
   DB_PASSWORD=
   
   HASH_DRIVER=argon2id
   ```
   *Catatan: Pastikan Anda telah membuat database kosong bernama `enkripsi_dipa` di MySQL lokal Anda terlebih dahulu.*

6. **Jalankan Migrasi Database & Seeder**
   - **Migrate & Seed Utama (Data Ter-enkripsi AES-256-GCM & Argon2id):**
     ```bash
     php artisan migrate:fresh --seed
     ```
   - **Seed Simulasi (Data Plaintext `sim_`):**
     ```bash
     php artisan db:seed --class=SimulationSeeder
     ```

7. **Instal Dependensi Node.js & Jalankan Asset Compiler (Vite)**
   ```bash
   npm install
   npm run dev
   ```

8. **Jalankan Development Server**
   Di terminal terpisah, jalankan perintah:
   ```bash
   php artisan serve
   ```
   Aplikasi akan berjalan dan dapat diakses melalui browser di **`http://127.0.0.1:8000`**.

---

## Troubleshooting & Perintah Berguna

- **Reset Database & Run All Seeders**:
  ```bash
  php artisan migrate:fresh --seed
  php artisan db:seed --class=SimulationSeeder
  ```
- **Clear Cache Aplikasi**:
  ```bash
  php artisan config:clear
  php artisan cache:clear
  php artisan route:clear
  ```
- **Mengecek Driver Enkripsi/Hashing Argon2id**:
  Pastikan di `.env` sudah terdapat `HASH_DRIVER=argon2id`. Penjelasan lengkap mengenai algoritma Argon2id pada proyek ini ada di [PENJELASAN_ENKRIPSI.md](file:///Users/potah/Documents/webdev/enkripsi_dipa/PENJELASAN_ENKRIPSI.md).
