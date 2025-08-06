<?php
include('../includes/db.php');
$msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $role = 'user';
  
  // Check if the account already exists
  $checkStmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $checkStmt->bind_param("s", $email);
  $checkStmt->execute();
  $result = $checkStmt->get_result();

  if ($result->num_rows > 0) {
    $msg = "Account already exists. <a href='login.php'>Login</a>";
  } else {
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $password, $role);

    if ($stmt->execute()) {
      $msg = "Registration successful! Redirecting to login...";
      header("refresh:2;url=login.php");
      exit();
    } else {
      $msg = "Error: " . $stmt->error;
    }
  }
}
?>

<?php include('../includes/header.php'); ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/CSS/style.css">
<div class="container2">
  <h2>Register</h2>
  
  <form method="post">
    <input name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <div style="position:relative;">
      <input type="password" name="password" id="reg_password" placeholder="Password" required style="padding-right:40px;">
      <span onclick="toggleRegPassword()" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer; color:#0077ff; font-size:1.1rem;">
        <svg id="regEyeIcon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-width="2" d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/></svg>
      </span>
    </div>
    <div class="test">
      <button type="submit" style="width: 100%;">Register</button>
    </div>
  </form>
  <p style="margin-top:16px;">Already have an account? <a href="/auth/login.php" >Login here</a></p>
  <?php if (!empty($msg)): ?>
    <div class="alert-info" style="margin-bottom:16px; text-align:center;"> <?= $msg ?> </div>
  <?php endif; ?>
<script>
function toggleRegPassword() {
  var pwd = document.getElementById('reg_password');
  var icon = document.getElementById('regEyeIcon');
  if (pwd.type === 'password') {
    pwd.type = 'text';
    icon.innerHTML = '<path stroke="currentColor" stroke-width="2" d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/><line x1="4" y1="4" x2="20" y2="20" stroke="currentColor" stroke-width="2"/>';
  } else {
    pwd.type = 'password';
    icon.innerHTML = '<path stroke="currentColor" stroke-width="2" d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>';
  }
}
</script>
</div>