<?php
session_start();
require_once '../../src/config/koneksi.php';
require_once '../../src/includes/functions.php';

// Dummy login
$admin_user = "farhan";
$admin_pass = "farhan";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $user = $_POST["username"];
  $pass = $_POST["password"];

  if ($user === $admin_user && $pass === $admin_pass) {
    $_SESSION["admin"] = true;
    header("Location: dashboard.php");
    exit;
  } else {
    $error = "Username atau password salah!";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login Admin</title>
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
      --border: #e5e7eb;
    }

    * {
      box-sizing: border-box;
    }

    html, body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: var(--bg);
      height: 100%;
    }

    body.login-page {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 20px;
    }

    .login-container {
      background-color: var(--white);
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      max-width: 420px;
      width: 100%;
      padding: 40px 30px;
      text-align: center;
    }

    .login-logo {
      width: 90px;
      margin-bottom: 20px;
    }

    .login-container h2 {
      font-size: 26px;
      color: var(--primary-dark);
      margin-bottom: 20px;
    }

    label {
      font-weight: 600;
      color: var(--text);
      margin-top: 20px;
      display: block;
      text-align: left;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 12px;
      margin-top: 8px;
      background-color: var(--gray-light);
      border: 1px solid var(--border);
      border-radius: 8px;
      font-size: 15px;
      transition: 0.3s;
    }

    input:focus {
      border-color: var(--primary);
      outline: none;
      background-color: var(--white);
    }

    button[type="submit"] {
      background-color: var(--primary);
      color: var(--white);
      border: none;
      border-radius: 8px;
      padding: 14px;
      margin-top: 30px;
      font-weight: bold;
      font-size: 16px;
      width: 100%;
      cursor: pointer;
      transition: background-color 0.3s ease, transform 0.2s ease;
    }

    button[type="submit"]:hover {
      background-color: var(--primary-dark);
      transform: translateY(-2px);
    }

    .error-msg {
      background: #f8d7da;
      color: #842029;
      padding: 10px;
      margin-top: 10px;
      border: 1px solid #f5c2c7;
      border-radius: 6px;
      text-align: left;
    }

    .password-wrapper {
      position: relative;
    }

    .toggle-password {
      position: absolute;
      top: 50%;
      right: 12px;
      transform: translateY(-50%);
      cursor: pointer;
      user-select: none;
      color: var(--gray);
    }

    .login-footer {
      margin-top: 25px;
      font-size: 13px;
      color: var(--gray);
    }

    @media (max-width: 600px) {
      .login-container {
        padding: 30px 20px;
      }
    }
  </style>
</head>

<body class="login-page">

  <div class="login-container">
    <img src="../../logo_saya/logome.png" class="login-logo" alt="Logo" />

    <h2>Login Admin</h2>

    <?php if (!empty($error)) echo "<div class='error-msg'>$error</div>"; ?>

    <form method="POST">
      <label for="username">Username</label>
      <input type="text" name="username" id="username" required>

      <label for="password">Password</label>
      <div class="password-wrapper">
        <input type="password" name="password" id="password" required>
        <span class="toggle-password" onclick="togglePassword()">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
            <path d="M8 3.5C4.5 3.5 1.5 6.5 1.5 8s3 4.5 6.5 4.5 6.5-3 6.5-4.5S11.5 3.5 8 3.5zM8 11a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5z"/>
          </svg>
        </span>
      </div>

      <button type="submit">Log In</button>
    </form>

    <p class="login-footer">© <?= date("Y") ?> SiPintar. Powered by FARHAN.</p>
  </div>

  <script>
    function togglePassword() {
      const input = document.getElementById("password");
      const icon = document.querySelector(".toggle-password svg");
      if (input.type === "password") {
        input.type = "text";
        icon.setAttribute("fill", "#2563eb");
      } else {
        input.type = "password";
        icon.setAttribute("fill", "currentColor");
      }
    }
  </script>

</body>
</html>
