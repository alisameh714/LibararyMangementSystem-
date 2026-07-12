<?php
require 'includes/db.php';
require 'includes/csrf.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    verify_csrf();
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);

    if ($_POST['password'] !== $_POST['password_confirm']) {
        $error = 'Passwords do not match.';
    } else {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        try {
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $password]);
            header('Location: login.php?registered=1');
            exit();
        } catch (PDOException $e) {
            $error = 'This email is already registered. Please use a different one.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Ali Edris - 220209960 - Uskudar University">
    <title>Register | Library Management System</title>
    <link rel="stylesheet" href="assets/css/register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="register-wrapper">
        <!-- Left brand panel -->
        <div class="register-panel">
            <div class="panel-logo">
                <i class="fas fa-book-reader"></i>
                <span>Library System</span>
            </div>
            <h1 class="panel-headline">
                Join Our<br><span>Reading Community</span>
            </h1>
            <p class="panel-sub">
                Create your free account and get access to thousands of books today.
            </p>
            <ul class="panel-features">
                <li><i class="fas fa-infinity"></i> Unlimited book browsing</li>
                <li><i class="fas fa-upload"></i> Upload & share your books</li>
                <li><i class="fas fa-star"></i> Rate and review books</li>
                <li><i class="fas fa-shield-alt"></i> Safe & secure platform</li>
            </ul>
        </div>

        <!-- Right form panel -->
        <div class="register-form-side">
            <div class="register-card">
                <div class="register-logo">
                    <i class="fas fa-user-plus"></i>
                    <h2>Create an Account</h2>
                    <p>Join the library community</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <div class="input-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" id="username" name="username" placeholder="Your name" required>
                        </div>
                    </div>
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
                            <input type="password" id="password" name="password" placeholder="Create a password" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password_confirm">Confirm Password</label>
                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password_confirm" name="password_confirm" placeholder="Repeat password" required>
                        </div>
                    </div>
                    <button type="submit" class="btn-register">
                        <i class="fas fa-user-check"></i> Create Account
                    </button>
                </form>

                <div class="register-footer">
                    Already have an account? <a href="login.php">Sign in here</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
