<?php
require '../includes/auth.php';
require '../includes/db.php';
require '../includes/csrf.php';

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM books WHERE uploaded_by = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Books | Library</title>
    <link rel="stylesheet" href="../assets/css/includes/header.css">
    <link rel="stylesheet" href="../assets/css/user/my_books.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php require '../includes/header.php'; ?>
    <div class="my-books-container">

        <div class="page-header">
            <h2><i class="fas fa-book"></i> My Uploaded Books</h2>
            <a href="upload_book.php" class="upload-btn">
                <i class="fas fa-plus"></i> Upload New Book
            </a>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Cover</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Pages</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($books)): ?>
                        <tr>
                            <td colspan="6" class="no-results">
                                <i class="fas fa-upload"></i>
                                <p>You haven't uploaded any books yet.
                                    <a href="upload_book.php">Upload one now</a>
                                </p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($books as $book): ?>
                            <tr>
                                <!-- Cover -->
                                <td class="cover-cell">
                                    <img src="../<?= !empty($book['book_image']) ? htmlspecialchars(ltrim($book['book_image'], './')) : 'assets/uploads/images/default-cover.svg' ?>"
                                         alt="Cover" class="book-thumb"
                                         onerror="this.src='../assets/uploads/images/default-cover.svg'; this.onerror=null;">
                                </td>
                                <!-- Title -->
                                <td class="title-cell">
                                    <a href="../book_details.php?id=<?= $book['id'] ?>" class="title-link">
                                        <?= htmlspecialchars($book['title']) ?>
                                    </a>
                                    <small class="author-sub"><?= htmlspecialchars($book['author']) ?></small>
                                </td>
                                <td><span class="tag"><?= htmlspecialchars($book['category']) ?></span></td>
                                <td><?= htmlspecialchars($book['pages']) ?> pg</td>
                                <!-- Status -->
                                <td>
                                    <span class="status-badge status-<?= $book['status'] ?>">
                                        <?= ucfirst($book['status']) ?>
                                    </span>
                                </td>
                                <!-- Actions -->
                                <td class="actions-cell">
                                    <?php if ($book['status'] === 'pending'): ?>
                                        <span class="pending-label"><i class="fas fa-clock"></i> Awaiting</span>
                                    <?php else: ?>
                                        <a href="edit_book.php?id=<?= $book['id'] ?>" class="btn btn-edit">
                                            <i class="fas fa-pencil-alt"></i> Edit
                                        </a>
                                    <?php endif; ?>
                                    <form method="POST" action="delete_book.php" style="display:inline;"
                                          onsubmit="return confirm('Delete this book permanently?')">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                                        <button type="submit" class="btn btn-delete">
                                            <i class="fas fa-trash-alt"></i>
                                            <?= $book['status'] === 'pending' ? 'Cancel' : 'Delete' ?>
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
