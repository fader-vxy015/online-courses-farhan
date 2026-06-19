<?php
session_start();
require_once '../config/koneksi.php';
require_once '../includes/functions.php';

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email tidak valid.';
    }
    if (!$password) {
        $errors[] = 'Password harus diisi.';
    }

    if (empty($errors)) {
        $stmt = mysqli_prepare($conn, "SELECT id, nama, password FROM users WHERE email = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $name, $hash);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if (!empty($id) && password_verify($password, $hash)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;
            redirect('../kursus-daftar.php');
        } else {
            $errors[] = 'Email atau password salah.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Peserta | SiPintar</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <main class="container auth-section">
    <section class="auth-card">
      <h2>Login Peserta</h2>
      <p class="section-subtitle">Masuk dengan email yang Anda daftarkan untuk melanjutkan pendaftaran kursus.</p>

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
          <label for="email">Email</label>
          <input type="email" id="email" name="email" value="<?= $email ?>" required>
        </div>

        <div class="form-row">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
        </div>

        <button type="submit" class="button button-primary">Masuk</button>
      </form>

      <p class="text-center">Belum punya akun? <a href="register.php">Daftar sekarang</a></p>
    </section>
  </main>
</body>
</html>
