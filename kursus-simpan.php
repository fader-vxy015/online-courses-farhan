<?php
session_start();
require_once 'src/config/koneksi.php';
require_once 'src/includes/functions.php';

if (empty($_SESSION['user_id'])) {
    redirect('auth/login.php');
}

$user_id = currentUserId();
$nama = '';
$email = '';
if ($user_id) {
    $stmt = mysqli_prepare($conn, "SELECT nama, email FROM users WHERE id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $db_name, $db_email);
    if (mysqli_stmt_fetch($stmt)) {
        $nama = $db_name;
        $email = $db_email;
    }
    mysqli_stmt_close($stmt);
}

if (!$nama || !$email) {
    redirect('auth/login.php');
}

$errors = [];
$berhasil = false;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('kursus-daftar.php');
}

// Hapus/abaikan field yang tidak diperlukan untuk kursus full-online
$wa = sanitize($_POST['wa'] ?? '');
$alamat = ''; // tidak digunakan
$jenis_kursus = sanitize($_POST['jenis_kursus'] ?? '');
$jenjang = sanitize($_POST['jenjang'] ?? '');
$hari_kursus = ''; // tidak digunakan
$jam_kursus = ''; // tidak digunakan
$metode_belajar = ''; // tidak digunakan
$pengalaman = ''; // tidak digunakan
$referensi = sanitize($_POST['referensi'] ?? '');
$motivasi = ''; // tidak digunakan
$status_pekerjaan = sanitize($_POST['status_pekerjaan'] ?? '');
$pendidikan_terakhir = sanitize($_POST['pendidikan_terakhir'] ?? '');
$foto_name = '';

if (!$wa) {
    $errors[] = 'Nomor WhatsApp harus diisi.';
}
if (!$jenis_kursus) {
    $errors[] = 'Pilih jenis kursus.';
}
if (!$jenjang) {
    $errors[] = 'Pilih jenjang kursus.';
}
if (!$status_pekerjaan) {
    $errors[] = 'Pilih status pekerjaan.';
}
if (!$pendidikan_terakhir) {
    $errors[] = 'Pilih pendidikan terakhir.';
}

if (empty($errors)) {
    $stmt = mysqli_prepare($conn, "INSERT INTO pendaftar (nama, email, wa, alamat, jenis_kursus, jenjang, hari_kursus, jam_kursus, metode_belajar, pengalaman, referensi, foto, motivasi, status_pekerjaan, pendidikan_terakhir) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'sssssssssssssss', $nama, $email, $wa, $alamat, $jenis_kursus, $jenjang, $hari_kursus, $jam_kursus, $metode_belajar, $pengalaman, $referensi, $foto_name, $motivasi, $status_pekerjaan, $pendidikan_terakhir);
        if (mysqli_stmt_execute($stmt)) {
            $berhasil = true;
        } else {
            $errors[] = 'Gagal menyimpan data: ' . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } else {
        $errors[] = 'Gagal memproses permintaan: ' . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Sukses</title>
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
            text-align: center;
            padding: 80px 20px;
        }

        .success-container {
            background-color: var(--white);
            border-radius: 16px;
            padding: 40px 30px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            animation: fadeIn 1s ease;
        }

        .checkmark {
            font-size: 60px;
            color: var(--primary);
            margin-bottom: 20px;
            animation: pop 0.6s ease-out;
        }

        .success-container h2 {
            color: var(--primary-dark);
            margin-bottom: 10px;
        }

        .success-container p {
            color: var(--gray);
            font-size: 16px;
            margin-top: 0;
        }

        .btn-kembali {
            margin-top: 30px;
            display: inline-block;
            background-color: var(--primary);
            color: #fff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .btn-kembali:hover {
            background-color: var(--primary-dark);
        }

        @keyframes pop {
            0% { transform: scale(0.2); opacity: 0; }
            60% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); }
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(20px);}
            to {opacity: 1; transform: translateY(0);}
        }
    </style>
</head>
<body>

<div class="success-container">
    <?php if ($berhasil): ?>
        <div class="checkmark">✔️</div>
        <h2>Pendaftaran Berhasil!</h2>
        <p>Terima kasih sudah mendaftar kursus kami.<br>Kami akan segera menghubungi Anda melalui email atau WhatsApp.</p>
        <a href="index.php" class="btn-kembali">Kembali ke Halaman Utama</a>
    <?php else: ?>
        <div class="checkmark" style="color: #b91c1c;">⚠️</div>
        <h2>Pendaftaran Gagal</h2>
        <p>Maaf, terjadi masalah saat memproses pendaftaran Anda. Silakan cek kembali data yang dimasukkan.</p>
        <?php if (!empty($errors)): ?>
            <div class="alert" style="background: rgba(254, 205, 211, 0.25); border-color: rgba(239, 68, 68, 0.3); color: #991b1b; text-align: left;">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <a href="kursus-daftar.php" class="btn-kembali">Kembali ke Form</a>
    <?php endif; ?>
</div>

</body>
</html>
