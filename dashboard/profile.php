<?php
session_start();
include('../includes/header.php');
include('../includes/db.php');
$msg = '';
if (!isset($_SESSION['user_id'])) {
  header('Location: ../auth/login.php');
  exit();
}
$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT username, email FROM users WHERE id = $user_id")->fetch_assoc();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
  $password_raw = $_POST['password'] ?? '';
  if (!$username || !$email) {
    $msg = "Please enter a valid username and email.";
  } else {
    if ($password_raw && strlen($password_raw) >= 6) {
      $password = password_hash($password_raw, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("UPDATE users SET username=?, email=?, password=? WHERE id=?");
      $stmt->bind_param("sssi", $username, $email, $password, $user_id);
    } else {
      $stmt = $conn->prepare("UPDATE users SET username=?, email=? WHERE id=?");
      $stmt->bind_param("ssi", $username, $email, $user_id);
    }
    if ($stmt->execute()) {
      $msg = "Profile updated successfully!";
      $user['username'] = $username;
      $user['email'] = $email;
    } else {
      $msg = "Failed to update profile. Please try again.";
    }
  }
}
?>
<link rel="stylesheet" href="/CSS/style.css">
<div class="section" style="max-width:500px;">
  <h2>Edit Profile</h2>
  <form method="post">
    <input name="username" value="<?= htmlspecialchars($user['username']) ?>" placeholder="Username" required>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" placeholder="Email" required>
    <input type="password" name="password" placeholder="New Password (leave blank to keep current)">
    <button type="submit">Update Profile</button>
    <?php if ($msg): ?>
      <div class="alert-info" style="margin-top:12px;"> <?= $msg ?> </div>
    <?php endif; ?>
  </form>
</div>
<?php include('../includes/footer.php'); ?>
