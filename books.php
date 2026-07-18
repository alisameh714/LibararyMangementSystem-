<?php
require 'includes/auth.php';
require 'includes/db.php';

$search    = $_GET['search']    ?? '';
$category  = $_GET['category']  ?? '';
$language  = $_GET['language']  ?? '';
$status    = $_GET['status']    ?? '';
$min_pages = $_GET['min_pages'] ?? '';
$max_pages = $_GET['max_pages'] ?? '';
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';

$isAdmin = $_SESSION['user_role'] === 'admin';

$categories = $conn->query("SELECT DISTINCT category FROM books" . ($isAdmin ? '' : " WHERE status='approved'"))->fetchAll(PDO::FETCH_COLUMN);
$languages  = $conn->query("SELECT DISTINCT language FROM books"  . ($isAdmin ? '' : " WHERE status='approved'"))->fetchAll(PDO::FETCH_COLUMN);

$query  = $isAdmin ? "SELECT * FROM books WHERE 1=1" : "SELECT * FROM books WHERE status = 'approved'";
$params = [];

if (!empty($search)) {
    $query .= " AND (title LIKE ? OR author LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if (!empty($category))                              { $query .= " AND category = ?";  $params[] = $category; }
if (!empty($language))                              { $query .= " AND language = ?";  $params[] = $language; }
if ($isAdmin && !empty($status))                    { $query .= " AND status = ?";    $params[] = $status; }
if (!empty($min_pages) && is_numeric($min_pages))   { $query .= " AND pages >= ?";   $params[] = $min_pages; }
if (!empty($max_pages) && is_numeric($max_pages))   { $query .= " AND pages <= ?";   $params[] = $max_pages; }
if (!empty($min_price) && is_numeric($min_price))   { $query .= " AND price >= ?";   $params[] = $min_price; }
if (!empty($max_price) && is_numeric($max_price))   { $query .= " AND price <= ?";   $params[] = $max_price; }

$stmt = $conn->prepare($query);
$stmt->execute($params);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Books | Library</title>
    <link rel="stylesheet" href="assets/css/includes/header.css">
    <link rel="stylesheet" href="assets/css/books.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php require 'includes/header.php'; ?>
    <div class="books-container">

        <div class="page-header">
            <h2><i class="fas fa-book-open"></i> Browse Books</h2>
            <p class="subtitle"><?= count($books) ?> book(s) found</p>
        </div>

        <!-- Filter Form -->
        <form method="GET" class="filter-form">
            <div class="filter-row">
                <div class="filter-field search-field">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" placeholder="Search by title or author..."
                           value="<?= htmlspecialchars($search) ?>">
                </div>

                <select name="category" class="filter-select">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat) ?>" <?= $category == $cat ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="language" class="filter-select">
                    <option value="">All Languages</option>
                    <?php foreach ($languages as $lang): ?>
                        <option value="<?= htmlspecialchars($lang) ?>" <?= $language == $lang ? 'selected' : '' ?>>
                            <?= htmlspecialchars($lang) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <?php if ($isAdmin): ?>
                    <select name="status" class="filter-select">
                        <option value="">All Status</option>
                        <option value="approved" <?= $status == 'approved' ? 'selected' : '' ?>>✅ Approved</option>
                        <option value="pending"  <?= $status == 'pending'  ? 'selected' : '' ?>>⏳ Pending</option>
                        <option value="rejected" <?= $status == 'rejected' ? 'selected' : '' ?>>❌ Rejected</option>
                    </select>
                <?php endif; ?>
            </div>

            <div class="filter-row range-row">
                <div class="range-group">
                    <label><i class="fas fa-file-alt"></i> Pages</label>
                    <input type="number" name="min_pages" placeholder="Min" value="<?= htmlspecialchars($min_pages) ?>">
                    <span>–</span>
                    <input type="number" name="max_pages" placeholder="Max" value="<?= htmlspecialchars($max_pages) ?>">
                </div>
                <div class="range-group">
                    <label><i class="fas fa-dollar-sign"></i> Price</label>
                    <input type="number" name="min_price" placeholder="Min" step="0.01" value="<?= htmlspecialchars($min_price) ?>">
                    <span>–</span>
                    <input type="number" name="max_price" placeholder="Max" step="0.01" value="<?= htmlspecialchars($max_price) ?>">
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn-search"><i class="fas fa-search"></i> Search</button>
                    <a href="books.php" class="btn-clear"><i class="fas fa-times"></i> Clear</a>
                </div>
            </div>
        </form>

        <!-- Books Table -->
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
                        <?php if ($isAdmin): ?><th>Status</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($books)): ?>
                        <tr>
                            <td colspan="<?= $isAdmin ? 8 : 7 ?>" class="no-results">
                                <i class="fas fa-search"></i>
                                <p>No books found. Try adjusting your search or filters.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($books as $book): ?>
                            <tr>
                                <td class="cover-cell">
                                    <img src="<?= !empty($book['book_image']) ? htmlspecialchars($book['book_image'] ?? '') : './assets/uploads/images/default-cover.svg' ?>"
                                         alt="Cover" class="book-thumb"
                                         onerror="this.src='./assets/uploads/images/default-cover.svg'; this.onerror=null;">
                                </td>
                                <td class="title-cell">
                                    <a href="book_details.php?id=<?= $book['id'] ?>" class="title-link">
                                        <?= htmlspecialchars($book['title'] ?? '') ?>
                                    </a>
                                </td>
                                <td><?= htmlspecialchars($book['author'] ?? '') ?></td>
                                <td><span class="tag"><?= htmlspecialchars($book['category'] ?? '') ?></span></td>
                                <td><?= htmlspecialchars($book['language'] ?? '') ?></td>
                                <td><?= htmlspecialchars($book['pages'] ?? '') ?></td>
                                <td class="price">$<?= number_format($book['price'], 2) ?></td>
                                <?php if ($isAdmin): ?>
                                    <td>
                                        <span class="status-badge status-<?= $book['status'] ?>">
                                            <?= ucfirst($book['status']) ?>
                                        </span>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <a href="dashboard.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</body>
</html>
