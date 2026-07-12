<?php
require '../includes/auth.php';
require '../includes/db.php';
require '../includes/csrf.php';
require '../includes/alerts.php';

// Validate Book ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('❌ Invalid Book ID'); // If the book ID is invalid, the script stops execution and displays an error message.
}

$book_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

// Fetch the book
if ($user_role === 'admin') {
    // Admin can access any book
    $stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->execute([$book_id]);
} else {
    // Normal user can only access their own books
    $stmt = $conn->prepare("SELECT * FROM books WHERE id = ? AND uploaded_by = ?");
    $stmt->execute([$book_id, $user_id]);
}

$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    die('❌ Unauthorized Access or Book Not Found');
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    verify_csrf();

    $title       = htmlspecialchars($_POST['title']);
    $author      = htmlspecialchars($_POST['author']);
    $description = htmlspecialchars($_POST['description']);
    $price       = floatval($_POST['price']);
    $category    = htmlspecialchars($_POST['category']);
    $language    = htmlspecialchars($_POST['language']);
    $pages       = intval($_POST['pages']);

    $book_file_path  = $book['book_file'];
    $book_image_path = $book['book_image'];
    $error           = '';

    $allowed_image_ext = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];

    if (!empty($_FILES['book_file']['name'])) {
        if ($_FILES['book_file']['error'] !== UPLOAD_ERR_OK) {
            $error = 'Book file upload failed.';
        } elseif ($_FILES['book_file']['size'] > 50 * 1024 * 1024) {
            $error = 'Book file must be under 50 MB.';
        } else {
            $finfo     = finfo_open(FILEINFO_MIME_TYPE);
            $book_mime = finfo_file($finfo, $_FILES['book_file']['tmp_name']);
            finfo_close($finfo);
            if ($book_mime !== 'application/pdf') {
                $error = 'Only PDF files are allowed for books.';
            } else {
                $uploads_dir_books = __DIR__ . '/../assets/uploads/books/';
                $book_filename     = uniqid('book_', true) . '.pdf';
                $book_file_path    = 'assets/uploads/books/' . $book_filename;
                move_uploaded_file($_FILES['book_file']['tmp_name'], $uploads_dir_books . $book_filename);
            }
        }
    }

    if (!$error && !empty($_FILES['book_image']['name'])) {
        if ($_FILES['book_image']['error'] !== UPLOAD_ERR_OK) {
            $error = 'Cover image upload failed.';
        } elseif ($_FILES['book_image']['size'] > 5 * 1024 * 1024) {
            $error = 'Cover image must be under 5 MB.';
        } else {
            $finfo      = finfo_open(FILEINFO_MIME_TYPE);
            $image_mime = finfo_file($finfo, $_FILES['book_image']['tmp_name']);
            finfo_close($finfo);
            if (!isset($allowed_image_ext[$image_mime])) {
                $error = 'Only JPEG, PNG, GIF, or WebP images are allowed for covers.';
            } else {
                $uploads_dir_images = __DIR__ . '/../assets/uploads/images/';
                $image_filename     = uniqid('img_', true) . '.' . $allowed_image_ext[$image_mime];
                $book_image_path    = 'assets/uploads/images/' . $image_filename;
                move_uploaded_file($_FILES['book_image']['tmp_name'], $uploads_dir_images . $image_filename);
            }
        }
    }

    if ($error) {
        display_alert('❌ ' . htmlspecialchars($error), 'error');
    } else {
        // Admin edits keep the current status; user edits go back to pending for re-approval
        $new_status = ($user_role === 'admin') ? $book['status'] : 'pending';

        $stmt = $conn->prepare("
            UPDATE books
            SET title = ?, author = ?, description = ?, price = ?, category = ?, language = ?, pages = ?, book_file = ?, book_image = ?, status = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        $stmt->execute([$title, $author, $description, $price, $category, $language, $pages, $book_file_path, $book_image_path, $new_status, $book_id]);

        display_alert('✅ Book updated successfully! It is now pending admin approval.', 'success');

        header($user_role === 'admin' ? 'Location: ../admin/manage_books.php' : 'Location: my_books.php');
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link rel="stylesheet" href="../assets/css/includes/header.css">
    <link rel="stylesheet" href="../assets/css/user/edit_book.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <?php require '../includes/header.php'; ?>
    <div class="container">
        <h2><i class="fas fa-pencil-alt"></i> Edit Book</h2>

        <form method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <!-- Book Info -->
            <div class="form-card">
                <div class="form-card-header"><h3><i class="fas fa-info-circle"></i> Book Information</h3></div>
                <div class="form-card-body">
                    <div class="field full">
                        <label for="title">Title <span style="color:red">*</span></label>
                        <input type="text" id="title" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>
                    </div>
                    <div class="field">
                        <label for="author">Author</label>
                        <input type="text" id="author" name="author" value="<?= htmlspecialchars($book['author']) ?>">
                    </div>
                    <div class="field">
                        <label for="category">Category</label>
                        <input type="text" id="category" name="category" value="<?= htmlspecialchars($book['category']) ?>">
                    </div>
                    <div class="field">
                        <label for="language">Language</label>
                        <input type="text" id="language" name="language" value="<?= htmlspecialchars($book['language']) ?>">
                    </div>
                    <div class="field">
                        <label for="pages">Pages</label>
                        <input type="number" id="pages" name="pages" value="<?= htmlspecialchars($book['pages']) ?>">
                    </div>
                    <div class="field">
                        <label for="price">Price ($)</label>
                        <input type="number" id="price" step="0.01" name="price" value="<?= $book['price'] ?>">
                    </div>
                    <div class="field full">
                        <label for="description">Description</label>
                        <textarea id="description" name="description"><?= htmlspecialchars($book['description']) ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Files -->
            <div class="form-card">
                <div class="form-card-header"><h3><i class="fas fa-file-upload"></i> Files (leave blank to keep current)</h3></div>
                <div class="form-card-body">
                    <div class="field">
                        <label for="book_file">Replace PDF</label>
                        <?php if (!empty($book['book_file'])): ?>
                            <div class="current-preview">
                                <i class="fas fa-file-pdf" style="font-size:2rem;color:#e53e3e;"></i>
                                <div>
                                    <span class="preview-label">Current file</span>
                                    <a href="../<?= htmlspecialchars(ltrim($book['book_file'], './')) ?>" download>
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                        <input type="file" id="book_file" name="book_file" accept=".pdf">
                        <span class="field-hint">PDF only · Max 50 MB</span>
                    </div>
                    <div class="field">
                        <label for="book_image">Replace Cover Image</label>
                        <?php if (!empty($book['book_image'])): ?>
                            <div class="current-preview">
                                <img src="../<?= htmlspecialchars(ltrim($book['book_image'], './')) ?>" alt="Current Cover"
                                     onerror="this.src='../assets/uploads/images/default-cover.svg'; this.onerror=null;">
                                <div>
                                    <span class="preview-label">Current cover</span>
                                    <span style="font-size:.8rem;color:var(--gray);">Upload a new image to replace</span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <input type="file" id="book_image" name="book_image" accept="image/*">
                        <span class="field-hint">JPEG / PNG / WebP · Max 5 MB</span>
                    </div>
                </div>
            </div>

            <div class="submit-row">
                <a href="<?= $user_role === 'admin' ? '../admin/manage_books.php' : 'my_books.php' ?>" class="back-link">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Save Changes</button>
            </div>
        </form>
    </div>
</body>
</html>