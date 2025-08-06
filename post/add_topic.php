<?php
include('../includes/db.php');
include('../includes/header.php');

if ($_SESSION['role'] != 'admin') {
  die("Access denied.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'];
  $desc = $_POST['description'];
  $admin_id = $_SESSION['user_id'];

  $stmt = $conn->prepare("INSERT INTO topics (title, description, created_by) VALUES (?, ?, ?)");
  $stmt->bind_param("ssi", $title, $desc, $admin_id);
  $stmt->execute();

  $conn->query("INSERT INTO activity_log (user_id, action) VALUES ($admin_id, 'Created a new topic: $title')");
  header("Location: /dashboard/admin.php");
  exit();
}
?>
<div class="container">
  <h2>Add Topic</h2>
  <form method="post">
    <input type="text" name="title" placeholder="Topic Title" required>
    <textarea name="description" placeholder="Description" required></textarea>
    <button type="submit">Post Topic</button>
  </form>
</div>
<?php include('../includes/footer.php'); ?>