# Setup Docker Project (Ponytail Mode)

Proyek ini telah dikonfigurasi menggunakan **Laravel Sail** — cara paling standar, minimalis, dan resmi (official) dari Laravel untuk menjalankan Docker tanpa *boilerplate* konfigurasi (Nginx/Dockerfile) kustom.

## Prasyarat di Komputer/Perangkat Lain
Pastikan Anda sudah menginstal:
- **Git**
- **Docker Desktop** (atau Docker Engine + Docker Compose)
- *Tidak perlu menginstal PHP atau Node.js di lokal.*

---

## Panduan Instalasi (Step-by-Step)

### 1. Clone Repository & Salin `.env`
Buka terminal dan jalankan:
```bash
git clone <URL_REPOSITORY> enkripsi_dipa
cd enkripsi_dipa
cp .env.example .env
```
**Penting:** Buka file `.env` dan pastikan konfigurasi database seperti ini (mengarah ke container mysql):
```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=enkripsi_dipa
DB_USERNAME=sail
DB_PASSWORD=password
```
*(Catatan: Username default Sail adalah `sail` dengan password `password` atau gunakan root tanpa password sesuai konfigurasi di environment)*

### 2. Install Vendor Dependencies (Composer) via Docker Kecil
Sebelum bisa menjalankan Sail, kita perlu menginstal folder `vendor` pertama kali menggunakan container composer sementara:
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

### 3. Jalankan Laravel Sail
Setelah vendor terinstal, jalankan semua layanan container (PHP & MySQL):
```bash
./vendor/bin/sail up -d
```
*(Tip: Anda bisa membuat alias `alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'` agar cukup memanggil `sail up -d`)*

### 4. Setup Database & Key
Jalankan migrasi database dan generate application key:
```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
```

### 5. Build Asset Frontend (Vite)
Compile CSS/JS menggunakan Node di dalam container Sail:
```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

---

## Selesai!
Akses aplikasi melalui browser:
👉 **http://localhost**

*Jika Anda ingin mematikan container, gunakan perintah:*
```bash
./vendor/bin/sail down
```

> **Ponytail Note:** Menggunakan Laravel Sail menghindarkan proyek ini dari "over-engineering" konfigurasi Docker kustom. Kita mendelegasikan pemeliharaan image Docker (Nginx/PHP) sepenuhnya ke standar komunitas Laravel. 
