<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_authenticated(): bool {
    return isset($_SESSION['user']) && isset($_SESSION['user']['id']);
}

function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}

function redirect_to_signin(): void {
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    $path = str_contains($script, '/app/') ? '../signin.php' : 'signin.php';
    header('Location: ' . $path);
    exit();
}

function require_auth(): void {
    if (!is_authenticated()) {
        redirect_to_signin();
    }
}
