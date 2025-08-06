<?php
include('../includes/db.php');
include('../includes/header.php');

if (!isset($_SESSION['user_id'])) {
  header("Location: /auth/login.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

$query = $role == 'admin'
  ? "SELECT activity_log.*, users.username FROM activity_log JOIN users ON activity_log.user_id = users.id ORDER BY created_at DESC"
  : "SELECT * FROM activity_log WHERE user_id = $user_id ORDER BY created_at DESC";

$result = $conn->query($query);
?>

<div class="container">
  <h2>Activity Log</h2>
  <?php while ($log = $result->fetch_assoc()): ?>
    <div>
      <p>
        <?= isset($log['username']) ? "<strong>{$log['username']}</strong>: " : '' ?>
        <?= htmlspecialchars($log['action']) ?>
        <br><small><?= $log['created_at'] ?></small>
      </p>
    </div>
    <hr>
  <?php endwhile; ?>
</div>

<?php include('../includes/footer.php'); ?>