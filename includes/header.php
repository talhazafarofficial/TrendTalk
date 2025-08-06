<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TrendTalk</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/CSS/style.css">
  <link rel="icon" type="image/png" href="/logo.png">
  <script src="/js/main.js" defer></script>
</head>
<body>
<header>
  <!-- Responsive Navbar for TrendTalk -->
  <nav class="navbar">
    <div class="navbar-brand">
      <a href="/index.php">TrendTalk</a>
    </div>
    <input type="checkbox" id="navbar-toggle" class="navbar-toggle">
    <label for="navbar-toggle" class="navbar-icon">
      <span></span>
      <span></span>
      <span></span>
    </label>
    <ul class="navbar-links">
      <li><a href="/index.php">Home</a></li>
      <li><a href="/helpcenter/index.php">Help Center</a></li>
      <li><a href="/about/index.php">About Us</a></li>
      <li><a href="/dashboard/user.php">Dashboard</a></li>
      <?php if (isset($_SESSION['user_id'])): ?>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
          <li><a href="/dashboard/admin.php">Admin Panel</a></li>
          <li><a href="/dashboard/admin_users.php">User Information</a></li>
        <?php endif; ?>
        <li><a href="/auth/logout.php" style="color: #ff4d4f;">Logout</a></li>
      <?php else: ?>
        <li><a href="/auth/login.php">Login</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>