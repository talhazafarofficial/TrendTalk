<?php
include('../includes/db.php');
include('../includes/header.php');

// Access control
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
  header("Location: ../auth/login.php");
  exit();
}

// Fetch all topics
$result = $conn->query("SELECT topics.*, users.username FROM topics JOIN users ON topics.created_by = users.id ORDER BY created_at DESC");
?>


<div class="container">
  <h2>Admin Dashboard</h2>

  <a href="/post/add_topic.php"><button>Add Trending Topic</button></a>
  <h3>All Topics</h3>
  <?php while ($row = $result->fetch_assoc()): ?>
    <div style="margin-bottom:18px;">
      <strong><?= htmlspecialchars($row['title']) ?></strong>
      <p><?= htmlspecialchars($row['description']) ?></p>
      <p>By <?= htmlspecialchars($row['username']) ?> on <?= $row['created_at'] ?></p>
      <a href="/post/view_topic.php?id=<?= $row['id'] ?>">View Discussion</a>
      <a href="/post/edit_topic.php?id=<?= $row['id'] ?>" class="btn-primary" style="margin-left:8px;">Edit</a>
      <form action="/post/delete_topic.php" method="post" style="display:inline; margin-left:8px;" onsubmit="return confirm('Are you sure you want to delete this topic?');">
        <input type="hidden" name="id" value="<?= $row['id'] ?>">
        <button type="submit" class="btn-primary" style="background:#ff4d4f;">Delete</button>
      </form>
    </div>
    <hr>
  <?php endwhile; ?>
</div>

<?php include('../includes/footer.php'); ?>