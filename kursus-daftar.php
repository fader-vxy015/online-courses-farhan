<?php
require_once 'src/config/koneksi.php';
require_once 'src/includes/functions.php';

if (empty($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}

$user_id = currentUserId();
$user_name = currentUserName();
$user_email = '';

if ($user_id) {
    $stmt = mysqli_prepare($conn, "SELECT nama, email FROM users WHERE id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $db_name, $db_email);
    if (mysqli_stmt_fetch($stmt)) {
        $user_name = $db_name;
        $user_email = $db_email;
    }
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Form Pendaftaran Kursus Online</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Roboto:wght@400;500&display=swap" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <style>
    :root {
      --primary: #2563eb;
      --primary-dark: #1d4ed8;
      --bg: #f3f4f6;
      --white: #ffffff;
      --gray-light: #f9fafb;
      --gray: #6b7280;
      --text: #111827;
      --border: #e5e7eb;
      --accent: #9333ea;
      --glass-bg: rgba(255, 255, 255, 0.1);
      --glass-border: rgba(255, 255, 255, 0.2);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, var(--bg), #e0e7ff);
      line-height: 1.6;
      overflow-x: hidden;
      position: relative;
    }

    .form-wrapper {
      display: flex;
      max-width: 1400px;
      margin: 60px auto;
      border-radius: 35px;
      overflow: hidden;
      box-shadow: 0 30px 70px rgba(0, 0, 0, 0.3);
      background-color: var(--white);
      animation: fadeInUp 1s ease-in-out;
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(40px); }
      to { opacity: 1; translateY(0); }
    }

    .form-info {
      flex: 1;
      position: relative;
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      overflow: hidden;
      padding: 60px;
    }

    .form-info .logo {
      font-size: 32px;
      font-weight: 700;
      color: var(--white);
      margin-bottom: 50px;
      text-transform: uppercase;
      letter-spacing: 4px;
      background: var(--glass-bg);
      padding: 15px 30px;
      border-radius: 25px;
      backdrop-filter: blur(10px);
      border: 1px solid var(--glass-border);
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .form-info::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.4);
      z-index: 2;
    }

    .form-info h2 {
      font-size: 42px;
      color: var(--white);
      margin-bottom: 30px;
      z-index: 3;
      font-weight: 700;
      text-shadow: 0 5px 12px rgba(0, 0, 0, 0.7);
    }

    .form-info p {
      font-size: 16px;
      color: var(--white);
      max-width: 90%;
      z-index: 3;
      font-family: 'Roboto', sans-serif;
      text-shadow: 0 2px 6px rgba(0, 0, 0, 0.5);
    }

    .form-info .promo-text {
      font-size: 18px;
      color: var(--white);
      z-index: 3;
      margin-top: 20px;
      padding: 15px;
      background: rgba(255, 255, 255, 0.15);
      border-radius: 15px;
      text-align: center;
      line-height: 1.8;
    }

    .form-section {
      flex: 1.6;
      padding: 80px 100px;
      display: grid;
      gap: 25px;
      background: linear-gradient(135deg, var(--white), #e6efff);
      position: relative;
    }

    .form-section h2 {
      font-size: 36px;
      color: var(--primary-dark);
      margin-bottom: 25px;
      text-align: center;
      font-weight: 700;
      position: relative;
    }

    .form-section h2::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 70px;
      height: 5px;
      background: linear-gradient(to right, var(--primary), var(--accent));
      border-radius: 3px;
    }

    .info-banner {
      background: rgba(37, 99, 235, 0.1);
      color: var(--primary-dark);
      border: 1px solid rgba(37, 99, 235, 0.2);
      border-radius: 16px;
      padding: 18px 22px;
      margin-bottom: 20px;
      font-weight: 600;
      text-align: center;
    }

    .field-note {
      display: block;
      margin-top: 8px;
      color: var(--gray);
      font-size: 0.95rem;
      line-height: 1.5;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      background: var(--white);
      padding: 25px;
      border-radius: 20px;
      border: 2px solid var(--border);
      position: relative;
      transition: box-shadow 0.3s ease;
    }

    .form-group:hover {
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    label {
      font-weight: 600;
      color: var(--text);
      margin-bottom: 10px;
      font-size: 16px;
      display: flex;
      align-items: center;
      gap: 10px;
      position: relative;
    }

    label i {
      color: var(--gray);
    }

    label .tooltip {
      position: absolute;
      top: -30px;
      left: 0;
      background: var(--primary-dark);
      color: var(--white);
      padding: 5px 10px;
      border-radius: 5px;
      font-size: 12px;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
      white-space: nowrap;
    }

    label:hover .tooltip {
      opacity: 1;
      visibility: visible;
      top: -40px;
    }

    input[type="text"],
    input[type="email"],
    input[type="time"],
    input[type="file"],
    select,
    textarea {
      width: 100%;
      padding: 16px 20px;
      margin-top: 5px;
      background-color: var(--gray-light);
      border: 2px solid var(--border);
      border-radius: 15px;
      font-size: 15px;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    input:focus,
    select:focus,
    textarea:focus {
      border-color: var(--primary);
      box-shadow: 0 0 8px rgba(37, 99, 235, 0.2);
      outline: none;
      background-color: var(--white);
    }

    textarea {
      resize: vertical;
      min-height: 140px;
    }

    .monospace {
      font-family: 'Courier New', Courier, monospace;
      font-size: 15px;
      background-color: #f1f5f9;
    }

    .checkbox-group {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      margin-top: 10px;
    }

    .checkbox-group label {
      font-weight: 500;
      font-size: 15px;
      color: var(--gray);
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 15px;
      border-radius: 12px;
      background: var(--gray-light);
      transition: background-color 0.3s ease;
    }

    .checkbox-group label:hover {
      background-color: #f1f5f9;
    }

    .checkbox-group input[type="checkbox"] {
      margin: 0;
      accent-color: var(--primary);
      transform: scale(1.4);
    }

    .progress-bar {
      width: 100%;
      height: 10px;
      background: var(--gray-light);
      border-radius: 5px;
      overflow: hidden;
      margin-top: 20px;
      position: relative;
    }

    .progress {
      height: 100%;
      background: linear-gradient(to right, var(--primary), var(--accent));
      width: 0;
      transition: width 0.5s ease;
    }

    .button-group {
      display: flex;
      gap: 25px;
      margin-top: 15px;
    }

    button[type="submit"],
    button[type="reset"],
    button[type="button"] {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: var(--white);
      border: none;
      border-radius: 15px;
      padding: 16px 30px;
      font-weight: 600;
      font-size: 16px;
      cursor: pointer;
      width: 100%;
      transition: background-color 0.3s ease, transform 0.3s ease;
    }

    button[type="reset"] {
      background: linear-gradient(135deg, var(--gray), #4b5563);
    }

    button[type="button"] {
      background: linear-gradient(135deg, var(--accent), #7e22ce);
    }

    button[type="submit"]:hover,
    button[type="reset"]:hover,
    button[type="button"]:hover {
      background-color: #1d4ed8;
      transform: translateY(-2px);
    }

    @media (max-width: 992px) {
      .form-wrapper {
        flex-direction: column;
      }

      .form-section {
        padding: 60px 70px;
      }

      .form-info {
        height: 450px;
        padding: 50px;
      }

      .form-info h2 {
        font-size: 30px;
      }

      .form-info p {
        font-size: 14px;
      }
    }

    @media (max-width: 600px) {
      .form-section {
        padding: 40px 30px;
        gap: 20px;
      }

      .form-info {
        height: 350px;
        padding: 30px;
      }

      .form-info h2 {
        font-size: 26px;
      }

      .form-info p {
        font-size: 13px;
      }

      .form-group {
        padding: 20px;
      }

      .button-group {
        flex-direction: column;
        gap: 20px;
      }

      button[type="submit"],
      button[type="reset"],
      button[type="button"] {
        padding: 14px 25px;
      }
    }
  </style>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const inputs = document.querySelectorAll('input, select, textarea');
      let progress = 0;
      const progressBar = document.querySelector('.progress');

      inputs.forEach(input => {
        input.addEventListener('change', () => {
          const filledInputs = document.querySelectorAll('input[required]:not(:placeholder-shown), select[required]:not(:placeholder-shown), textarea[required]:not(:placeholder-shown)').length;
          const totalRequired = document.querySelectorAll('[required]').length;
          progress = (filledInputs / totalRequired) * 100;
          progressBar.style.width = `${progress}%`;
        });
      });
    });
  </script>
</head>
<body>

<div class="form-wrapper">
  <div class="form-info">
    <div class="logo">SiPintar <i class="fas fa-graduation-cap"></i></div>
    <h2>Belajar Tanpa Batas</h2>
    <p>Wujudkan impianmu dengan kursus online terdepan di era digital!</p>
    <div class="promo-text">
      <p><strong>Promo Spesial:</strong> Daftar sebelum 31 Juli 2025 dan dapatkan diskon 20%!</p>
      <p>Kelas mulai setiap Senin, lengkap dengan sertifikat resmi.</p>
    </div>
  </div>

  <div class="form-section">
    <div class="progress-bar">
      <div class="progress"></div>
    </div>
    <form action="kursus-simpan.php" method="POST" enctype="multipart/form-data">
      <h2>Form Pendaftaran Kursus</h2>

      <div class="info-banner">
        Anda sudah masuk sebagai <strong><?= htmlspecialchars($user_email) ?></strong>. Data akun Anda akan digunakan pada pendaftaran kursus.
      </div>

      <div class="form-group">
        <label for="nama"><i class="fas fa-user"></i> Nama Lengkap</label>
        <input type="text" name="nama" id="nama" value="<?= htmlspecialchars($user_name) ?>" readonly>
        <span class="field-note">Nama akun Anda akan digunakan sebagai data pendaftar.</span>
      </div>

      <div class="form-group">
        <label for="email"><i class="fas fa-envelope"></i> Email</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($user_email) ?>" readonly>
        <span class="field-note">Email akun sudah terdaftar dan tidak perlu diubah di halaman ini.</span>
      </div>

      <div class="form-group">
        <label for="wa"><i class="fas fa-phone"></i> Nomor WhatsApp <span class="tooltip">Tanpa spasi atau tanda +</span></label>
        <input type="text" name="wa" id="wa" required placeholder="Masukkan nomor WA">
      </div>

      <div class="form-group">
        <label for="jenis_kursus"><i class="fas fa-book"></i> Jenis Kursus <span class="tooltip">Pilih sesuai minat</span></label>
        <select name="jenis_kursus" id="jenis_kursus" required>
          <option value="">Pilih Kursus</option>
          <option value="Desain Grafis">Desain Grafis</option>
          <option value="Data Analyst">Data Analyst</option>
          <option value="Digital Marketing">Digital Marketing</option>
          <option value="Machine Learning">Machine Learning</option>
          <option value="Microsoft Excel">Microsoft Excel</option>
          <option value="Microsoft Word">Microsoft Word</option>
        </select>
      </div>

      <div class="form-group">
        <label for="jenjang"><i class="fas fa-level-up-alt"></i> Jenjang <span class="tooltip">Sesuaikan dengan kemampuan</span></label>
        <select name="jenjang" id="jenjang" required>
          <option value="">Pilih Jenjang</option>
          <option value="Pemula">Pemula</option>
          <option value="Menengah">Menengah</option>
          <option value="Lanjutan">Lanjutan</option>
        </select>
      </div>

      <!-- Untuk kursus full online, beberapa field dihilangkan untuk menyederhanakan pendaftaran -->

      <div class="form-group">
        <label for="referensi"><i class="fas fa-info-circle"></i> Referensi Kursus <span class="tooltip">Contoh: teman, iklan</span></label>
        <input type="text" name="referensi" id="referensi" placeholder="Darimana Anda tahu kursus ini?">
      </div>

      <!-- Foto, motivasi, pengalaman, metode, jam, hari, alamat dihilangkan untuk kursus online berbasis video+modul -->

      <div class="form-group">
        <label for="status_pekerjaan"><i class="fas fa-briefcase"></i> Status Pekerjaan <span class="tooltip">Pilih status saat ini</span></label>
        <select name="status_pekerjaan" id="status_pekerjaan" required>
          <option value="">Pilih Status</option>
          <option value="Pelajar">Pelajar</option>
          <option value="Mahasiswa">Mahasiswa</option>
          <option value="Freelancer">Freelancer</option>
          <option value="Karyawan Swasta">Karyawan Swasta</option>
          <option value="PNS">PNS</option>
          <option value="Wiraswasta">Wiraswasta</option>
          <option value="Tidak Bekerja">Tidak Bekerja</option>
          <option value="Lainnya">Lainnya</option>
        </select>
      </div>

      <div class="form-group">
        <label for="pendidikan_terakhir"><i class="fas fa-graduation-cap"></i> Pendidikan Terakhir <span class="tooltip">Pilih tingkat terakhir</span></label>
        <select name="pendidikan_terakhir" id="pendidikan_terakhir" required>
          <option value="">Pilih Pendidikan</option>
          <option value="SD">SD</option>
          <option value="SMP">SMP</option>
          <option value="SMA/SMK">SMA/SMK</option>
          <option value="Diploma">Diploma</option>
          <option value="Sarjana (S1)">Sarjana (S1)</option>
          <option value="Magister (S2)">Magister (S2)</option>
          <option value="Doktor (S3)">Doktor (S3)</option>
        </select>
      </div>

      <div class="button-group">
        <button type="submit" name="submit" class="button button-primary">Daftar Sekarang</button>
        <button type="reset" class="button button-secondary">Reset Form</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>