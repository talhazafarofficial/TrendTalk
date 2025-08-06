<?php
include('/includes/db.php');
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
  header('Location: /auth/login.php');
  exit();
$msg = '';
$id = $_POST['id'] ?? null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if ($id) {
    $stmt = $conn->prepare('DELETE FROM topics WHERE id=?');
    if ($stmt) {
      } else {
        $msg = 'Failed to delete topic. SQL error: ' . $stmt->error;
      }
    } else {
      $msg = 'Prepare failed: ' . $conn->error;
    }
  } else {
    $msg = 'No topic ID provided.';
  }
}
?>
<?php include('../includes/header.php'); ?>
<div class="container">
  <h2>Delete Topic</h2>
  <p>Topic ID: <?= htmlspecialchars($id) ?></p>
  <p><?= htmlspecialchars($msg) ?></p>
  <a href="/dashboard/admin.php" class="btn-primary">Back to Admin Panel</a>
</div>
<?php include('../includes/footer.php'); ?>
<?php
include('/includes/db.php');
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
  header('Location: /auth/login.php');
  exit();
}

$msg = '';
$id = $_POST['id'] ?? null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if ($id) {
    $stmt = $conn->prepare('DELETE FROM topics WHERE id=?');
    if ($stmt) {
      $stmt->bind_param('i', $id);
      if ($stmt->execute()) {
        $msg = 'Topic deleted successfully.';
      } else {
        $msg = 'Failed to delete topic. SQL error: ' . $stmt->error;
      }
    } else {
      $msg = 'Prepare failed: ' . $conn->error;
    }
  } else {
    $msg = 'No topic ID provided.';
  }
}
?>
<?php include('../includes/header.php'); ?>
<div class="container">
  <h2>Delete Topic</h2>
  <p>Topic ID: <?= htmlspecialchars($id) ?></p>
  <p><?= htmlspecialchars($msg) ?></p>
  <a href="/dashboard/admin.php" class="btn-primary">Back to Admin Panel</a>
</div>
<?php include('/includes/footer.php'); ?>
