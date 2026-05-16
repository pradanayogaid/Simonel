# SIMONEL - Sistem Monitoring Panel

**Versi:** v1.1.0

**Status:** rilis publik pertama untuk staging/internal deployment

SIMONEL adalah aplikasi dashboard monitoring berbasis IoT untuk memantau data panel listrik atau PLTS on-grid secara realtime. Aplikasi ini dibangun menggunakan Native PHP dengan pola MVC sederhana, MySQL/MariaDB, Tailwind CSS, Alpine.js, dan Chart.js.

## Fitur Utama

- **Monitoring realtime**: pantau tegangan, arus, daya nyata, daya semu, dan daya reaktif dari perangkat IoT.
- **Dashboard statistik**: ringkasan jumlah perangkat, status online/offline, total daya, efisiensi sistem, dan peak power harian.
- **Riwayat data sensor**: melihat log sensor berdasarkan perangkat dan waktu.
- **Manajemen perangkat**: tambah, edit, hapus perangkat, serta kelola API key perangkat.
- **Export data**: preview data, export CSV, dan generate PDF dari rentang tanggal tertentu.
- **Autentikasi dan role user**: login, register, profil, manajemen user, role admin/user.
- **Keamanan form**: proteksi CSRF untuk aksi POST dan delete.
- **Session timeout**: auto logout setelah 30 menit tidak aktif.

## Teknologi

- **Backend:** Native PHP 8.x
- **Arsitektur:** MVC sederhana
- **Database:** MySQL / MariaDB
- **Frontend:** Tailwind CSS, Alpine.js, Chart.js
- **UI library:** SweetAlert2, Boxicons
- **Font:** Outfit

## Struktur Project

```text
simonel/
+-- app/
|   +-- controllers/
|   +-- core/
|   +-- models/
|   +-- views/
+-- config/
|   +-- config.sample.php
+-- public/
|   +-- index.php
+-- simonel.sql
+-- README.md
```

## Instalasi Lokal / Staging

1. Clone repository:

   ```bash
   git clone https://github.com/pradanayogaid/Simonel.git
   cd Simonel
   ```

2. Import database:

   ```bash
   mysql -u root -p < simonel.sql
   ```

   Atau import `simonel.sql` melalui phpMyAdmin.

3. Salin file konfigurasi:

   ```bash
   cp config/config.sample.php config/config.php
   ```

4. Sesuaikan konfigurasi database dan base URL di `config/config.php`.

5. Pastikan web server mengarah ke folder project dan URL rewrite aktif.

6. Akses aplikasi, contoh:

   ```text
   http://localhost/simonel
   ```

## Konfigurasi URL Rewrite

File `.htaccess` tidak disertakan dalam repository ini. Untuk Apache, aktifkan `mod_rewrite`, lalu gunakan konfigurasi rewrite berikut sesuai lokasi deploy.

Root project:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^$ public/ [L]
    RewriteRule (.*) public/$1 [L]
</IfModule>
```

Folder `public/`:

```apache
<IfModule mod_rewrite.c>
    Options -Multiviews
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
</IfModule>
```

Untuk Nginx, arahkan document root ke folder `public/`, lalu gunakan fallback ke `index.php`.

Contoh:

```nginx
location / {
    try_files $uri $uri/ /index.php?url=$uri&$query_string;
}
```

## Migrasi Database Versi Lama

Jika database sudah pernah dibuat dari skema lama yang masih memakai kolom `power`, `apparent_power`, dan `reactive_power`, jalankan:

```bash
php alter_db_final.php
```

Skrip tersebut akan menyelaraskan tabel `sensor_logs` ke kolom:

- `daya_nyata`
- `daya_semu`
- `daya_reaktif`

Untuk instalasi baru, cukup import `simonel.sql`.

## API Perangkat IoT

Perangkat IoT mengirim data sensor menggunakan method **POST** ke endpoint:

```text
/api/send
```

Header:

```http
Content-Type: application/json
```

Payload:

```json
{
  "api_key": "YOUR_API_KEY",
  "voltage": 220.5,
  "current": 1.25,
  "daya_nyata": 275.6,
  "daya_semu": 310.2,
  "daya_reaktif": 140.5
}
```

Response sukses:

```json
{
  "status": "success",
  "message": "Data recorded successfully"
}
```

Endpoint baca data realtime:

```text
/api/fetch?api_key=YOUR_API_KEY
```

Endpoint riwayat grafik:

```text
/api/history?api_key=YOUR_API_KEY
/api/history?api_key=YOUR_API_KEY&date=YYYY-MM-DD
```

## Catatan Keamanan Staging

Sebelum deploy ke staging atau server publik:

- Salin `config/config.sample.php` menjadi `config/config.php`, lalu isi credential server.
- Jangan commit `config/config.php`.
- Ganti password default admin setelah import database.
- Batasi akses file utilitas root seperti `check_db.php`, `list_users.php`, `test_api.php`, dan `test_curl.php` jika file tersebut ikut berada di server.
- Pastikan directory listing web server nonaktif.
- Gunakan HTTPS untuk staging/production.
- Jika aplikasi dibuka ke publik, tambahkan rate limiting untuk endpoint login dan API.

## Default Login

Database awal menyediakan user admin contoh untuk mempermudah setup awal. Setelah login pertama, segera ubah password admin melalui menu profile.

## Verifikasi Cepat

Jalankan syntax check PHP:

```bash
php -l public/index.php
```

Atau cek semua file PHP:

```bash
find . -name "*.php" -print -exec php -l {} \;
```

Di Windows PowerShell:

```powershell
Get-ChildItem -Recurse -Filter *.php | ForEach-Object { php -l $_.FullName }
```

## Rilis v1.1.0

Rilis ini menyiapkan SIMONEL untuk staging/internal deployment dengan perubahan utama:

- Sinkronisasi skema database sensor ke kolom `daya_*`.
- Proteksi CSRF untuk form dan aksi delete.
- Delete device memakai POST dan membersihkan log terkait.
- Header JSON untuk endpoint API.
- Perbaikan dokumentasi API perangkat IoT.
- Panduan rewrite server tanpa menyertakan `.htaccess` di repository.

---

SIMONEL - Solusi monitoring energi listrik berbasis IoT.
