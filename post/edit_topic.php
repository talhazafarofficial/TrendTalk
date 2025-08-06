<?php
include('../includes/db.php');
include('../includes/header.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
  header('Location: /auth/login.php');
  exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
  echo '<div class="container">Invalid topic ID.</div>';
  include('../includes/footer.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'];
  $description = $_POST['description'];
  $stmt = $conn->prepare('UPDATE topics SET title=?, description=? WHERE id=?');
  $stmt->bind_param('ssi', $title, $description, $id);
  if ($stmt->execute()) {
    header('Location: /dashboard/admin.php');
    exit();
  } else {
    $msg = 'Update failed.';
  }
}

$stmt = $conn->prepare('SELECT * FROM topics WHERE id=?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$topic = $res->fetch_assoc();
if (!$topic) {
  echo '<div class="container">Topic not found.</div>';
  include('../includes/footer.php');
  exit();
}
?>
<div class="container">
  <h2>Edit Topic</h2>
  <form method="post">
    <input type="text" name="title" value="<?= htmlspecialchars($topic['title']) ?>" required>
    <textarea name="description" required><?= htmlspecialchars($topic['description']) ?></textarea>
    <button type="submit">Update</button>
    <p><?= $msg ?? '' ?></p>
  </form>
</div>
<?php include('../includes/footer.php'); ?>
