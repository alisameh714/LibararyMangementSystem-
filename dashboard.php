<?php
require 'includes/auth.php';
require 'includes/db.php';

// Admin Stats
if ($_SESSION['user_role'] === 'admin') {
    $total_users = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn(); // Total Users:
    $total_books = $conn->query("SELECT COUNT(*) FROM books")->fetchColumn(); // Total Books
    $pending_books = $conn->query("SELECT COUNT(*) FROM books WHERE status = 'pending'")->fetchColumn(); // Pending Books
    $approved_books = $conn->query("SELECT COUNT(*) FROM books WHERE status = 'approved'")->fetchColumn(); // Approved Books
    $rejected_books = $conn->query("SELECT COUNT(*) FROM books WHERE status = 'rejected'")->fetchColumn(); // Rejected Books: 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Ali Edris - 220209960 - Uskudar University">
    <title>Dashboard | Library Management System</title>
    <link rel="stylesheet" href="assets/css/includes/header.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
</head>

<body>
    <?php require 'includes/header.php'; ?>
    <div class="container">
        <?php
            $hour = (int)date('H');
            $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
        ?>
        <?php if ($_SESSION['user_role'] === 'admin'): ?>
            <header class="dashboard-header">
                <div class="greeting-badge"><i class="fas fa-shield-alt"></i> Admin</div>
                <h1><?= $greeting ?>, <span><?= htmlspecialchars($_SESSION['username']) ?></span> 👋</h1>
                <p class="subtitle">Here's what's happening in your library today.</p>
            </header>

            <div class="stats-grid">
                <div class="stat-card users">
                    <i class="fas fa-users"></i>
                    <h3>Total Users</h3>
                    <p class="stat-number"><?= $total_users ?></p>
                </div>
                <div class="stat-card books">
                    <i class="fas fa-book"></i>
                    <h3>Total Books</h3>
                    <p class="stat-number"><?= $total_books ?></p>
                </div>
                <div class="stat-card pending">
                    <i class="fas fa-clock"></i>
                    <h3>Pending Books</h3>
                    <p class="stat-number"><?= $pending_books ?></p>
                </div>
                <div class="stat-card approved">
                    <i class="fas fa-check-circle"></i>
                    <h3>Approved Books</h3>
                    <p class="stat-number"><?= $approved_books ?></p>
                </div>
                <?php
                ?>
                <div class="stat-card rejected">
                    <i class="fas fa-times-circle"></i>
                    <h3>Rejected Books</h3>
                    <p class="stat-number"><?= $rejected_books ?></p>
                </div>
            </div>

            <section class="admin-actions">
                <h2>Management Tools</h2>
                <div class="action-grid">
                    <a href="admin/manage_users.php" class="action-card">
                        <i class="fas fa-users-cog"></i>
                        <span>Manage Users</span>
                    </a>
                    <a href="admin/pending_books.php" class="action-card">
                        <i class="fas fa-tasks"></i>
                        <span>Pending Books</span>
                    </a>
                    <a href="admin/manage_comments.php" class="action-card">
                        <i class="fas fa-comments"></i>
                        <span>Manage Comments</span>
                    </a>
                    <a href="admin/manage_books.php" class="action-card">
                    <i class="fa-solid fa-book"></i>
                        <span>Manage Books</span>
                    </a>
                    <a href="books.php" class="action-card">
                    <i class="fas fa-search"></i>
                        <span>Browse Books</span>
                    </a>
                </div>
            </section>

        <?php else: ?>
            <header class="dashboard-header">
                <div class="greeting-badge"><i class="fas fa-user"></i> Member</div>
                <h1><?= $greeting ?>, <span><?= htmlspecialchars($_SESSION['username']) ?></span> 👋</h1>
                <p class="subtitle">Welcome back to your library.</p>
            </header>

            <section class="user-actions">
                <div class="action-grid">
                    <a href="user/my_books.php" class="action-card">
                        <i class="fa-solid fa-book"></i>
                        <span>My Books</span>
                    </a>
                    <a href="user/upload_book.php" class="action-card">
                        <i class="fas fa-upload"></i>
                        <span>Upload New Book</span>
                    </a>
                    <a href="books.php" class="action-card">
                        <i class="fas fa-search"></i>
                        <span>Browse Books</span>
                    </a>
                </div>
            </section>
        <?php endif; ?>
    </div>

    <footer style="text-align:center; padding: 20px; margin-top: 40px; color: #888; font-size: 0.85rem;">
        &copy; <?= date('Y') ?> Library Management System &mdash; Ali Edris &bull; 220209960 &bull; Uskudar University
    </footer>
</body>

</html>