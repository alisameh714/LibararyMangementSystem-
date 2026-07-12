<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// SQLite database file — no MySQL / XAMPP needed
$dbPath = __DIR__ . '/../library.db';
$isNew  = !file_exists($dbPath);

try {
    $conn = new PDO('sqlite:' . $dbPath);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec('PRAGMA foreign_keys     = ON');
    $conn->exec('PRAGMA journal_mode     = WAL');
    $conn->exec('PRAGMA case_sensitive_like = OFF');

    // First run: create tables and load seed data automatically
    if ($isNew) {
        $sql = file_get_contents(__DIR__ . '/../sql/init.sql');
        foreach (array_filter(array_map('trim', explode(';', $sql))) as $stmt) {
            $conn->exec($stmt);
        }
    }
} catch (PDOException $e) {
    die('Database error: ' . $e->getMessage());
}
