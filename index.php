<?php require_once 'src/config/koneksi.php';
require_once 'src/includes/functions.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Beranda | Kursus Online Profesional</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      /* Blue, Purple, White, Black Palette */
      --primary: #2563eb; /* Biru utama */
      --primary-light: #3b82f6; /* Biru terang */
      --primary-dark: #1d4ed8; /* Biru gelap */
      --primary-extra-light: #eff6ff; /* Biru sangat terang */
      --accent: #4f46e5; /* Ungu */
      --white: #ffffff; /* Putih */
      --dark: #1e293b; /* Hitam gelap */
      --gray: #64748b; /* Abu-abu */
      --light-gray: #e2e8f0; /* Abu-abu terang */
      --glass-bg: rgba(255, 255, 255, 0.2); /* Efek kaca */
      --glass-border: rgba(255, 255, 255, 0.5);
      --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: var(--dark);
      line-height: 1.6;
      background: linear-gradient(135deg, var(--primary-extra-light), #3c6cabff);
      overflow-x: hidden;
    }

    /* Header & Navigation */
    header {
      background: var(--white);
      box-shadow: var(--shadow);
      position: sticky;
      top: 0;
      z-index: 1000;
      backdrop-filter: blur(5px);
      border-bottom: 1px solid var(--glass-border);
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 0;
      transition: padding 0.3s ease;
    }

    .logo {
      font-size: 28px;
      font-weight: 700;
      color: var(--primary);
      display: flex;
      gap: 0px;
    }

    .logo span {
      color: var(--dark);
      transition: color 0.3s;
    }

    .logo:hover span {
      color: var(--accent);
    }

    .nav-links {
      display: flex;
      gap: 25px;
      list-style: none;
      align-items: center;
    }

    .nav-links a {
      text-decoration: none;
      color: var(--dark);
      font-weight: 500;
      padding: 5px 10px;
      position: relative;
      transition: color 0.3s, transform 0.2s;
    }

    .nav-links a:hover {
      color: var(--accent);
      transform: translateY(-2px);
    }

    .nav-links a.active {
      color: var(--primary);
    }

    .nav-links a.active::after {
      content: '';
      position: absolute;
      bottom: -5px;
      left: 50%;
      transform: translateX(-50%);
      width: 60%;
      height: 2px;
      background: var(--primary);
      transition: width 0.3s ease;
    }

    .btn-daftar {
      background: var(--primary);
      color: var(--white) !important;
      padding: 10px 20px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 5px;
    }

    .btn-daftar:hover {
      background: var(--primary-dark);
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
    }

    .mobile-menu {
      display: none;
      font-size: 24px;
      background: none;
      border: none;
      color: var(--dark);
      cursor: pointer;
      transition: color 0.3s;
    }

    .mobile-menu:hover {
      color: var(--accent);
    }

    /* Hero Section */
    .hero {
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 70%);
      color: var(--white);
      padding: 100px 0;
      position: relative;
      overflow: hidden;
      display: flex;
      align-items: center;
    }

    .hero::before {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 50%;
      height: 100%;
      background: url('assets/img/utama.jpg') no-repeat center;
      background-size: cover;
      opacity: 0.7;
      border-radius: 20px 0 0 20px;
      z-index: 1;
    }

    .hero-content {
      max-width: 600px;
      position: relative;
      z-index: 2;
      text-align: left;
      animation: fadeIn 3s ease-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    .hero h1 {
      font-size: 48px;
      margin-bottom: 20px;
      line-height: 1.2;
      animation: slideIn 1s ease-out;
    }

    @keyframes slideIn {
      from { transform: translateX(-20px); opacity: 0; }
      to { transform: translateX(0); opacity: 1; }
    }

    .hero p {
      font-size: 18px;
      margin-bottom: 30px;
      opacity: 0.9;
    }

    .hero-buttons {
      display: flex;
      gap: 15px;
      align-items: center;
    }

    .btn-primary {
      background: var(--white);
      color: var(--primary);
      padding: 12px 24px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
      box-shadow: 0 2px 10px rgba(255, 255, 255, 0.3);
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }

    .btn-primary:hover {
      background: var(--primary-light);
      color: var(--white);
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
    }

    .btn-secondary {
      background: transparent;
      color: var(--white);
      padding: 12px 24px;
      border: 2px solid var(--white);
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-secondary:hover {
      background: rgba(255, 255, 255, 0.2);
      transform: translateY(-2px);
    }

    /* Stats Section */
    .stats {
      padding: 60px 0;
      background: var(--primary-extra-light);
      position: relative;
      text-align: center;
    }

    .stats::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 5px;
      background: linear-gradient(to right, var(--primary), var(--accent));
      opacity: 0.1;
    }

    .stats-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      max-width: 1000px;
      margin: 0 auto;
      padding: 0 10px;
    }

    .stat-item {
      background: var(--white);
      padding: 20px;
      border-radius: 10px;
      box-shadow: var(--shadow);
      transition: transform 0.3s ease;
    }

    .stat-item:hover {
      transform: translateY(-5px);
    }

    .stat-number {
      font-size: 36px;
      font-weight: 700;
      color: var(--primary);
      margin-bottom: 10px;
    }

    .stat-label {
      font-size: 16px;
      color: var(--gray);
    }

    /* Courses Section */
    .courses {
      padding: 80px 0;
      background: var(--white);
    }

    .section-header {
      text-align: center;
      margin-bottom: 40px;
      position: relative;
    }

    .section-header::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 60px;
      height: 3px;
      background: var(--accent);
      border-radius: 2px;
    }

    .section-header h2 {
      font-size: 36px;
      margin-bottom: 10px;
      color: var(--dark);
    }

    .section-header p {
      max-width: 600px;
      margin: 0 auto;
      color: var(--gray);
    }

    .courses-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 25px;
      padding: 0 10px;
      justify-items: center;
    }

    .course-card {
      background: var(--white);
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      width: 100%;
      max-width: 300px;
    }

    .course-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .course-badge {
      position: absolute;
      top: 15px;
      left: 15px;
      background: var(--accent);
      color: var(--white);
      padding: 5px 10px;
      border-radius: 4px;
      font-size: 12px;
      font-weight: 600;
      z-index: 1;
    }

    .course-image {
      height: 180px;
      overflow: hidden;
      position: relative;
    }

    .course-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
    }

    .course-card:hover .course-image img {
      transform: scale(1.1);
    }

    .course-content {
      padding: 15px;
      text-align: center;
    }

    .course-category {
      display: inline-block;
      color: var(--primary);
      font-size: 14px;
      margin-bottom: 8px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .course-title {
      font-size: 18px;
      margin-bottom: 10px;
      color: var(--dark);
      font-weight: 600;
    }

    .course-description {
      color: var(--gray);
      margin-bottom: 15px;
      font-size: 14px;
      line-height: 1.5;
    }

    .course-info {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 15px;
      border-top: 1px solid var(--light-gray);
      padding-top: 10px;
    }

    .course-price {
      font-size: 16px;
      font-weight: 700;
      color: var(--primary);
    }

    .course-price span {
      color: var(--gray);
      font-size: 12px;
      text-decoration: line-through;
      margin-left: 5px;
      font-weight: 400;
    }

    .course-rating {
      display: flex;
      align-items: center;
      color: var(--gray);
      font-size: 12px;
    }

    .stars {
      color: #f59e0b;
      margin-right: 5px;
    }

    .btn-enroll {
      display: inline-block;
      background: var(--primary);
      color: var(--white);
      padding: 8px 16px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 600;
      margin-top: 10px;
      transition: background 0.3s ease;
    }

    .btn-enroll:hover {
      background: var(--primary-dark);
    }

    /* Features Section */
    .features {
      padding: 80px 0;
      background: var(--primary-extra-light);
      position: relative;
    }

    .features::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 5px;
      background: linear-gradient(to right, var(--primary), var(--accent));
    }

    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 25px;
      padding: 0 10px;
      justify-items: center;
    }

    .feature-card {
      background: var(--white);
      padding: 25px;
      border-radius: 10px;
      box-shadow: var(--shadow);
      transition: transform 0.3s ease;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .feature-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 5px;
      background: var(--accent);
      animation: slideLeft 3s infinite;
    }

    @keyframes slideLeft {
      0% { left: -100%; }
      50% { left: 100%; }
      100% { left: -100%; }
    }

    .feature-card:hover {
      transform: translateY(-5px);
    }

    .feature-icon {
      width: 60px;
      height: 60px;
      background: var(--primary-light);
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      margin: 0 auto 15px;
      color: var(--white);
      font-size: 24px;
      transition: transform 0.3s ease;
    }

    .feature-card:hover .feature-icon {
      transform: rotate(15deg) scale(1.1);
    }

    .feature-title {
      font-size: 20px;
      margin-bottom: 12px;
      color: var(--dark);
      font-weight: 600;
    }

    .feature-description {
      color: var(--gray);
      line-height: 1.6;
    }

    /* Testimonials Section */
    .testimonials {
      padding: 80px 0;
      background: var(--white);
    }

    .testimonials-slider {
      position: relative;
      max-width: 900px;
      margin: 0 auto;
      overflow: hidden;
    }

    .testimonial-card {
      background: var(--glass-bg);
      border: 1px solid var(--glass-border);
      border-radius: 10px;
      padding: 25px;
      max-width: 800px;
      margin: 0 auto;
      box-shadow: var(--shadow);
      position: relative;
      animation: fadeInUp 1s ease-out;
    }

    @keyframes fadeInUp {
      from { transform: translateY(20px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    .testimonial-card::before {
      content: '"';
      position: absolute;
      top: -15px;
      left: 15px;
      font-size: 60px;
      color: var(--accent);
      opacity: 0.2;
      font-family: serif;
    }

    .testimonial-content {
      margin-bottom: 20px;
      color: var(--gray);
      font-style: italic;
      font-size: 16px;
    }

    .testimonial-author {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .author-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      margin-right: 10px;
      object-fit: cover;
      border: 2px solid var(--accent);
    }

    .author-info h4 {
      color: var(--dark);
      font-weight: 600;
    }

    .author-info p {
      color: var(--gray);
      font-size: 12px;
    }

    /* CTA Section */
    .cta {
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
      color: var(--white);
      padding: 80px 0;
      text-align: center;
      position: relative;
    }

    .cta::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 15px;
      background: linear-gradient(to top, rgba(0, 0, 0, 0.1), transparent);
    }

    .cta .container {
      max-width: 800px;
    }

    .cta h2 {
      font-size: 36px;
      margin-bottom: 15px;
      font-weight: 700;
      animation: fadeIn 1s ease-out;
    }

    .cta p {
      margin-bottom: 25px;
      opacity: 0.9;
      font-size: 16px;
    }

    /* Footer */
    footer {
      background: var(--dark);
      color: var(--white);
      padding: 60px 0 20px;
      position: relative;
    }

    footer::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 5px;
      background: linear-gradient(to right, var(--primary), var(--accent));
    }

    .footer-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 30px;
      margin-bottom: 40px;
      padding: 0 10px;
    }

    .footer-logo {
      font-size: 22px;
      font-weight: 700;
      color: var(--white);
      margin-bottom: 15px;
    }

    .footer-description {
      margin-bottom: 15px;
      color: var(--light-gray);
      font-size: 14px;
    }

    .social-icons {
      display: flex;
      gap: 12px;
      justify-content: center;
    }

    .social-icon {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: var(--glass-bg);
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
    }

    .social-icon:hover {
      background: var(--primary);
      transform: scale(1.1);
    }

    .footer-links h3 {
      font-size: 16px;
      margin-bottom: 15px;
      color: var(--white);
    }

    .footer-links ul {
      list-style: none;
    }

    .footer-links li {
      margin-bottom: 10px;
    }

    .footer-links a {
      color: var(--light-gray);
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .footer-links a:hover {
      color: var(--primary-light);
    }

    .copyright {
      text-align: center;
      padding-top: 15px;
      border-top: 1px solid var(--glass-border);
      color: var(--light-gray);
      font-size: 12px;
    }

    /* Responsive */
    @media (max-width: 992px) {
      .hero::before {
        display: none;
      }
      .hero-content {
        max-width: 100%;
        text-align: center;
      }
      .hero-buttons {
        justify-content: center;
      }
      .courses-grid {
        grid-template-columns: 1fr;
      }
      .features-grid {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 768px) {
      .nav-links {
        display: none;
        flex-direction: column;
        position: absolute;
        top: 70px;
        left: 0;
        width: 100%;
        background: var(--white);
        padding: 20px;
        box-shadow: var(--shadow);
        opacity: 0;
        transform: translateY(-10px);
        transition: opacity 0.3s ease, transform 0.3s ease;
      }

      .nav-links.active {
        display: flex;
        opacity: 1;
        transform: translateY(0);
      }

      .mobile-menu {
        display: block;
      }

      .hero h1 {
        font-size: 32px;
      }

      .btn-daftar {
        width: 100%;
        text-align: center;
        margin-top: 10px;
        color: var(--white) !important;
      }
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header>
    <div class="container">
      <nav class="navbar">
        <a href="index.php" class="logo">Si<span>Pintar</span></a>
        <ul class="nav-links">
          <li><a href="index.php" class="active">Beranda</a></li>
          <li><a href="src/auth/register.php" class="btn-daftar">Daftar Sekarang</a></li>
        </ul>
        <button class="mobile-menu" onclick="toggleMenu()">
          <i class="fas fa-bars"></i>
        </button>
      </nav>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <div class="container">
      <div class="hero-content">
        <h1>Belajar Kapan Saja, Di Mana Saja</h1>
        <p>Tingkatkan skillmu dengan kursus online dari instruktur berpengalaman dengan materi terupdate dan metode pembelajaran interaktif.</p>
        <div class="hero-buttons">
          <a href="src/auth/register.php" class="btn-primary">Daftar Sekarang</a>
          <a href="#courses" class="btn-secondary">Lihat Kursus</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats Section -->
  <section class="stats">
    <div class="container">
      <div class="stats-container">
        <div class="stat-item">
          <div class="stat-number">5,000+</div>
          <div class="stat-label">Pelajar</div>
        </div>
        <div class="stat-item">
          <div class="stat-number">100+</div>
          <div class="stat-label">Kursus Online</div>
        </div>
        <div class="stat-item">
          <div class="stat-number">50+</div>
          <div class="stat-label">Instruktur Ahli</div>
        </div>
        <div class="stat-item">
          <div class="stat-number">98%</div>
          <div class="stat-label">Kepuasan Pelajar</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Courses Section -->
  <section class="courses" id="courses">
    <div class="container">
      <div class="section-header">
        <h2>Kursus Populer</h2>
        <p>Pilih dari berbagai kursus terbaik yang kami sediakan untuk mengembangkan keterampilan Anda.</p>
      </div>
      <div class="courses-grid">
        <div class="course-card">
          <div class="course-badge">Diskon 40%</div>
          <div class="course-image">
            <img src="assets/img/desain.png" alt="Desain Grafis dengan Adobe Photoshop, Illustrator dan Canva">
          </div>
          <div class="course-content">
            <span class="course-category">Desain</span>
            <h3 class="course-title">Desain Grafis Profesional</h3>
            <p class="course-description">Belajar Photoshop, Illustrator, dan Canva dari dasar hingga mahir untuk kebutuhan profesional.</p>
            <div class="course-info">
              <div class="course-price">Rp 600.000 <span>Rp 1.000.000</span></div>
              <div class="course-rating">
                <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
                </div>
                (4.8)
              </div>
            </div>
            <a href="src/auth/register.php" class="btn-enroll">Ambil Kursus</a>
          </div>
        </div>
        <div class="course-card">
          <div class="course-badge">Diskon 50%</div>
          <div class="course-image">
            <img src="assets/img/work.png" alt="Data Analyst dengan Excel, SQL, Python dan Tableau">
          </div>
          <div class="course-content">
            <span class="course-category">Analisis Data</span>
            <h3 class="course-title">Data Analyst Lengkap</h3>
            <p class="course-description">Pelajari Excel, SQL, Python dan Tableau untuk pengolahan, analisis, dan visualisasi data profesional.</p>
            <div class="course-info">
              <div class="course-price">Rp 400.000 <span>Rp 800.000</span></div>
              <div class="course-rating">
                <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
                (5.0)
              </div>
            </div>
            <a href="src/auth/register.php" class="btn-enroll">Ambil Kursus</a>
          </div>
        </div>
        <div class="course-card">
          <div class="course-badge">Diskon 30%</div>
          <div class="course-image">
            <img src="assets/img/digital.png">
          </div>
          <div class="course-content">
            <span class="course-category">Marketing</span>
            <h3 class="course-title">Digital Marketing Expert</h3>
            <p class="course-description">Facebook Ads, SEO, Content Marketing dan strategi digital marketing lengkap untuk meningkatkan penjualan.</p>
            <div class="course-info">
              <div class="course-price">Rp 630.000 <span>Rp 900.000</span></div>
              <div class="course-rating">
                <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
                (4.9)
              </div>
            </div>
            <a href="src/auth/register.php" class="btn-enroll">Ambil Kursus</a>
          </div>
        </div>
        <div class="course-card">
          <div class="course-badge">Diskon 20%</div>
          <div class="course-image">
            <img src="assets/img/word.png" alt="Microsoft Word untuk Dokumen Profesional">
          </div>
          <div class="course-content">
            <span class="course-category">Office</span>
            <h3 class="course-title">Microsoft Word Mastery</h3>
            <p class="course-description">Penguasaan lengkap Microsoft Word untuk membuat dokumen profesional, laporan, dan tesis secara efisien.</p>
            <div class="course-info">
              <div class="course-price">Rp 320.000 <span>Rp 400.000</span></div>
              <div class="course-rating">
                <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
                </div>
                (4.7)
              </div>
            </div>
            <a href="src/auth/register.php" class="btn-enroll">Ambil Kursus</a>
          </div>
        </div>
        <div class="course-card">
          <div class="course-badge">Diskon 25%</div>
          <div class="course-image">
            <img src="assets/img/excel.png" alt="Microsoft Excel untuk Pengolahan Data">
          </div>
          <div class="course-content">
            <span class="course-category">Excel</span>
            <h3 class="course-title">Microsoft Excel Expert</h3>
            <p class="course-description">Belajar rumus, pivot table, dashboard, makro, dan analisis data dengan Excel untuk kebutuhan profesional.</p>
            <div class="course-info">
              <div class="course-price">Rp 375.000 <span>Rp 500.000</span></div>
              <div class="course-rating">
                <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
                (4.9)
              </div>
            </div>
            <a href="src/auth/register.php" class="btn-enroll">Ambil Kursus</a>
          </div>
        </div>
        <div class="course-card">
          <div class="course-badge">Diskon 35%</div>
          <div class="course-image">
            <img src="assets/img/piton.png" alt="Machine Learning dengan Python">
          </div>
          <div class="course-content">
            <span class="course-category">AI & Data Science</span>
            <h3 class="course-title">Machine Learning Fundamental</h3>
            <p class="course-description">Kuasai dasar machine learning mulai dari preprocessing data, model ML, hingga evaluasi model dengan Python.</p>
            <div class="course-info">
              <div class="course-price">Rp 780.000 <span>Rp 1.200.000</span></div>
              <div class="course-rating">
                <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
                (4.9)
              </div>
            </div>
            <a href="src/auth/register.php" class="btn-enroll">Ambil Kursus</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section class="features">
    <div class="container">
      <div class="section-header">
        <h2>Keunggulan Kami</h2>
        <p>Mengapa harus belajar di KursusOnline? Ini dia kelebihan yang akan Anda dapatkan.</p>
      </div>
      <div class="features-grid">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-chalkboard-teacher"></i>
          </div>
          <h3 class="feature-title">Instruktur Berpengalaman</h3>
          <p class="feature-description">Dibimbing oleh instruktur yang telah terbukti berpengalaman di bidangnya masing-masing.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-video"></i>
          </div>
          <h3 class="feature-title">Materi Video Berkualitas</h3>
          <p class="feature-description">Akses video pembelajaran berkualitas HD dengan durasi yang optimal untuk memaksimalkan pemahaman.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-certificate"></i>
          </div>
          <h3 class="feature-title">Sertifikat Resmi</h3>
          <p class="feature-description">Dapatkan sertifikat resmi yang bisa digunakan untuk mendukung karir profesional Anda.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-headset"></i>
          </div>
          <h3 class="feature-title">Support 24/7</h3>
          <p class="feature-description">Tim support kami siap membantu Anda kapan saja selama proses pembelajaran berlangsung.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-mobile-alt"></i>
          </div>
          <h3 class="feature-title">Akses Seluler</h3>
          <p class="feature-description">Belajar dimanapun dan kapanpun melalui perangkat seluler dengan aplikasi kami.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-graduation-cap"></i>
          </div>
          <h3 class="feature-title">Proyek Nyata</h3>
          <p class="feature-description">Praktekkan langsung skill Anda dengan proyek nyata sebagai portofolio karir.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimonials Section -->
  <section class="testimonials">
    <div class="container">
      <div class="section-header">
        <h2>Apa Kata Mereka</h2>
        <p>Testimonial dari peserta yang telah mengikuti program kursus kami.</p>
      </div>
      <div class="testimonials-slider">
        <div class="testimonial-card">
          <div class="testimonial-content">
            "Saya sangat merekomendasikan pelatihan online Digital Marketing dari Sipintar! Materi yang disampaikan sangat lengkap dan mudah dipahami, bahkan untuk pemula sekalipun. Saya jadi lebih percaya diri dalam menjalankan bisnis online saya. Setelah mengikuti pelatihan ini, saya berhasil meningkatkan penjualan lewat strategi digital yang tepat sasaran. Terima kasih Sipintar, ilmunya sangat aplikatif dan bermanfaat!"
          </div>
            <div class="testimonial-author">
            <img src="assets/img/wanitamuda.png" alt="Yasmine - Entrepreneur" class="author-avatar">
            <div class="author-info">
              <h4>Yasmine</h4>
              <p>Entrepreneur</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="cta">
    <div class="container">
      <h2>Siap Memulai Perjalanan Belajarmu?</h2>
      <p>Bergabunglah dengan ribuan pelajar lainnya yang telah meningkatkan skill mereka dengan kursus kami. Daftar sekarang dan dapatkan diskon spesial untuk pendaftaran pertama.</p>
      <a href="src/auth/register.php" class="btn-primary">Daftar Sekarang</a>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="container">
      <div class="footer-grid">
        <div class="footer-about">
          <div class="footer-logo">Si<span>Pintar</span></div>
          <p class="footer-description">
            Platform belajar online terbaik di Indonesia yang membantu Anda mengembangkan skill digital untuk karir lebih baik.
          </p>
          <div class="social-icons">
            <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
          </div>
        </div>
        <div class="footer-links">
          <h3>Tautan Cepat</h3>
          <ul>
            <li><a href="index.php">Beranda</a></li>
            <li><a href="courses.php">Kursus</a></li>
            <li><a href="about.php">Tentang Kami</a></li>
            <li><a href="contact.php">Kontak</a></li>
            <li><a href="blog.php">Blog</a></li>
          </ul>
        </div>
        <div class="footer-links">
          <h3>Kategori</h3>
          <ul>
            <li><a href="#">Teknologi</a></li>
            <li><a href="#">Desain</a></li>
            <li><a href="#">Bisnis</a></li>
            <li><a href="#">Marketing</a></li>
            <li><a href="#">Keuangan</a></li>
          </ul>
        </div>
        <div class="footer-links">
          <h3>Kontak Kami</h3>
          <ul>
            <li><i class="fas fa-envelope"></i> info@sipintar.com</li>
            <li><i class="fas fa-phone"></i> +62 858 1967 8029</li>
            <li><i class="fas fa-map-marker-alt"></i> Jl. Akna No.09, Ciledug, Cirebon</li>
          </ul>
        </div>
      </div>
      <div class="copyright">
        © 2025 SiPintar reserved.
      </div>
    </div>
  </footer>

  <script>
    function toggleMenu() {
      const navLinks = document.querySelector('.nav-links');
      navLinks.classList.toggle('active');
    }

    // Sederhana testimonial slider
    let slideIndex = 0;
    const slides = document.querySelectorAll('.testimonial-card');
    if (slides.length > 0) {
      slides.forEach((slide, index) => {
        slide.style.display = index === 0 ? 'block' : 'none';
      });

      setInterval(() => {
        slides[slideIndex].style.display = 'none';
        slideIndex = (slideIndex + 1) % slides.length;
        slides[slideIndex].style.display = 'block';
        slides[slideIndex].style.animation = 'fadeInUp 0.5s ease-out';
      }, 5000);
    }
  </script>
</body>
</html>