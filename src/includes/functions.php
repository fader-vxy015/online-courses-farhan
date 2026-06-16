<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function sanitize(string $value): string
{
    return trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
}

function redirect(string $url): void
{
    header("Location: $url");
    exit;
}

function requireLogin(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (empty($_SESSION['user_id'])) {
        redirect('login.php');
    }
}

function requireAdminLogin(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (empty($_SESSION['admin'])) {
        redirect('login.php');
    }
}

function currentUserName(): string
{
    return $_SESSION['user_name'] ?? '';
}

function currentUserId(): ?int
{
    return isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
}
