<?php
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token()) . '">';
}

function verify_csrf(): void {
    $token         = $_POST['csrf_token'] ?? '';
    $session_token = $_SESSION['csrf_token'] ?? '';
    if (!$session_token || !hash_equals($session_token, $token)) {
        http_response_code(403);
        die('❌ Security token mismatch. Please go back and try again.');
    }
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
