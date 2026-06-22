<?php
session_start();
require_once '../config/koneksi.php';
require_once '../includes/functions.php';

$errors = [];
$name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if (!$name) {
        $errors[] = 'Nama lengkap harus diisi.';
    }
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email tidak valid.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Password minimal 6 karakter.';
    }
    if ($password !== $password_confirm) {
        $errors[] = 'Password dan konfirmasi tidak cocok.';
    }

    if (empty($errors)) {
      // Pastikan tabel users ada (bila belum di-import dari SQL dump)
      $create_sql = "CREATE TABLE IF NOT EXISTS `users` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `nama` varchar(150) NOT NULL,
        `email` varchar(150) NOT NULL,
        `password` varchar(255) NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`),
        UNIQUE KEY `email_unique` (`email`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

      if (!mysqli_query($conn, $create_sql)) {
        $errors[] = 'Gagal inisialisasi tabel users: ' . mysqli_error($conn);
      } else {
        // Cek apakah email sudah terdaftar sebelum insert
        $check_stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? LIMIT 1");
        if ($check_stmt) {
          mysqli_stmt_bind_param($check_stmt, 's', $email);
          mysqli_stmt_execute($check_stmt);
          mysqli_stmt_store_result($check_stmt);
          if (mysqli_stmt_num_rows($check_stmt) > 0) {
            $errors[] = 'Gagal membuat akun. Email sudah terdaftar.';
          }
          mysqli_stmt_close($check_stmt);
        } else {
          $errors[] = 'Gagal menyiapkan pengecekan email: ' . mysqli_error($conn);
        }

        if (empty($errors)) {
          $hash = password_hash($password, PASSWORD_DEFAULT);
          $stmt = mysqli_prepare($conn, "INSERT INTO users (nama, email, password) VALUES (?, ?, ?)");
          if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $hash);
            if (mysqli_stmt_execute($stmt)) {
              mysqli_stmt_close($stmt);
              redirect('login.php');
            } else {
              $errors[] = 'Gagal membuat akun: ' . mysqli_error($conn);
              mysqli_stmt_close($stmt);
            }
          } else {
            $errors[] = 'Gagal menyiapkan query pendaftaran: ' . mysqli_error($conn);
          }
        }
      }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Peserta | SiPintar</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Roboto:wght@400;500&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
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
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* ===== HEADER ===== */
    .header-main {
      background: var(--white);
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .header-main .container {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 15px 0;
    }

    .brand {
      display: inline-flex;
      align-items: center;
      gap: 12px;
      color: var(--primary);
      font-weight: 700;
      font-size: 1.5rem;
      text-decoration: none;
    }

    .brand span {
      color: var(--text);
    }

    .nav-links {
      display: flex;
      gap: 18px;
      flex-wrap: wrap;
      align-items: center;
    }

    .nav-links a {
      text-decoration: none;
      color: var(--gray);
      font-weight: 500;
      transition: color 0.2s ease;
    }

    .nav-links a:hover {
      color: var(--primary);
    }

    /* ===== AUTH WRAPPER ===== */
    .auth-wrapper {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 60px 20px;
    }

    .auth-container {
      display: flex;
      max-width: 960px;
      width: 100%;
      border-radius: 35px;
      overflow: hidden;
      box-shadow: 0 30px 70px rgba(0, 0, 0, 0.25);
      background-color: var(--white);
      animation: fadeInUp 0.8s ease-in-out;
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(40px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* ===== LEFT PANEL (INFO) ===== */
    .auth-panel {
      flex: 1;
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      padding: 60px 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }

    .auth-panel::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -30%;
      width: 300px;
      height: 300px;
      border-radius: 50%;
      background: rgba(255,255,255,0.06);
    }

    .auth-panel::after {
      content: '';
      position: absolute;
      bottom: -20%;
      left: -20%;
      width: 250px;
      height: 250px;
      border-radius: 50%;
      background: rgba(255,255,255,0.05);
    }

    .auth-panel .logo {
      font-size: 28px;
      font-weight: 700;
      color: var(--white);
      margin-bottom: 40px;
      text-transform: uppercase;
      letter-spacing: 3px;
      background: rgba(255,255,255,0.12);
      padding: 12px 24px;
      border-radius: 25px;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255,255,255,0.2);
      display: inline-flex;
      align-items: center;
      gap: 10px;
      position: relative;
      z-index: 1;
    }

    .auth-panel h2 {
      font-size: 36px;
      color: var(--white);
      margin-bottom: 20px;
      font-weight: 700;
      text-shadow: 0 5px 12px rgba(0,0,0,0.5);
      position: relative;
      z-index: 1;
    }

    .auth-panel p {
      font-size: 15px;
      color: rgba(255,255,255,0.9);
      max-width: 90%;
      font-family: 'Roboto', sans-serif;
      text-shadow: 0 2px 6px rgba(0,0,0,0.3);
      position: relative;
      z-index: 1;
      line-height: 1.8;
    }

    .auth-panel .benefits {
      margin-top: 30px;
      display: grid;
      gap: 14px;
      position: relative;
      z-index: 1;
    }

    .auth-panel .benefit-item {
      display: flex;
      align-items: center;
      gap: 12px;
      color: rgba(255,255,255,0.92);
      font-size: 14px;
    }

    .auth-panel .benefit-item i {
      width: 32px;
      height: 32px;
      background: rgba(255,255,255,0.15);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      flex-shrink: 0;
    }

    /* ===== RIGHT PANEL (FORM) ===== */
    .auth-form-section {
      flex: 1.4;
      padding: 60px 50px;
      display: grid;
      gap: 24px;
      background: linear-gradient(135deg, var(--white), #eef2ff);
    }

    .auth-form-section h2 {
      font-size: 28px;
      color: var(--primary-dark);
      text-align: center;
      font-weight: 700;
      position: relative;
      padding-bottom: 16px;
      margin-bottom: 8px;
    }

    .auth-form-section h2::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 60px;
      height: 4px;
      background: linear-gradient(to right, var(--primary), var(--accent));
      border-radius: 2px;
    }

    .auth-form-section .subtitle {
      text-align: center;
      color: var(--gray);
      font-size: 14px;
      margin-bottom: 10px;
    }

    .form-card {
      display: grid;
      gap: 20px;
      padding: 0;
    }

    .form-row {
      display: grid;
      gap: 8px;
    }

    .form-row label {
      display: flex;
      align-items: center;
      gap: 8px;
      color: var(--text);
      font-weight: 600;
      font-size: 14px;
    }

    .form-row label i {
      color: var(--primary);
      width: 20px;
    }

    .form-row input {
      width: 100%;
      border-radius: 14px;
      border: 2px solid var(--border);
      background: var(--gray-light);
      padding: 14px 16px;
      color: var(--text);
      font-size: 15px;
      transition: border-color 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
    }

    .form-row input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 8px rgba(37, 99, 235, 0.2);
      background: var(--white);
    }

    .btn-submit {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: var(--white);
      border: none;
      border-radius: 14px;
      padding: 16px 30px;
      font-weight: 700;
      font-size: 16px;
      cursor: pointer;
      width: 100%;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      margin-top: 8px;
    }

    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 20px 40px rgba(37, 99, 235, 0.25);
    }

    .auth-footer-link {
      text-align: center;
      color: var(--gray);
      font-size: 14px;
    }

    .auth-footer-link a {
      color: var(--primary);
      font-weight: 600;
      text-decoration: none;
    }

    .auth-footer-link a:hover {
      text-decoration: underline;
    }

    /* ===== ALERT ===== */
    .alert {
      border-radius: 14px;
      padding: 16px 20px;
      border: 1px solid rgba(148, 163, 184, 0.2);
      background: #f8fafc;
      font-size: 14px;
    }

    .alert--error {
      border-color: #ef4444;
      background: rgba(239, 68, 68, 0.08);
      color: #991b1b;
    }

    .alert ul {
      margin: 0;
      padding-left: 18px;
    }

    /* ===== FOOTER ===== */
    .footer {
      background: #1e293b;
      color: rgba(255,255,255,0.7);
      text-align: center;
      padding: 24px 0;
      font-size: 13px;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
      .auth-container {
        flex-direction: column;
        border-radius: 24px;
      }

      .auth-panel {
        padding: 40px 30px;
      }

      .auth-panel h2 {
        font-size: 26px;
      }

      .auth-panel p {
        max-width: 100%;
      }

      .auth-form-section {
        padding: 40px 30px;
      }

      .auth-form-section h2 {
        font-size: 24px;
      }
    }
  </style>
</head>
<body>

<!-- HEADER -->
<header class="header-main">
  <div class="container">
    <a href="../../index.php" class="brand"><i class="fas fa-graduation-cap"></i> Si<span>Pintar</span></a>
    <div class="nav-links">
      <a href="../../index.php">Beranda</a>
      <a href="../../courses.php">Kursus</a>
      <a href="login.php">Masuk</a>
    </div>
  </div>
</header>

<!-- AUTH SECTION -->
<div class="auth-wrapper">
  <div class="auth-container">
    <!-- Left Panel -->
    <div class="auth-panel">
      <div class="logo"><i class="fas fa-graduation-cap"></i> SiPintar</div>
      <h2>Mulai Perjalanan Belajar</h2>
      <p>Daftar sekarang dan dapatkan akses ke ratusan kursus online berkualitas dengan instruktur berpengalaman.</p>
      <div class="benefits">
        <div class="benefit-item">
          <i class="fas fa-check"></i>
          <span>Akses ke semua kursus premium</span>
        </div>
        <div class="benefit-item">
          <i class="fas fa-check"></i>
          <span>Belajar dengan video berkualitas HD</span>
        </div>
        <div class="benefit-item">
          <i class="fas fa-check"></i>
          <span>Dapatkan sertifikat resmi</span>
        </div>
        <div class="benefit-item">
          <i class="fas fa-check"></i>
          <span>Dukungan instruktur 24/7</span>
        </div>
      </div>
    </div>

    <!-- Right Panel (Form) -->
    <div class="auth-form-section">
      <h2>Daftar Akun Peserta</h2>
      <p class="subtitle">Isi data diri Anda untuk membuat akun baru.</p>

      <?php if (!empty($errors)): ?>
        <div class="alert alert--error">
          <ul>
            <?php foreach ($errors as $error): ?>
              <li><?= $error ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="POST" class="form-card">
        <div class="form-row">
          <label for="name"><i class="fas fa-user"></i> Nama Lengkap</label>
          <input type="text" id="name" name="name" value="<?= $name ?>" placeholder="Masukkan nama lengkap Anda" required>
        </div>

        <div class="form-row">
          <label for="email"><i class="fas fa-envelope"></i> Email</label>
          <input type="email" id="email" name="email" value="<?= $email ?>" placeholder="Masukkan email Anda" required>
        </div>

        <div class="form-row">
          <label for="password"><i class="fas fa-lock"></i> Password</label>
          <input type="password" id="password" name="password" placeholder="Buat password (min. 6 karakter)" required>
        </div>

        <div class="form-row">
          <label for="password_confirm"><i class="fas fa-lock"></i> Konfirmasi Password</label>
          <input type="password" id="password_confirm" name="password_confirm" placeholder="Ulangi password Anda" required>
        </div>

        <button type="submit" class="btn-submit"><i class="fas fa-user-plus"></i> Daftar</button>
      </form>

      <p class="auth-footer-link">Sudah punya akun? <a href="login.php">Masuk sekarang <i class="fas fa-arrow-right"></i></a></p>
    </div>
  </div>
</div>

<!-- FOOTER -->
<footer class="footer">
  <div class="container">
    &copy; 2025 SiPintar. All rights reserved.
  </div>
</footer>

</body>
</html>