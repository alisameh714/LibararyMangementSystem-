<?php
require '../includes/auth.php';
require '../includes/db.php';
require '../includes/csrf.php';
require '../includes/alerts.php';

$alert = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    verify_csrf();

    $title       = htmlspecialchars($_POST['title']);
    $author      = htmlspecialchars($_POST['author']);
    $description = htmlspecialchars($_POST['description']);
    $price       = floatval($_POST['price']);
    $category    = htmlspecialchars($_POST['category']);
    $language    = htmlspecialchars($_POST['language']);
    $pages       = intval($_POST['pages']);
    $uploaded_by = $_SESSION['user_id'];
    $error       = '';

    if ($_FILES['book_file']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Book file upload failed. Please try again.';
    } elseif ($_FILES['book_image']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Cover image upload failed. Please try again.';
    } elseif ($_FILES['book_file']['size'] > 50 * 1024 * 1024) {
        $error = 'Book file must be under 50 MB.';
    } elseif ($_FILES['book_image']['size'] > 5 * 1024 * 1024) {
        $error = 'Cover image must be under 5 MB.';
    } else {
        $finfo      = finfo_open(FILEINFO_MIME_TYPE);
        $book_mime  = finfo_file($finfo, $_FILES['book_file']['tmp_name']);
        $image_mime = finfo_file($finfo, $_FILES['book_image']['tmp_name']);
        finfo_close($finfo);

        $allowed_image_ext = ['image/jpeg'=>'jpg','image/png'=>'png','image/gif'=>'gif','image/webp'=>'webp'];

        if ($book_mime !== 'application/pdf') {
            $error = 'Only PDF files are allowed for books.';
        } elseif (!isset($allowed_image_ext[$image_mime])) {
            $error = 'Only JPEG, PNG, GIF, or WebP images are allowed for covers.';
        } else {
            $uploads_dir_books  = __DIR__ . '/../assets/uploads/books/';
            $uploads_dir_images = __DIR__ . '/../assets/uploads/images/';
            if (!is_dir($uploads_dir_books))  mkdir($uploads_dir_books,  0755, true);
            if (!is_dir($uploads_dir_images)) mkdir($uploads_dir_images, 0755, true);

            $book_filename  = uniqid('book_', true) . '.pdf';
            $image_filename = uniqid('img_',  true) . '.' . $allowed_image_ext[$image_mime];
            $book_file_path  = 'assets/uploads/books/'  . $book_filename;
            $book_image_path = 'assets/uploads/images/' . $image_filename;

            move_uploaded_file($_FILES['book_file']['tmp_name'],  $uploads_dir_books  . $book_filename);
            move_uploaded_file($_FILES['book_image']['tmp_name'], $uploads_dir_images . $image_filename);

            $stmt = $conn->prepare("
                INSERT INTO books (title, author, description, book_file, book_image, price, category, language, pages, uploaded_by, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
            ");
            $stmt->execute([$title, $author, $description, $book_file_path, $book_image_path, $price, $category, $language, $pages, $uploaded_by]);

            $alert = 'success';
        }
    }

    if ($error) { $alert = 'error:' . $error; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Book | Library</title>
    <link rel="stylesheet" href="../assets/css/includes/header.css">
    <link rel="stylesheet" href="../assets/css/user/upload_book.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php require '../includes/header.php'; ?>
    <div class="container">
        <h2><i class="fas fa-upload"></i> Upload a New Book</h2>

        <?php if ($alert === 'success'): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> Book uploaded! It is now pending admin approval.</div>
        <?php elseif (str_starts_with($alert, 'error:')): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars(substr($alert, 6)) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <!-- Book Info -->
            <div class="form-card">
                <div class="form-card-header"><h3><i class="fas fa-info-circle"></i> Book Information</h3></div>
                <div class="form-card-body">
                    <div class="field full">
                        <label for="title">Title <span style="color:red">*</span></label>
                        <input type="text" id="title" name="title" placeholder="Book title" required>
                    </div>
                    <div class="field">
                        <label for="author">Author</label>
                        <input type="text" id="author" name="author" placeholder="Author name">
                    </div>
                    <div class="field">
                        <label for="category">Category</label>
                        <input type="text" id="category" name="category" placeholder="e.g. Fiction, Science">
                    </div>
                    <div class="field">
                        <label for="language">Language</label>
                        <input type="text" id="language" name="language" placeholder="e.g. English, Arabic">
                    </div>
                    <div class="field">
                        <label for="pages">Pages</label>
                        <input type="number" id="pages" name="pages" placeholder="Number of pages" min="1">
                    </div>
                    <div class="field">
                        <label for="price">Price ($)</label>
                        <input type="number" id="price" name="price" placeholder="0.00" step="0.01" min="0">
                    </div>
                    <div class="field full">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" placeholder="Brief description of the book..."></textarea>
                    </div>
                </div>
            </div>

            <!-- File Uploads -->
            <div class="form-card">
                <div class="form-card-header"><h3><i class="fas fa-file-upload"></i> Files</h3></div>
                <div class="form-card-body">
                    <div class="field">
                        <label for="book_file">Book PDF <span style="color:red">*</span></label>
                        <input type="file" id="book_file" name="book_file" accept=".pdf" required>
                        <span class="field-hint">PDF only · Max 50 MB</span>
                    </div>
                    <div class="field">
                        <label for="book_image">Cover Image <span style="color:red">*</span></label>
                        <input type="file" id="book_image" name="book_image" accept="image/*" required>
                        <span class="field-hint">JPEG / PNG / WebP · Max 5 MB</span>
                    </div>
                </div>
            </div>

            <div class="submit-row">
                <a href="my_books.php" class="back-link"><i class="fas fa-arrow-left"></i> My Books</a>
                <button type="submit" class="btn-submit"><i class="fas fa-cloud-upload-alt"></i> Upload Book</button>
            </div>
        </form>
    </div>
</body>
</html>
