<?php
require '../includes/auth.php';
require '../includes/db.php';
require '../includes/csrf.php';

if ($_SESSION['user_role'] !== 'admin') {
    die('❌ Unauthorized Access');
}

// Handle deletion first
if (isset($_POST['delete_comment'])) {
    verify_csrf();
    $comment_id = intval($_POST['comment_id']);
    $stmt = $conn->prepare("DELETE FROM comments WHERE id = ?");
    $stmt->execute([$comment_id]);
    header('Location: manage_comments.php');
    exit();
}

// Fetch all comments with user and book info
$stmt = $conn->prepare("
    SELECT comments.id, comments.comment, comments.created_at,
           users.username, books.title AS book_title
    FROM comments
    JOIN users ON comments.user_id = users.id
    JOIN books ON comments.book_id = books.id
    ORDER BY comments.created_at DESC
");
$stmt->execute();
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Comments | Admin</title>
    <link rel="stylesheet" href="../assets/css/includes/header.css">
    <link rel="stylesheet" href="../assets/css/admin/manage_comments.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php require '../includes/header.php'; ?>
    <div class="manage-comments-container">
        <h2><i class="fas fa-comments"></i> Manage Comments</h2>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Book</th>
                        <th>Comment</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($comments)): ?>
                        <tr class="empty-row">
                            <td colspan="5"><i class="fas fa-comment-slash" style="font-size:2rem;display:block;margin-bottom:.5rem;"></i> No comments found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($comments as $c): ?>
                            <tr>
                                <td>
                                    <div class="user-cell">
                                        <div class="user-avatar">
                                            <?= strtoupper(substr($c['username'], 0, 1)) ?>
                                        </div>
                                        <strong><?= htmlspecialchars($c['username']) ?></strong>
                                    </div>
                                </td>
                                <td class="book-name"><?= htmlspecialchars($c['book_title']) ?></td>
                                <td class="comment-text"><?= htmlspecialchars($c['comment']) ?></td>
                                <td class="date-text"><?= htmlspecialchars($c['created_at']) ?></td>
                                <td>
                                    <form method="POST"
                                          onsubmit="return confirm('Delete this comment?')">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="comment_id" value="<?= $c['id'] ?>">
                                        <button type="submit" name="delete_comment" class="btn-delete">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <a href="../dashboard.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</body>
</html>
