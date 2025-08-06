<?php
session_start();
include('../includes/header.php');
include('../includes/db.php');
if (!isset($_SESSION['user_id'])) {
  header("Location: ../auth/login.php");
  exit();
}
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/CSS/style.css">
<?php
// Fetch user info
$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT username, email, role, created_at FROM users WHERE id = $user_id")->fetch_assoc();

// Fetch user's comments
$comments = $conn->query("SELECT comments.*, topics.title FROM comments JOIN topics ON comments.topic_id = topics.id WHERE comments.user_id = $user_id ORDER BY comments.created_at DESC");

// Display topics
$result = $conn->query("SELECT topics.*, users.username FROM topics JOIN users ON topics.created_by = users.id ORDER BY created_at DESC");
?>

<div class="section">
  <h2>Welcome to your Dashboard</h2>
  <div class="topic-card" style="margin-bottom:32px;">
    <h3>Your Information</h3>
    <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Member Since:</strong> <?= htmlspecialchars($user['created_at']) ?></p>
    <a href="/dashboard/profile.php" class="btn-primary" style="margin-top:12px;">Edit Profile</a>
  </div>
  <div class="topic-card" style="margin-bottom:32px;">
    <h3>Your Comments</h3>
    <?php if ($comments->num_rows > 0): ?>
      <?php while ($c = $comments->fetch_assoc()): ?>
        <div style="margin-bottom:18px;">
          <strong>On topic:</strong> <?= htmlspecialchars($c['title']) ?><br>
          <span><?= nl2br(htmlspecialchars($c['comment'])) ?></span><br>
          <small><?= $c['created_at'] ?></small>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No comments yet.</p>
    <?php endif; ?>
  </div>
  <!-- Trending topics removed for logged-in user dashboard -->
</div>