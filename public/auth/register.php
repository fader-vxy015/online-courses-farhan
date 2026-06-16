<?php
session_start();
require_once '../../src/config/koneksi.php';
require_once '../../src/includes/functions.php';

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
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <main class="container auth-section">
    <section class="auth-card">
      <h2>Daftar Akun Peserta</h2>
      <p class="section-subtitle">Buat akun untuk melanjutkan pendaftaran kursus online.</p>

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
          <label for="name">Nama Lengkap</label>
          <input type="text" id="name" name="name" value="<?= $name ?>" required>
        </div>

        <div class="form-row">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" value="<?= $email ?>" required>
        </div>

        <div class="form-row">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
        </div>

        <div class="form-row">
          <label for="password_confirm">Konfirmasi Password</label>
          <input type="password" id="password_confirm" name="password_confirm" required>
        </div>

        <button type="submit" class="button button-primary">Daftar</button>
      </form>

      <p class="text-center">Sudah punya akun? <a href="login.php">Masuk sekarang</a></p>
    </section>
  </main>
</body>
</html>
