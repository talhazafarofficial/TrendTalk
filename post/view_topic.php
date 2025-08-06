<?php
include('../includes/db.php');
include('../includes/header.php');

if (!isset($_GET['id'])) {
  die("Topic ID missing.");
}

$topic_id = intval($_GET['id']);

// Fetch topic details
$topic_sql = "SELECT topics.*, users.username FROM topics 
              JOIN users ON topics.created_by = users.id 
              WHERE topics.id = $topic_id";
$topic = $conn->query($topic_sql)->fetch_assoc();

// Handle comment/reply submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
  $comment = htmlspecialchars($_POST['comment']);
  $user_id = $_SESSION['user_id'];
  $parent_id = $_POST['parent_id'] !== '' ? intval($_POST['parent_id']) : NULL;

  $stmt = $conn->prepare("INSERT INTO comments (topic_id, user_id, comment, parent_id) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("iisi", $topic_id, $user_id, $comment, $parent_id);
  $stmt->execute();

  // Add activity log
  $conn->query("INSERT INTO activity_log (user_id, action) VALUES ($user_id, 'Commented on topic: {$topic['title']}')");

  header("Location: view_topic.php?id=$topic_id");
  exit();
}

// Fetch top-level comments
$comments = $conn->query("
  SELECT c.*, u.username 
  FROM comments c 
  JOIN users u ON c.user_id = u.id 
  WHERE c.topic_id = $topic_id AND c.parent_id IS NULL 
  ORDER BY c.created_at DESC
");

// Helper to fetch replies
function getReplies($conn, $parent_id) {
  return $conn->query("
      SELECT c.*, u.username 
      FROM comments c 
      JOIN users u ON c.user_id = u.id 
      WHERE c.parent_id = $parent_id 
      ORDER BY c.created_at
  ");
}
?>
<link rel="stylesheet" href="/CSS/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<div class="container">
  <h2><?= htmlspecialchars($topic['title']) ?></h2>
  <p><?= nl2br(htmlspecialchars($topic['description'])) ?></p>
  <p><small>Posted by <?= htmlspecialchars($topic['username']) ?> on <?= $topic['created_at'] ?></small></p>
  <hr>

  <h3>Discussion</h3>

  <?php while ($comment = $comments->fetch_assoc()): ?>
    <div style="margin-bottom: 20px; border-left:3px solid #e3f0ff; padding-left:12px;">
      <strong><?= htmlspecialchars($comment['username']) ?>:</strong>
      <p><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
      <small><?= $comment['created_at'] ?></small>

      <!-- Reply form toggle -->
      <?php if (isset($_SESSION['user_id'])): ?>
        <details style="margin-top: 10px;">
          <summary style="cursor:pointer;">Reply</summary>
          <form method="POST" style="margin-top:10px;">
            <input type="hidden" name="parent_id" value="<?= $comment['id'] ?>">
            <textarea name="comment" placeholder="Write your reply" required></textarea>
            <button type="submit">Submit Reply</button>
          </form>
        </details>
      <?php endif; ?>

      <!-- Nested replies -->
      <?php $replies = getReplies($conn, $comment['id']); ?>
      <?php while ($reply = $replies->fetch_assoc()): ?>
        <div style="margin-left:24px; margin-top:10px; border-left:2px solid #dbeafe; padding-left:10px; background:#f7f9fb;">
          <strong><?= htmlspecialchars($reply['username']) ?>:</strong>
          <p><?= nl2br(htmlspecialchars($reply['comment'])) ?></p>
          <small><?= $reply['created_at'] ?></small>
        </div>
      <?php endwhile; ?>
    </div>
    <hr>
  <?php endwhile; ?>

  <?php if (isset($_SESSION['user_id'])): ?>
    <div style="padding: 20px;">
    <h3>Leave a Comment</h3>
    <form method="POST">
      <input type="hidden" name="parent_id" value="">
      <textarea name="comment" placeholder="Type your comment here..." required></textarea>
      <button type="submit">Post Comment</button>
    </form>
    </div>
  <?php else: ?>
    <p><a href="/auth/login.php">Login</a> to comment.</p>
  <?php endif; ?>

</div>

<?php include('../includes/footer.php'); ?>