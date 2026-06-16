<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "db_kursus";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// (Admin user dummy, bisa dibuat tabel jika perlu)
$admin_user = "farhan";
$admin_pass = "farhan";
?>
