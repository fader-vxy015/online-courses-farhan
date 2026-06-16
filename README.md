# Online Courses — Admin & Pendaftaran

Ringkasan singkat aplikasi pendaftaran kursus berbasis PHP/MySQL.

**Fitur utama**
- Pendaftaran peserta (frontend)
- Autentikasi peserta & admin
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
- Akses aplikasi di: http://localhost/online-courses/public/
- Admin panel: http://localhost/online-courses/public/admin/dashboard.php
- Halaman Analitik (grafik): http://localhost/online-courses/public/admin/analytics.php

**Admin account**
- Jika belum ada akun admin, tambahkan baris pada tabel `users` secara manual (sesuaikan kolom yang ada). Aplikasi mengharapkan session `admin` setelah login.

**Lokasi file penting**
- Koneksi DB: [src/config/koneksi.php](src/config/koneksi.php)
- Helper/fungsi: [src/includes/functions.php](src/includes/functions.php)
- Dashboard admin: [public/admin/dashboard.php](public/admin/dashboard.php)
- Halaman analitik: [public/admin/analytics.php](public/admin/analytics.php)
- Export Excel: [public/admin/export-excel.php](public/admin/export-excel.php)
- SQL schema: [db_kursus.sql](db_kursus.sql)

**Perubahan penting yang dibuat**
- Form pendaftaran disederhanakan (beberapa field dan upload dihilangkan).
- Handler pendaftaran (`public/kursus-simpan.php`) disesuaikan sesuai flow login.
- Bar chart dipindahkan dari `dashboard.php` ke halaman khusus `analytics.php`.
- Ditambahkan halaman analytics dengan Bar chart (12 bulan terakhir) dan Pie chart (distribusi `jenis_kursus`).
- Sidebar admin menautkan ke `analytics.php`.

**Testing singkat (manual)**
1. Daftarkan peserta melalui form frontend (`/public/kursus-daftar.php`).
2. Login sebagai admin di `/public/admin/login.php`.
3. Buka `/public/admin/analytics.php` — harus menampilkan grafik bar per bulan dan pie distribusi.

**Debug & Troubleshooting**
- Jika muncul error koneksi DB, cek kredensial di [src/config/koneksi.php](src/config/koneksi.php).
- Jika grafik kosong, pastikan kolom `created_at` pada tabel `pendaftar` terisi timestamp saat pendaftaran.
- Jika halaman masih menampilkan chart lama, lakukan hard refresh (Ctrl+F5) untuk menghilangkan cache.

Butuh saya commit perubahan ini ke Git atau bantu buat skrip seed admin?"