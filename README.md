# Online Courses — Admin & Pendaftaran

Aplikasi pendaftaran kursus berbasis PHP/MySQL dengan fitur autentikasi peserta & admin, dashboard, dan analitik.

**Fitur utama**
- Pendaftaran peserta (frontend)
- Autentikasi peserta & admin (login/register)
- Dashboard admin (daftar peserta, hapus)
- Halaman Analitik terpisah dengan grafik Bar (per bulan) dan Pie (distribusi kursus)
- Ekspor data ke Excel

**Prerequisites**
- Windows (recommended) dengan Laragon / XAMPP / WAMP
- PHP 8.x, MySQL/MariaDB
- Browser modern

**Instalasi & setup singkat**
1. Letakkan folder proyek di folder web server (contoh: `C:\laragon\www\online-courses`).
2. Import struktur database:

```sql
-- di MySQL client / phpMyAdmin
SOURCE db_kursus.sql;
```

3. Sesuaikan koneksi database di [src/config/koneksi.php](src/config/koneksi.php).
4. Pastikan folder `uploads/` dapat ditulis oleh web server apabila diperlukan.

**Menjalankan aplikasi**
- Start Laragon / Apache + MySQL.
- Akses aplikasi di: http://localhost/online-courses/
- Halaman pendaftaran: http://localhost/online-courses/src/auth/kursus-daftar.php
- Login peserta: http://localhost/online-courses/src/auth/login.php
- Register peserta: http://localhost/online-courses/src/auth/register.php
- Admin panel: http://localhost/online-courses/src/admin/login.php
- Dashboard admin: http://localhost/online-courses/src/admin/dashboard.php
- Halaman Analitik (grafik): http://localhost/online-courses/src/admin/analytics.php

**Admin account**
- Jika belum ada akun admin, tambahkan baris pada tabel `users` secara manual (sesuaikan kolom yang ada). Aplikasi mengharapkan session `admin` setelah login.

**Lokasi file penting**
- Koneksi DB: [src/config/koneksi.php](src/config/koneksi.php)
- Helper/fungsi: [src/includes/functions.php](src/includes/functions.php)
- Dashboard admin: [src/admin/dashboard.php](src/admin/dashboard.php)
- Halaman analitik: [src/admin/analytics.php](src/admin/analytics.php)
- Export Excel: [src/admin/export-excel.php](src/admin/export-excel.php)
- Login admin: [src/admin/login.php](src/admin/login.php)
- Login peserta: [src/auth/login.php](src/auth/login.php)
- Register peserta: [src/auth/register.php](src/auth/register.php)
- Pendaftaran kursus: [src/auth/kursus-daftar.php](src/auth/kursus-daftar.php)
- Proses pendaftaran: [src/auth/kursus-simpan.php](src/auth/kursus-simpan.php)
- SQL schema: [db_kursus.sql](db_kursus.sql)

**Perubahan penting yang dibuat**
- Restrukturisasi folder: file dipindahkan dari `public/` ke root dan `src/` directories.
- Form pendaftaran disederhanakan (beberapa field dan upload dihilangkan).
- Handler pendaftaran (`src/auth/kursus-simpan.php`) disesuaikan sesuai flow login.
- Bar chart dipindahkan dari `dashboard.php` ke halaman khusus `analytics.php`.
- Ditambahkan halaman analytics dengan Bar chart (12 bulan terakhir) dan Pie chart (distribusi `jenis_kursus`).
- Sidebar admin menautkan ke `analytics.php`.
- Sistem autentikasi peserta (login/register) di `src/auth/`.

**Testing singkat (manual)**
1. Daftarkan peserta melalui form frontend (`/src/auth/kursus-daftar.php`).
2. Login sebagai admin di `/src/admin/login.php`.
3. Buka `/src/admin/analytics.php` — harus menampilkan grafik bar per bulan dan pie distribusi.

**Debug & Troubleshooting**
- Jika muncul error koneksi DB, cek kredensial di [src/config/koneksi.php](src/config/koneksi.php).
- Jika grafik kosong, pastikan kolom `created_at` pada tabel `pendaftar` terisi timestamp saat pendaftaran.
- Jika halaman masih menampilkan chart lama, lakukan hard refresh (Ctrl+F5) untuk menghilangkan cache.