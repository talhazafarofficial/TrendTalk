<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php
include('../includes/db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $res = $stmt->get_result();

  if ($res->num_rows === 1) {
    $user = $res->fetch_assoc();
    if (password_verify($password, $user['password'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['role'] = $user['role'];
      if ($user['role'] == 'admin') {
        header('Location: ../dashboard/admin.php');
      } else {
        header('Location: ../index.php');
      }
    } else {
      $msg = "Invalid Credentials.";
    }
  } else {
    $msg = "User not found.";
  }
}
?>

<?php include('../includes/header.php'); ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/CSS/style.css">
<div class="container2">
  <h2>Login</h2>
  <form method="post" novalidate>
    <input type="email" name="email" placeholder="Email" required pattern="^[^@\s]+@[^@\s]+\.[^@\s]+$" title="Please enter a valid email address">
    <div style="position:relative;">
      <input type="password" name="password" id="password" placeholder="Password" required minlength="6" title="Password must be at least 6 characters" style="padding-right:40px;">
      <span onclick="togglePassword()" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer; color:#0077ff; font-size:1.1rem;">
        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-width="2" d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/></svg>
      </span>
    </div>
    <div class="test">
      <button type="submit" style="width: 100%;">Login</button>
    </div>
  </form>
  <p style="margin-top:16px;">Don't have an account? <a href="/auth/register.php" >Sign up</a></p>
</div>
<?php if (!empty($msg)): ?>
    <div class="alert-info" style="margin-bottom:16px; text-align:center; color:red;"> <?= htmlspecialchars($msg) ?> </div>
  <?php endif; ?>
<script>
function togglePassword() {
  var pwd = document.getElementById('password');
  var icon = document.getElementById('eyeIcon');
  if (pwd.type === 'password') {
    pwd.type = 'text';
    icon.innerHTML = '<path stroke="currentColor" stroke-width="2" d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/><line x1="4" y1="4" x2="20" y2="20" stroke="currentColor" stroke-width="2"/>';
  } else {
    pwd.type = 'password';
    icon.innerHTML = '<path stroke="currentColor" stroke-width="2" d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>';
  }
}
</script>