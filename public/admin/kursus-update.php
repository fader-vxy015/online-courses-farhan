<?php
session_start();
if (!isset($_SESSION["admin"])) {
  header("Location: login.php");
  exit;
}

require_once '../../src/config/koneksi.php';
require_once '../../src/includes/functions.php';

$berhasil = false;
$error_message = "";

// Ambil ID
$id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['id']) ? intval($_POST['id']) : 0);
$nama = $email = $wa = $jenis_kursus = $hari_kursus = $jam_kursus = "";

// Ambil data lama
if ($_SERVER["REQUEST_METHOD"] === "GET" && $id > 0) {
    $result = mysqli_query($conn, "SELECT * FROM pendaftar WHERE id=$id");
    if ($data = mysqli_fetch_assoc($result)) {
        $nama = $data['nama'];
        $email = $data['email'];
        $wa = $data['wa'];
        $jenis_kursus = $data['jenis_kursus'];
        $hari_kursus = $data['hari_kursus'];
        $jam_kursus = $data['jam_kursus'];
    } else {
        $error_message = "Data tidak ditemukan.";
    }
}

// Proses update data
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $wa = $_POST['wa'];
    $jenis_kursus = $_POST['jenis_kursus'];
    $hari_kursus = $_POST['hari_kursus'];
    $jam_kursus = $_POST['jam_kursus'];

    // Validasi jam
    if (!in_array($jam_kursus, ['09:00', '15:00'])) {
        $error_message = "Jam kursus tidak valid.";
    } else {
        $query = "UPDATE pendaftar SET 
            nama='$nama', 
            email='$email', 
            wa='$wa', 
            jenis_kursus='$jenis_kursus', 
            hari_kursus='$hari_kursus',
            jam_kursus='$jam_kursus'
            WHERE id=$id";

        if (mysqli_query($conn, $query)) {
            $berhasil = true;
        } else {
            $error_message = "Gagal update data: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Data Pendaftar</title>
  <link rel="stylesheet" href="style.css">
  <style>
    :root {
      --primary: #2563eb;
      --primary-dark: #1d4ed8;
      --bg: #f3f4f6;
      --white: #ffffff;
      --gray-light: #f9fafb;
      --gray: #6b7280;
      --text: #111827;
    }

    body {
      background-color: var(--bg);
      font-family: 'Segoe UI', sans-serif;
      padding: 80px 20px;
    }

    .container {
      background-color: var(--white);
      border-radius: 16px;
      padding: 40px 30px;
      max-width: 600px;
      margin: auto;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      animation: fadeIn 0.8s ease;
    }

    h2 {
      color: var(--primary);
      text-align: center;
      margin-bottom: 20px;
    }

    label {
      display: block;
      font-weight: 600;
      margin-top: 20px;
      margin-bottom: 6px;
    }

    input[type="text"],
    input[type="email"],
    select {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 8px;
      background-color: var(--gray-light);
    }

    .btn {
      background-color: var(--primary);
      color: white;
      padding: 14px;
      margin-top: 30px;
      width: 100%;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .btn:hover {
      background-color: var(--primary-dark);
    }

    .success-message,
    .error-message {
      padding: 16px;
      margin-top: 20px;
      border-radius: 8px;
      font-weight: bold;
    }

    .success-message {
      background-color: #d4edda;
      color: #155724;
    }

    .error-message {
      background-color: #f8d7da;
      color: #721c24;
    }

    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(20px);}
      to {opacity: 1; transform: translateY(0);}
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Form Edit Data</h2>

  <?php if ($berhasil): ?>
    <div class="success-message">
      Data berhasil diupdate. <a href="dashboard.php" class="btn" style="display:inline-block;margin-top:10px;">Lihat Data</a>
    </div>
  <?php elseif (!empty($error_message)): ?>
    <div class="error-message"><?= $error_message ?></div>
  <?php endif; ?>

  <?php if (!$berhasil): ?>
  <form method="POST" action="">
    <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

    <label for="nama">Nama:</label>
    <input type="text" name="nama" id="nama" value="<?= htmlspecialchars($nama) ?>" required>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>" required>

    <label for="wa">No. WA:</label>
    <input type="text" name="wa" id="wa" value="<?= htmlspecialchars($wa) ?>" required>

    <label for="jenis_kursus">Jenis Kursus:</label>
    <select type="text" name="jenis_kursus" id="jenis_kursus" required>
      <option value="Data Analyst" <?= $jam_kursus === 'Data Analyst' ? 'selected' : '' ?>>Data Analyst</option>
      <option value="Desain Grafis" <?= $jam_kursus === 'Desain Grafis' ? 'selected' : '' ?>>Desain Grafis</option>
      <option value="Digital Marketing" <?= $jam_kursus === 'Digital Marketing' ? 'selected' : '' ?>>Digital Marketing</option>
      <option value="Machine Learning" <?= $jam_kursus === 'Machine Learning' ? 'selected' : '' ?>>Machine Learning</option>
      <option value="Microsoft Excel" <?= $jam_kursus === 'Microsoft Excel' ? 'selected' : '' ?>>Microsoft Excel</option>
      <option value="Microsoft Word" <?= $jam_kursus === 'Microsoft Word' ? 'selected' : '' ?>>Microsoft Word</option>
    </select>

    <label for="hari_kursus">Hari Kursus:</label>
    <input type="text" name="hari_kursus" id="hari_kursus" value="<?= htmlspecialchars($hari_kursus) ?>" required>

    <label for="jam_kursus">Jam Kursus:</label>
    <select name="jam_kursus" id="jam_kursus" required>
      <option value="09:00" <?= $jam_kursus === '09:00' ? 'selected' : '' ?>>09:00 Pagi</option>
      <option value="15:00" <?= $jam_kursus === '15:00' ? 'selected' : '' ?>>15:00 Sore</option>
    </select>

    <button type="submit" name="submit" class="btn">Update Data</button>
  </form>
  <?php endif; ?>
</div>

</body>
</html>
