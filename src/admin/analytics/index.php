<?php
header('Location: ../analytics.php');
exit;
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - Analitik Kursus</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    :root {
      --primary: #2563eb;
      --primary-dark: #1d4ed8;
      --bg: #eef2ff;
      --white: #ffffff;
      --text: #0f172a;
      --muted: #475569;
      --border: rgba(148, 163, 184, 0.25);
      --shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
      --accent: #2563eb;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', sans-serif; background: linear-gradient(180deg, #eef2ff 0%, #f8fafc 100%); color: var(--text); min-height: 100vh; }
    .layout { display: flex; min-height: 100vh; }
    .sidebar { width: 260px; background: linear-gradient(180deg, var(--primary), var(--primary-dark)); color: #fff; padding: 30px 20px; }
    .sidebar .logo { font-size: 1.3rem; font-weight: 700; margin-bottom: 30px; display: flex; align-items: center; gap: 12px; }
    .sidebar .logo i { font-size: 1.4rem; }
    .sidebar ul { list-style: none; }
    .sidebar ul li { margin-bottom: 16px; }
    .sidebar ul li a { display: flex; align-items: center; gap: 12px; color: #dbeafe; text-decoration: none; font-size: 0.98rem; padding: 12px 14px; border-radius: 14px; transition: background 0.2s ease; }
    .sidebar ul li a:hover,
    .sidebar ul li a.active { background: rgba(255,255,255,0.12); color: #fff; }
    .sidebar ul li a i { width: 20px; text-align: center; }
    .main { flex: 1; padding: 32px; }
    .main-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 18px; margin-bottom: 28px; }
    .main-header h1 { font-size: 2rem; margin-bottom: 6px; }
    .subtitle { color: var(--muted); }
    .stats-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 18px; margin-bottom: 28px; }
    .stat-box { background: var(--white); border: 1px solid var(--border); border-radius: 24px; padding: 24px; box-shadow: var(--shadow); }
    .stat-box h3 { font-size: 0.95rem; color: var(--muted); margin-bottom: 10px; }
    .stat-box p { font-size: 2rem; font-weight: 700; }
    .charts-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 24px; }
    .chart-card { background: var(--white); border: 1px solid var(--border); border-radius: 28px; box-shadow: var(--shadow); padding: 22px; min-height: 400px; display: flex; flex-direction: column; }
    .chart-card h2 { font-size: 1.1rem; margin-bottom: 18px; }
    .chart-card canvas { width: 100% !important; height: 100% !important; }
    .legend-list { display: grid; gap: 10px; margin-top: 18px; }
    .legend-item { display: flex; align-items: center; gap: 12px; font-size: 0.95rem; color: var(--muted); }
    .legend-swatch { width: 14px; height: 14px; border-radius: 4px; display: inline-block; }
    @media (max-width: 1040px) { .charts-grid { grid-template-columns: 1fr; } }
    @media (max-width: 860px) { .layout { flex-direction: column; } .sidebar { width: 100%; position: sticky; top: 0; } }
  </style>
</head>
<body>
  <div class="layout">
    <aside class="sidebar">
      <div class="logo"><i class="fas fa-chart-line"></i> Admin Analitik</div>
      <ul>
        <li><a href="../dashboard.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
        <li><a href="index.php" class="active"><i class="fas fa-chart-pie"></i> <span>Analitik</span></a></li>
        <li><a href="../export-excel.php"><i class="fas fa-file-excel"></i> <span>Export Excel</span></a></li>
        <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
      </ul>
    </aside>
    <main class="main">
      <div class="main-header">
        <div>
          <h1>Analitik Kursus</h1>
          <p class="subtitle">Lihat distribusi pendaftar per bulan dan jenis kursus sekaligus.</p>
        </div>
      </div>

      <div class="stats-grid">
        <div class="stat-box">
          <h3>Total Pendaftar</h3>
          <p><?= $total_pendaftar ?></p>
        </div>
        <div class="stat-box">
          <h3>Jenis Kursus</h3>
          <p><?= count($course_labels) ?></p>
        </div>
        <div class="stat-box">
          <h3>Bulan Terlihat</h3>
          <p>12 Bulan</p>
        </div>
      </div>

      <div class="charts-grid">
        <section class="chart-card">
          <h2>Jumlah Pendaftar per Bulan</h2>
          <canvas id="monthlyChart"></canvas>
        </section>

        <section class="chart-card">
          <h2>Distribusi Kursus</h2>
          <canvas id="courseChart"></canvas>
          <div class="legend-list">
            <?php foreach ($course_counts as $index => $course): ?>
              <div class="legend-item">
                <span class="legend-swatch" style="background-color: <?= 'hsl(' . ($index * 40 % 360) . ', 75%, 55%)' ?>"></span>
                <?= htmlspecialchars($course['label']) ?> (<?= $course['value'] ?>)
              </div>
            <?php endforeach; ?>
          </div>
        </section>
      </div>
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const monthlyLabels = <?= json_encode($chart_labels) ?>;
    const monthlyData = <?= json_encode($chart_values) ?>;
    const courseLabels = <?= json_encode($course_labels) ?>;
    const courseData = <?= json_encode($course_values) ?>;
    const courseColors = courseLabels.map((_, index) => `hsl(${index * 40 % 360}, 75%, 55%)`);

    new Chart(document.getElementById('monthlyChart'), {
      type: 'bar',
      data: {
        labels: monthlyLabels,
        datasets: [{
          label: 'Pendaftar',
          data: monthlyData,
          backgroundColor: 'rgba(56, 189, 248, 0.75)',
          borderColor: 'rgba(37, 99, 235, 1)',
          borderWidth: 1,
          borderRadius: 12,
          maxBarThickness: 40
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          x: { grid: { display: false } },
          y: { beginAtZero: true, ticks: { precision: 0 } }
        },
        plugins: {
          legend: { display: false },
          tooltip: { callbacks: { label: ctx => `${ctx.parsed.y} pendaftar` } }
        }
      }
    });

    new Chart(document.getElementById('courseChart'), {
      type: 'pie',
      data: {
        labels: courseLabels,
        datasets: [{
          data: courseData,
          backgroundColor: courseColors,
          borderColor: '#ffffff',
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { position: 'bottom', labels: { boxWidth: 12, padding: 16 } },
          tooltip: { callbacks: { label: ctx => `${ctx.label}: ${ctx.parsed} pendaftar` } }
        }
      }
    });
  </script>
</body>
</html>
