<?php
// admin_users.php - Admin page to manage users
session_start();
require_once '../includes/db.php';

// Only allow admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /index.php');
    exit();
}

// Handle user deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $user_id = intval($_GET['delete']);
    // Delete user and their comments
    $stmt = $conn->prepare('DELETE FROM comments WHERE user_id = ?');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt = $conn->prepare('DELETE FROM users WHERE id = ?');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    header('Location: admin_users.php');
    exit();
}

// Handle username edit
if (isset($_POST['edit_id']) && isset($_POST['new_username'])) {
    $edit_id = intval($_POST['edit_id']);
    $new_username = trim($_POST['new_username']);
    if ($new_username !== '') {
        $stmt = $conn->prepare('UPDATE users SET username = ? WHERE id = ?');
        $stmt->bind_param('si', $new_username, $edit_id);
        $stmt->execute();
    }
    header('Location: admin_users.php');
    exit();
}

// Fetch all users and their comment counts
$sql = 'SELECT u.id, u.username, u.email, COUNT(c.id) AS comment_count FROM users u LEFT JOIN comments c ON u.id = c.user_id GROUP BY u.id';
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - User Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/trendtalk/CSS/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <div class="section">
        <h2 class="center">User Information</h2>
        <table style="width:100%;margin-top:24px;background:#fff;border-radius:10px;box-shadow:0 2px 12px rgba(0,0,0,0.06);">
            <thead>
                <tr style="background:#f7f9fb;">
                    <th style="padding:12px;">Username</th>
                    <th style="padding:12px;">Email</th>
                    <th style="padding:12px;">Comments</th>
                    <th style="padding:12px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td style="padding:12px;">
                        <form method="post" style="display:inline-flex;align-items:center;gap:8px;">
                            <input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>">
                            <input type="text" name="new_username" value="<?php echo htmlspecialchars($row['username']); ?>" style="width:120px;padding:6px 8px;font-size:1rem;">
                            <button type="submit" class="btn-primary" style="padding:6px 14px;font-size:0.95rem;">Edit</button>
                        </form>
                    </td>
                    <td style="padding:12px;"><?php echo htmlspecialchars($row['email']); ?></td>
                    <td style="padding:12px;" class="center"><?php echo $row['comment_count']; ?></td>
                    <td style="padding:12px;">
                        <a href="admin_users.php?delete=<?php echo $row['id']; ?>" class="btn-primary" style="background:#ff4d4f;padding:6px 14px;font-size:0.95rem;" onclick="return confirm('Delete this user? This will also delete their comments.');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
