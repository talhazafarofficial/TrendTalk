<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start(); 
}
include('./includes/header.php');
?>

<!-- Google Fonts: Montserrat -->
<link href="https://fonts.googleapis.com/css?family=Montserrat:400,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/CSS/style.css">
<link rel="icon" type="image/png" href="/logo.png">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php
include('./includes/db.php');

$topics = $conn->query("SELECT topics.*, users.username FROM topics JOIN users ON topics.created_by = users.id ORDER BY created_at DESC");
?>

<?php if (!isset($_SESSION['user_id'])): ?>
  <div class="main-landing">
    <h1>TrendTalk <span class="highlight">— Trading Community Platforms</span></h1>
    <p class="subtitle">Join discussions on the latest market trends and share your suggestions with other traders.</p>
      <a href="/auth/register.php" class="btn-primary">Get Started</a>
  </div>
<?php endif; ?>

<div class="section" style="margin-top:32px;">
  <h2>All Discussions & Topics</h2>

  <?php while ($row = $topics->fetch_assoc()): ?>
    <div class="topic-card" style="text-align:left; margin-bottom:24px;">
      <strong><?= htmlspecialchars($row['title']) ?></strong>
      <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
      <p><small>Posted by <?= htmlspecialchars($row['username']) ?> on <?= $row['created_at'] ?></small></p>
      <a href="/post/view_topic.php?id=<?= $row['id'] ?>" class="btn-primary" style="padding:8px 18px; font-size:0.95rem;">View & Comment</a>
    </div>
  <?php endwhile; ?>

  <!-- <?php if (!isset($_SESSION['user_id'])): ?>
    <div class="alert-info" style="text-align:center; margin-top:24px; background:#e3f0ff; color:#0077ff; padding:14px 0; border-radius:8px; font-weight:500;">
      To comment on any topic, <a href="auth/login.php" style="text-decoration: none; color: inherit">Login</a> first.
    </div>
  <?php endif; ?> -->
</div>

<?php include('./includes/footer.php'); ?>
