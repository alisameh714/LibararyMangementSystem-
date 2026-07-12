<?php
require 'includes/db.php';
require 'includes/csrf.php';

$error   = '';
$success = isset($_GET['registered']) ? 'Registration successful! Please log in.' : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    verify_csrf();
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['username']  = $user['username'];
        header('Location: dashboard.php');
        exit();
    } else {
        $error = 'Invalid email or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Ali Edris - 220209960 - Uskudar University">
    <title>Login | Library Management System</title>
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-wrapper">
        <!-- Left brand panel -->
        <div class="login-panel">
            <div class="panel-logo">
                <i class="fas fa-book-reader"></i>
                <span>Library System</span>
            </div>
            <h1 class="panel-headline">
                Your Digital<br><span>Library Hub</span>
            </h1>
            <p class="panel-sub">
                Discover, download, and manage thousands of books — all in one place.
            </p>
            <ul class="panel-features">
                <li><i class="fas fa-book"></i> Access thousands of books</li>
                <li><i class="fas fa-cloud-download-alt"></i> Download PDFs instantly</li>
                <li><i class="fas fa-upload"></i> Share your own books</li>
                <li><i class="fas fa-comments"></i> Leave reviews & comments</li>
            </ul>
        </div>

        <!-- Right form panel -->
        <div class="login-form-side">
            <div class="login-card">
                <div class="login-logo">
                    <i class="fas fa-book-reader"></i>
                    <h2>Welcome Back</h2>
                    <p>Sign in to your library account</p>
                </div>

                <?php if ($success): ?>
                    <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <div class="input-icon">
                            <i class="fas fa-envelope"></i>
                            <input type="email" id="email" name="email" placeholder="you@example.com" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password" name="password" placeholder="Your password" required>
                        </div>
                    </div>
                    <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i> Sign In
                    </button>
                </form>

                <div class="login-footer">
                    Don't have an account? <a href="register.php">Register here</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
