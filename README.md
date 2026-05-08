# SIMONEL — Sistem Monitoring Panel

SIMONEL adalah aplikasi dashboard monitoring berbasis IoT untuk memantau data panel listrik secara realtime. Aplikasi ini dibangun menggunakan Native PHP dengan arsitektur MVC, Tailwind CSS, dan Alpine.js.

## Fitur Utama

- **Monitoring Realtime**: Pantau Tegangan (V), Arus (A), Daya (W), Daya Semu (VA), Daya Reaktif (VAR), Frekuensi (Hz), dan Power Factor (PF) secara langsung.
- **Dashboard Statistik**: Ringkasan jumlah perangkat, status online/offline, dan total beban daya harian.
- **Riwayat Data (Logs)**: Catatan histori data sensor dari seluruh perangkat yang terdaftar.
- **Manajemen Perangkat**: Kelola perangkat IoT Anda dengan sistem API Key yang aman.
- **Autentikasi**: Sistem login dan manajemen profil pengguna.

## Teknologi Stack

- **Backend**: Native PHP (MVC Pattern)
- **Frontend**: Tailwind CSS, Alpine.js, Chart.js
- **Database**: MySQL / MariaDB
- **Icons**: Boxicons
- **Fonts**: Outfit

## Persiapan Instalasi

1. Clone repository ini:
   ```bash
   git clone <repository-url>
   ```
2. Impor database `simonel.sql` ke MySQL Anda.
3. Salin file konfigurasi:
   ```bash
   cp config/config.sample.php config/config.php
   ```
4. Sesuaikan `config/config.php` dengan database dan URL lokal Anda.
5. Akses melalui browser (misal: `http://localhost/simonel`).

## API Payload

Perangkat IoT dapat mengirim data menggunakan method **POST** ke endpoint `/api/send`:

```json
{
  "api_key": "YOUR_API_KEY",
  "voltage": 220.5,
  "current": 1.25,
  "power": 275.6,
  "energy": 12.45,
  "frequency": 50,
  "pf": 0.98
}
```

---
*SIMONEL — Solusi Cerdas Monitoring Energi.*
