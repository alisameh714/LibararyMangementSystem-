<?php
require '../includes/auth.php';
require '../includes/db.php';
require '../includes/csrf.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die('❌ Unauthorized Access.');
}

// Handle POST first
if (isset($_POST['action']) && isset($_POST['book_id'])) {
    verify_csrf();
    $action  = $_POST['action'];
    $book_id = intval($_POST['book_id']);
    if (in_array($action, ['approved', 'rejected'])) {
        $stmt = $conn->prepare("UPDATE books SET status = ? WHERE id = ?");
        $stmt->execute([$action, $book_id]);
        header('Location: pending_books.php');
        exit();
    }
}

$stmt = $conn->prepare("SELECT * FROM books WHERE status = 'pending' ORDER BY created_at DESC");
$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Books | Admin</title>
    <link rel="stylesheet" href="../assets/css/includes/header.css">
    <link rel="stylesheet" href="../assets/css/admin/pending_books.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php require '../includes/header.php'; ?>
    <div class="pending-books-container">

        <div class="page-header">
            <h2><i class="fas fa-clock"></i> Pending Books</h2>
            <span class="book-count"><?= count($books) ?> awaiting review</span>
        </div>

        <?php if (empty($books)): ?>
            <div class="empty-state">
                <i class="fas fa-check-circle"></i>
                <h3>All Clear!</h3>
                <p>No books are currently pending approval.</p>
            </div>
        <?php else: ?>
            <div class="cards-grid">
                <?php foreach ($books as $book): ?>
                    <div class="book-card">
                        <!-- Cover -->
                        <div class="card-cover">
                                <img src="../<?= !empty($book['book_image']) ? htmlspecialchars(ltrim($book['book_image'], './')) : 'assets/uploads/images/default-cover.svg' ?>"
                                     alt="Cover of <?= htmlspecialchars($book['title']) ?>"
                                     onerror="this.src='../assets/uploads/images/default-cover.svg'; this.onerror=null;">
                        </div>
                        <!-- Info -->
                        <div class="card-info">
                            <h3 class="card-title">
                                <a href="../book_details.php?id=<?= $book['id'] ?>">
                                    <?= htmlspecialchars($book['title']) ?>
                                </a>
                            </h3>
                            <p class="card-author"><i class="fas fa-user-edit"></i> <?= htmlspecialchars($book['author']) ?></p>
                            <div class="card-meta">
                                <span><i class="fas fa-bookmark"></i> <?= htmlspecialchars($book['category']) ?></span>
                                <span><i class="fas fa-globe"></i> <?= htmlspecialchars($book['language']) ?></span>
                                <span><i class="fas fa-file-alt"></i> <?= htmlspecialchars($book['pages']) ?> pg</span>
                            </div>
                            <?php if (!empty($book['description'])): ?>
                                <p class="card-desc"><?= htmlspecialchars(mb_substr($book['description'], 0, 100)) ?>…</p>
                            <?php endif; ?>
                            <!-- Actions -->
                            <form method="POST" class="card-actions">
                                <?= csrf_field() ?>
                                <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                                <button type="submit" name="action" value="approved" class="btn btn-approve">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                                <button type="submit" name="action" value="rejected" class="btn btn-reject">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <a href="../dashboard.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</body>
</html>
