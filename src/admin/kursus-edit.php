<?php
session_start();
if (!isset($_SESSION["admin"])) {
  header("Location: login.php");
  exit;
}

require_once '../config/koneksi.php';
require_once '../includes/functions.php';

// Validasi ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("ID tidak valid.");
}

$data = mysqli_query($conn, "SELECT * FROM pendaftar WHERE id=$id");
$row = mysqli_fetch_assoc($data);
if (!$row) {
    die("Data tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Data Pendaftar</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f6f8;
      padding: 50px;
      margin: 0;
    }

    .container {
      max-width: 600px;
      margin: auto;
      background-color: #fff;
      border-radius: 12px;
      padding: 30px 40px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      color: #2563eb;
      margin-bottom: 30px;
    }

    label {
      font-weight: bold;
      display: block;
      margin-top: 20px;
      margin-bottom: 6px;
    }

    input[type="text"],
    input[type="email"],
    select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
      background-color: #f9f9f9;
    }

    input:focus,
    select:focus {
      border-color: #2563eb;
      background-color: #fff;
      outline: none;
    }

    button {
      margin-top: 30px;
      background-color: #2563eb;
      color: white;
      padding: 12px 20px;
      font-size: 16px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      width: 100%;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #1d4ed8;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Edit Data Pendaftar</h2>
  <form action="kursus-update.php" method="POST">
    <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">

    <label for="nama">Nama:</label>
    <input type="text" name="nama" id="nama" value="<?= htmlspecialchars($row['nama']) ?>" required>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" value="<?= htmlspecialchars($row['email']) ?>" required>

    <label for="wa">No. WA:</label>
    <input type="text" name="wa" id="wa" value="<?= htmlspecialchars($row['wa']) ?>" required>

    <label for="jenis_kursus">Jenis Kursus:</label>
    <select type="text" name="jenis_kursus" id="jenis_kursus" required>
      <option value="Data Analyst" <?= $row['jenis_kursus'] == 'Data Analyst' ? 'selected' : '' ?>>Data Analyst</option>
      <option value="Desain Grafis" <?= $row['jenis_kursus'] == 'Desain Grafis' ? 'selected' : '' ?>>Desain Grafis</option>
      <option value="Digital Marketing" <?= $row['jenis_kursus'] == '09:00' ? 'selected' : '' ?>>Digital Marketing</option>
      <option value="Machine Learning" <?= $row['jenis_kursus'] == '15:00' ? 'selected' : '' ?>>Machine Learning</option>
      <option value="Microsoft Excel" <?= $row['jenis_kursus'] == '15:00' ? 'selected' : '' ?>>Microsoft Excel</option>
       <option value="Microsoft Word" <?= $row['jenis_kursus'] == '15:00' ? 'selected' : '' ?>>Microsoft Word</option>
  </select>

    <label for="hari_kursus">Hari Kursus:</label>
    <input type="text" name="hari_kursus" id="hari_kursus" value="<?= htmlspecialchars($row['hari_kursus']) ?>" required>

    <label for="jam_kursus">Jam Kursus:</label>
    <select name="jam_kursus" id="jam_kursus" required>
      <option value="09:00" <?= $row['jam_kursus'] == '09:00' ? 'selected' : '' ?>>09:00 Pagi</option>
      <option value="15:00" <?= $row['jam_kursus'] == '15:00' ? 'selected' : '' ?>>15:00 Sore</option>
    </select>

    <button type="submit" name="submit">Update</button>
  </form>
</div>

</body>
</html>
