<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}

require_once '../config/koneksi.php';
require_once '../includes/functions.php';

// Periksa apakah ID tersedia
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Jalankan query hapus
    $query = "DELETE FROM pendaftar WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data berhasil dihapus'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('ID tidak ditemukan'); window.history.back();</script>";
}
?>
