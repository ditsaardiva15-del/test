<?php
session_start();
if(isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: admin.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Admin - Kopi Nusantara</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body {
    font-family: 'DM Sans', sans-serif;
    background: linear-gradient(135deg, #1A0F06 0%, #2C1A0E 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .login-container {
    background: rgba(26,15,6,0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(192,123,58,0.25);
    border-radius: 24px;
    padding: 2.5rem;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
  }
  .login-icon {
    font-size: 3rem;
    text-align: center;
    margin-bottom: 1rem;
  }
  .login-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.8rem;
    font-weight: 700;
    text-align: center;
    color: #D4A96A;
    margin-bottom: 0.5rem;
  }
  .login-sub {
    text-align: center;
    color: rgba(245,237,216,0.45);
    font-size: 0.85rem;
    margin-bottom: 2rem;
  }
  .form-group {
    margin-bottom: 1.2rem;
  }
  .form-label {
    display: block;
    color: rgba(245,237,216,0.6);
    font-size: 0.8rem;
    margin-bottom: 0.4rem;
  }
  .form-control {
    width: 100%;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(245,237,216,0.12);
    border-radius: 10px;
    padding: 0.8rem 1rem;
    color: #F5EDD8;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.9rem;
    outline: none;
  }
  .form-control:focus {
    border-color: #C07B3A;
  }
  .btn-login {
    width: 100%;
    background: #C07B3A;
    color: white;
    border: none;
    padding: 0.8rem;
    border-radius: 10px;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
  }
  .btn-login:hover {
    background: #D4A96A;
  }
  .error-message {
    background: rgba(180,60,30,0.2);
    border: 1px solid rgba(180,60,30,0.3);
    color: #E8855A;
    padding: 0.75rem;
    border-radius: 8px;
    font-size: 0.8rem;
    margin-bottom: 1rem;
    text-align: center;
  }
  .login-hint {
    text-align: center;
    color: rgba(245,237,216,0.3);
    font-size: 0.7rem;
    margin-top: 1.5rem;
  }
</style>
</head>
<body>
<div class="login-container">
  <div class="login-icon">☕</div>
  <div class="login-title">Admin Panel</div>
  <div class="login-sub">Masuk untuk mengelola website</div>

  <?php if(isset($_GET['error'])): ?>
  <div class="error-message">⚠️ Username atau password salah</div>
  <?php endif; ?>

  <form action="process_admin.php" method="POST">
    <input type="hidden" name="action" value="login">
    <div class="form-group">
      <label class="form-label">Username</label>
      <input type="text" name="username" class="form-control" placeholder="admin" required>
    </div>
    <div class="form-group">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control" placeholder="••••••••" required>
    </div>
    <button type="submit" class="btn-login">Masuk →</button>
    <div class="login-hint">Default: admin / kopi123</div>
  </form>
</div>
</body>
</html>
