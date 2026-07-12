<?php
require 'includes/auth.php';
require 'includes/db.php';
require 'includes/csrf.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('❌ Invalid Book ID');
}
$book_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$book_id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) { die('❌ Book Not Found'); }

if ($_SESSION['user_role'] !== 'admin' && $book['status'] !== 'approved') {
    die('❌ This book is not available.');
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {
    verify_csrf();
    $comment = htmlspecialchars($_POST['comment']);
    $user_id = $_SESSION['user_id'];
    if (!empty($comment)) {
        try {
            $stmt = $conn->prepare("INSERT INTO comments (book_id, user_id, comment) VALUES (?, ?, ?)");
            $stmt->execute([$book_id, $user_id, $comment]);
            header("Location: book_details.php?id=$book_id&comment=success");
            exit();
        } catch (PDOException $e) {
            $comment_error = '❌ Could not save comment. Please try again.';
        }
    } else {
        $comment_error = '❌ Comment cannot be empty.';
    }
}

// Fetch comments
$stmt = $conn->prepare("
    SELECT comments.comment, comments.created_at, users.username
    FROM comments
    JOIN users ON comments.user_id = users.id
    WHERE comments.book_id = ?
    ORDER BY comments.created_at DESC
");
$stmt->execute([$book_id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($book['title']) ?> | Library</title>
    <link rel="stylesheet" href="assets/css/includes/header.css">
    <link rel="stylesheet" href="assets/css/book_details.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php require 'includes/header.php'; ?>

    <div class="container">

        <!-- ── Book Hero ── -->
        <div class="book-hero">
            <div class="book-hero-banner"></div>
            <div class="book-hero-body">

            <!-- Left: Cover Image -->
            <div class="book-cover-wrap">
                    <img src="<?= !empty($book['book_image']) ? htmlspecialchars($book['book_image']) : './assets/uploads/images/default-cover.svg' ?>"
                         alt="Cover of <?= htmlspecialchars($book['title']) ?>"
                         class="book-cover-img"
                         onerror="this.src='./assets/uploads/images/default-cover.svg'; this.onerror=null;">

                <?php if (!empty($book['book_file'])): ?>
                    <a href="<?= htmlspecialchars($book['book_file']) ?>" class="download-btn" download>
                        <i class="fas fa-download"></i> Download Book
                    </a>
                <?php endif; ?>
            </div>

            <!-- Right: Details -->
            <div class="book-info">
                <h1 class="book-title"><?= htmlspecialchars($book['title']) ?></h1>
                <p class="book-author"><i class="fas fa-user-edit"></i> <?= htmlspecialchars($book['author']) ?></p>

                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                    <span class="status-badge status-<?= $book['status'] ?>">
                        <?= ucfirst($book['status']) ?>
                    </span>
                <?php endif; ?>

                <?php if (!empty($book['description'])): ?>
                    <p class="book-description"><?= htmlspecialchars($book['description']) ?></p>
                <?php endif; ?>

                <div class="meta-grid">
                    <div class="meta-item">
                        <i class="fas fa-bookmark"></i>
                        <span class="meta-label">Category</span>
                        <span class="meta-value"><?= htmlspecialchars($book['category']) ?></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-globe"></i>
                        <span class="meta-label">Language</span>
                        <span class="meta-value"><?= htmlspecialchars($book['language']) ?></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-file-alt"></i>
                        <span class="meta-label">Pages</span>
                        <span class="meta-value"><?= htmlspecialchars($book['pages']) ?></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-dollar-sign"></i>
                        <span class="meta-label">Price</span>
                        <span class="meta-value price">$<?= number_format($book['price'], 2) ?></span>
                    </div>
                </div>
            </div>
            </div><!-- end book-hero-body -->
        </div>

        <!-- ── Comments ── -->
        <div class="comments-section">

            <!-- Add Comment -->
            <div class="add-comment-card">
                <h3><i class="fas fa-comment-dots"></i> Leave a Comment</h3>
                <?php if (isset($_GET['comment']) && $_GET['comment'] === 'success'): ?>
                    <div class="alert alert-success"><i class="fas fa-check-circle"></i> Comment added successfully!</div>
                <?php endif; ?>
                <?php if (!empty($comment_error)): ?>
                    <div class="alert alert-error"><?= $comment_error ?></div>
                <?php endif; ?>
                <form method="POST" class="comment-form">
                    <?= csrf_field() ?>
                    <textarea name="comment" placeholder="Share your thoughts about this book..." required></textarea>
                    <button type="submit"><i class="fas fa-paper-plane"></i> Post Comment</button>
                </form>
            </div>

            <!-- Comments List -->
            <div class="comments-list-card">
                <h3><i class="fas fa-comments"></i> Comments
                    <span class="comment-count"><?= count($comments) ?></span>
                </h3>

                <?php if (empty($comments)): ?>
                    <div class="no-comments">
                        <i class="fas fa-comment-slash"></i>
                        <p>No comments yet. Be the first to comment!</p>
                    </div>
                <?php else: ?>
                    <ul class="comment-list">
                        <?php foreach ($comments as $c): ?>
                            <li class="comment-item">
                                <div class="comment-avatar">
                                    <?= strtoupper(substr($c['username'], 0, 1)) ?>
                                </div>
                                <div class="comment-body">
                                    <div class="comment-header">
                                        <strong><?= htmlspecialchars($c['username']) ?></strong>
                                        <small><?= htmlspecialchars($c['created_at']) ?></small>
                                    </div>
                                    <p><?= htmlspecialchars($c['comment']) ?></p>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <a href="books.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Books
        </a>
    </div>
</body>
</html>
