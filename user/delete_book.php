<?php
require '../includes/auth.php';
require '../includes/db.php';
require '../includes/csrf.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('❌ Invalid request method.');
}

verify_csrf();

if (!isset($_POST['book_id'])) {
    die('❌ Invalid Book ID');
}

$user_id = $_SESSION['user_id'];
$book_id = intval($_POST['book_id']);

$stmt = $conn->prepare("SELECT book_file, book_image FROM books WHERE id = ? AND uploaded_by = ?");
$stmt->execute([$book_id, $user_id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if ($book) {
    $root = __DIR__ . '/../';
    if (!empty($book['book_file']))  @unlink($root . $book['book_file']);
    if (!empty($book['book_image'])) @unlink($root . $book['book_image']);

    $stmt = $conn->prepare("DELETE FROM books WHERE id = ? AND uploaded_by = ?");
    $stmt->execute([$book_id, $user_id]);
    header('Location: my_books.php');
    exit();
} else {
    // Redirect back instead of showing a raw error message
    header('Location: my_books.php');
    exit();
}
?>
