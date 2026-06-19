<?php
session_start();
if (!isset($_SESSION["admin"])) {
  header("Location: login.php");
  exit;
}

require_once '../config/koneksi.php';
require_once '../includes/functions.php';

// Pagination
$per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $per_page;

// Total pendaftar
$total_pendaftar_query = mysqli_query($conn, "SELECT * FROM pendaftar");
$total_pendaftar = mysqli_num_rows($total_pendaftar_query);
$total_pages = ceil($total_pendaftar / $per_page);

// Kursus Aktif (asumsi ada kolom status_kursus)
$active_courses_query = mysqli_query($conn, "SELECT COUNT(*) as active FROM pendaftar WHERE status_kursus = 'Aktif'");
$active_courses = mysqli_fetch_array($active_courses_query)['active'];

// Fallback jika kolom status_kursus tidak ada
if ($active_courses === 0 && !mysqli_num_rows($active_courses_query)) {
    $active_courses = $total_pendaftar; // Fallback ke total pendaftar
}

// Data pendaftar per bulan untuk chart
$month_counts = [];
$chart_query = mysqli_query($conn, "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, COUNT(*) AS total FROM pendaftar GROUP BY month ORDER BY month ASC");
while ($row = mysqli_fetch_assoc($chart_query)) {
    $month_counts[$row['month']] = (int) $row['total'];
}

$chart_labels = [];
$chart_values = [];
$startDate = new DateTime('first day of -11 months');
for ($i = 0; $i < 12; $i++) {
    $monthKey = $startDate->format('Y-m');
    $chart_labels[] = $startDate->format('M Y');
    $chart_values[] = $month_counts[$monthKey] ?? 0;
    $startDate->modify('+1 month');
}

$data = mysqli_query($conn, "SELECT * FROM pendaftar LIMIT $start, $per_page");
$no = $start + 1; // Inisialisasi $no berdasarkan offset pagination
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - Data Peserta</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    :root {
      --primary: #2563eb;
      --primary-dark: #1d4ed8;
      --bg: #e0e7ff;
      --white: #ffffff;
      --gray-light: rgba(255, 255, 255, 0.9);
      --gray: #6b7280;
      --text: #1e293b;
      --border: rgba(209, 213, 219, 0.5);
      --accent: #000000ff; 
      --accent2: #1d4ed8; 
      --shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      --glass-bg: rgba(255, 255, 255, 0.15);
      --glass-border: rgba(255, 255, 255, 0.3);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Roboto', 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, var(--bg), #c7d2fe);
      color: var(--text);
      height: 100vh;
      overflow: hidden;
      display: flex;
    }

    .sidebar {
      width: 260px;
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: var(--white);
      padding: 25px 15px;
      position: fixed;
      height: 100%;
      transition: width 0.3s ease, transform 0.3s ease;
      box-shadow: var(--shadow);
      z-index: 100;
    }

    .sidebar:hover {
      width: 280px;
    }

    .sidebar .logo {
      font-size: 26px;
      font-weight: 700;
      margin-bottom: 40px;
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px;
      background: var(--glass-bg);
      border-radius: 10px;
      backdrop-filter: blur(10px);
      border: 1px solid var(--glass-border);
    }

    .sidebar .logo i {
      color: var(--accent); /* Ikon logo sidebar jadi ungu */
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.1); }
      100% { transform: scale(1); }
    }

    .sidebar ul {
      list-style: none;
    }

    .sidebar ul li {
      margin: 15px 0;
    }

    .sidebar ul li a {
      text-decoration: none;
      color: var(--white);
      font-weight: 600;
      padding: 12px 15px;
      display: flex;
      align-items: center;
      gap: 12px;
      border-radius: 8px;
      transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .sidebar ul li a:hover {
      background-color: var(--glass-bg);
      transform: translateX(5px);
    }

    .sidebar ul li a i {
      font-size: 18px;
    }

    .main-content {
      margin-left: 260px;
      width: calc(100% - 260px);
      padding: 25px;
      overflow-y: auto;
      height: 100vh;
    }

    .header {
      background: var(--white);
      padding: 15px 30px;
      border-radius: 12px;
      box-shadow: var(--shadow);
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      backdrop-filter: blur(5px);
      border: 1px solid var(--glass-border);
    }

    .header h2 {
      font-size: 30px;
      color: var(--primary-dark);
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .header h2 i {
      color: var(--accent); /* Ikon judul header jadi ungu */
    }

    .header .actions {
      display: flex;
      gap: 15px;
      align-items: center;
    }

    .btn {
      background: var(--primary);
      color: var(--white);
      border: none;
      padding: 10px 20px;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
    }

    .btn:hover {
      background-color: var(--primary-dark);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .btn-refresh {
      background: var(--accent2); /* Tombol refresh jadi ungu */
    }

    .btn-refresh:hover {
      background-color: #6b21a8; /* Nada ungu yang lebih gelap saat hover */
      box-shadow: 0 4px 12px rgba(126, 34, 206, 0.3);
    }

    .search-bar input {
      padding: 10px 15px;
      width: 300px;
      border: 1px solid var(--border);
      border-radius: 8px;
      font-size: 14px;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .search-bar input:focus {
      border-color: var(--primary);
      box-shadow: 0 0 8px rgba(37, 99, 235, 0.2);
      outline: none;
    }

    .stats-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      margin-bottom: 20px;
    }

    .stat-card {
      background: var(--white);
      padding: 20px;
      border-radius: 10px;
      text-align: center;
      box-shadow: var(--shadow);
      backdrop-filter: blur(5px);
      border: 1px solid var(--glass-border);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    .stat-card h3 {
      color: var(--primary-dark);
      font-size: 16px;
      margin-bottom: 5px;
    }

    .stat-card p {
      font-size: 24px;
      font-weight: 700;
      color: var(--text);
    }

    .chart-card {
      background: var(--white);
      border-radius: 16px;
      box-shadow: var(--shadow);
      border: 1px solid var(--glass-border);
      margin-bottom: 24px;
      padding: 24px;
      overflow: hidden;
    }

    .chart-card-header {
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
    }

    .chart-card-header h3 {
      margin: 0;
      font-size: 18px;
      color: var(--primary-dark);
    }

    .chart-card-body {
      position: relative;
      width: 100%;
    }

    table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      background: var(--white);
      border-radius: 12px;
      box-shadow: var(--shadow);
      animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    table th, table td {
      padding: 14px 16px;
      text-align: center;
      border-bottom: 1px solid var(--border);
    }

    table th {
      background: linear-gradient(90deg, var(--primary), var(--primary-dark));
      color: var(--white);
      font-weight: 600;
      font-size: 15px;
      position: sticky;
      top: 0;
      z-index: 1;
    }

    table tr {
      transition: background-color 0.3s ease, transform 0.2s ease;
    }

    table tr:hover {
      background-color: #f0f9ff;
      transform: translateY(-2px);
    }

    .btn-action {
      padding: 8px 14px;
      font-size: 13px;
      font-weight: 600;
      color: #fff;
      border-radius: 6px;
      text-decoration: none;
      transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .btn-edit {
      background: #132edeff;
    }

    .btn-hapus {
      background: #000000;
    }

    .btn-edit:hover {
      background: #132edeff;
      transform: translateY(-1px);
    }

    .btn-hapus:hover {
      background: #000000;
      transform: translateY(-1px);
    }

    img.foto-pendaftar {
      width: 60px;
      border-radius: 6px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    img.foto-pendaftar:hover {
      transform: scale(1.1);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .pagination {
      margin-top: 20px;
      text-align: center;
    }

    .pagination a {
      color: var(--primary-dark);
      padding: 8px 12px;
      text-decoration: none;
      border: 1px solid var(--border);
      border-radius: 6px;
      margin: 0 5px;
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    .pagination a:hover {
      background-color: var(--primary);
      color: var(--white);
    }

    .pagination .active {
      background-color: var(--primary);
      color: var(--white);
    }

    @media (max-width: 768px) {
      .sidebar {
        width: 60px;
      }
      .sidebar:hover {
        width: 260px;
      }
      .main-content {
        margin-left: 60px;
        width: calc(100% - 60px);
      }
      .sidebar .logo {
        font-size: 0;
      }
      .sidebar:hover .logo {
        font-size: 26px;
      }
      .sidebar ul li a span {
        display: none;
      }
      .sidebar:hover ul li a span {
        display: inline;
      }
      .header {
        flex-direction: column;
        gap: 15px;
      }
      .stats-container {
        grid-template-columns: 1fr;
      }
      table {
        font-size: 12px;
      }
      .search-bar input {
        width: 100%;
      }
    }
  </style>
</head>
<body>

  <div class="sidebar">
  <div class="logo"><i class="fas fa-shield-alt"></i> Admin Panel</div>
  <ul>
    <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
    <li><a href="analytics.php"><i class="fas fa-chart-pie"></i> <span>Analitik</span></a></li>
    <li><a href="export-excel.php"><i class="fas fa-file-excel"></i> <span>Export Excel</span></a></li>
    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
  </ul>
</div>

<div class="main-content">
  <div class="header">
    <h2><i class="fas fa-users"></i> Data Pendaftar Kursus</h2>
    <div class="actions">
      <button class="btn btn-refresh" onclick="refreshData()">Refresh <i class="fas fa-sync"></i></button>
      <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Cari nama atau email..." onkeyup="searchTable()">
      </div>
    </div>
  </div>

  <div class="stats-container">
    <div class="stat-card">
      <h3>Total Pendaftar</h3>
      <p><?= $total_pendaftar ?></p>
    </div>
    <div class="stat-card">
      <h3>Kursus Aktif</h3>
      <p><?= $active_courses ?></p>
    </div>
  </div>

  <!-- Bar chart moved to Analitik page -->

  <table id="dataTable">
    <tr>
      <th>No</th>
      <th>Nama</th>
      <th>Email</th>
      <th>No. WA</th>
      <th>Jenis Kursus</th>
      <th>Jenjang</th>
      <th>Aksi</th>
    </tr>

    <?php while ($d = mysqli_fetch_array($data)) { ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= htmlspecialchars($d['nama']) ?></td>
      <td><?= htmlspecialchars($d['email']) ?></td>
      <td><?= htmlspecialchars($d['wa']) ?></td>
      <td><?= htmlspecialchars($d['jenis_kursus']) ?></td>
      <td><?= htmlspecialchars($d['jenjang']) ?></td>
      <td>
        <a href="kursus-hapus.php?id=<?= $d['id'] ?>" class="btn-action btn-hapus" onclick="return confirm('Yakin ingin menghapus data ini?')" title="Hapus data">Hapus</a>
      </td>
    </tr>
    <?php } ?>
  </table>

  <div class="pagination">
    <?php for ($i = 1; $i <= $total_pages; $i++) {
      $active = $page == $i ? 'active' : '';
      echo "<a href='?page=$i' class='$active'>$i</a>";
    } ?>
  </div>
</div>

<script>
  function searchTable() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    let table = document.getElementById("dataTable");
    let tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {
      let td = tr[i].getElementsByTagName("td")[1]; // Kolom Nama
      let td2 = tr[i].getElementsByTagName("td")[2]; // Kolom Email
      if (td || td2) {
        let txtValue = (td.textContent || td2.textContent).toLowerCase();
        tr[i].style.display = txtValue.indexOf(input) > -1 ? "" : "none";
      }
    }
  }

  function refreshData() {
    location.reload();
  }

  // Smooth pagination fetch
  document.querySelectorAll('.pagination a').forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      const page = link.getAttribute('href');
      window.history.pushState({}, '', page);
      fetchData(page);
    });
  });

  function fetchData(page) {
    fetch(`dashboard.php?page=${page.split('=')[1]}`)
      .then(response => response.text())
      .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newTable = doc.querySelector('#dataTable');
        document.querySelector('#dataTable').innerHTML = newTable.innerHTML;
        const newPagination = doc.querySelector('.pagination');
        document.querySelector('.pagination').innerHTML = newPagination.innerHTML;
      })
      .catch(error => console.error('Error:', error));
  }
</script>
</html>