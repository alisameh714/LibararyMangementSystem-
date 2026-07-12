<?php
require '../includes/auth.php';
require '../includes/db.php';
require '../includes/csrf.php';

if ($_SESSION['user_role'] !== 'admin') {
    die('❌ Unauthorized Access');
}

// Handle form submissions for book actions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_id'])) {
    verify_csrf();
    $book_id = intval($_POST['book_id']);
    $action  = htmlspecialchars($_POST['action']);

    if ($action === 'approve') {
        $stmt = $conn->prepare("UPDATE books SET status = 'approved' WHERE id = ?");
        $stmt->execute([$book_id]);

    } elseif ($action === 'reject') {
        $stmt = $conn->prepare("UPDATE books SET status = 'rejected' WHERE id = ?");
        $stmt->execute([$book_id]);

    } elseif ($action === 'delete') {
        $fstmt = $conn->prepare("SELECT book_file, book_image FROM books WHERE id = ?");
        $fstmt->execute([$book_id]);
        $bk = $fstmt->fetch(PDO::FETCH_ASSOC);
        if ($bk) {
            $root = __DIR__ . '/../';
            if (!empty($bk['book_file']))  @unlink($root . $bk['book_file']);
            if (!empty($bk['book_image'])) @unlink($root . $bk['book_image']);
        }
        $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
        $stmt->execute([$book_id]);
    }

    header('Location: manage_books.php' . ($status !== 'all' ? '?status=' . urlencode($_GET['status'] ?? '') : ''));
    exit();
}

// Fetch books based on the selected status filter
$status = isset($_GET['status']) ? htmlspecialchars($_GET['status']) : 'all';
$query  = "SELECT * FROM books";
$params = [];

if ($status !== 'all') {
    $query   .= " WHERE status = ?";
    $params[] = $status;
}

$stmt = $conn->prepare($query);
$stmt->execute($params);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Books | Admin</title>
    <link rel="stylesheet" href="../assets/css/includes/header.css">
    <link rel="stylesheet" href="../assets/css/admin/manage_books.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <?php require '../includes/header.php'; ?>

    <div class="manage-books-container">
        <h2><i class="fas fa-book"></i> Manage Books</h2>

        <!-- Status filter -->
        <div class="filter-bar">
            <form method="GET">
                <label for="status-filter"><i class="fas fa-filter"></i> Filter by Status:</label>
                <select id="status-filter" name="status" onchange="this.form.submit()">
                    <option value="all"     <?= $status === 'all'      ? 'selected' : '' ?>>All Books</option>
                    <option value="approved"<?= $status === 'approved' ? 'selected' : '' ?>>✅ Approved</option>
                    <option value="pending" <?= $status === 'pending'  ? 'selected' : '' ?>>⏳ Pending</option>
                    <option value="rejected"<?= $status === 'rejected' ? 'selected' : '' ?>>❌ Rejected</option>
                </select>
                <span class="book-count"><?= count($books) ?> book(s) found</span>
            </form>
        </div>

        <!-- Book table -->
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Cover</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Language</th>
                        <th>Pages</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($books)): ?>
                        <tr>
                            <td colspan="9" class="no-results">
                                <i class="fas fa-search"></i>
                                <p>No books found for this filter.</p>
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($books as $book): ?>
                        <tr>
                            <!-- Cover Image -->
                            <td class="cover-cell">
                                <?php if (!empty($book['book_image'])): ?>
                                    <img src="../<?= htmlspecialchars(ltrim($book['book_image'], './')) ?>"
                                         alt="Cover of <?= htmlspecialchars($book['title']) ?>"
                                         class="book-thumb"
                                         onerror="this.src='../assets/uploads/images/default-cover.svg'; this.onerror=null;">
                                <?php else: ?>
                                    <div class="no-cover"><i class="fas fa-book"></i></div>
                                <?php endif; ?>
                            </td>

                            <!-- Title -->
                            <td class="title-cell">
                                <a href="../book_details.php?id=<?= $book['id'] ?>" class="title-link">
                                    <?= htmlspecialchars($book['title']) ?>
                                </a>
                            </td>

                            <td><?= htmlspecialchars($book['author']) ?></td>
                            <td><?= htmlspecialchars($book['category']) ?></td>
                            <td><?= htmlspecialchars($book['language']) ?></td>
                            <td><?= htmlspecialchars($book['pages']) ?></td>
                            <td>$<?= htmlspecialchars($book['price']) ?></td>

                            <!-- Status Badge -->
                            <td>
                                <span class="status-badge status-<?= $book['status'] ?>">
                                    <?= ucfirst($book['status']) ?>
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="actions-cell">
                                <!-- Edit Button -->
                                <a href="../user/edit_book.php?id=<?= $book['id'] ?>" class="btn btn-edit">
                                    <i class="fas fa-pencil-alt"></i> Edit
                                </a>

                                <!-- Delete Button -->
                                <form method="POST" style="display:inline;"
                                      onsubmit="return confirm('Are you sure you want to permanently delete this book?')">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                                    <button type="submit" name="action" value="delete" class="btn btn-delete">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </form>

                                <!-- Approve / Reject based on status -->
                                <?php if ($book['status'] === 'pending'): ?>
                                    <form method="POST" style="display:inline;">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                                        <button type="submit" name="action" value="approve" class="btn btn-approve">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button type="submit" name="action" value="reject" class="btn btn-reject">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </form>
                                <?php elseif ($book['status'] === 'approved'): ?>
                                    <form method="POST" style="display:inline;">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                                        <button type="submit" name="action" value="reject" class="btn btn-reject">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </form>
                                <?php elseif ($book['status'] === 'rejected'): ?>
                                    <form method="POST" style="display:inline;">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                                        <button type="submit" name="action" value="approve" class="btn btn-approve">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <a href="../dashboard.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</body>

</html>
