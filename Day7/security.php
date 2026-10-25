<?php

declare(strict_types=1);

function csrfToken(): string
{
    startAuthenticationSession();
    return $_SESSION['csrf_token'] ??= bin2hex(random_bytes(32));
}

function verifyCsrfToken(?string $token): bool
{
    startAuthenticationSession();
    return is_string($token) && isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function requireCsrfToken(): void
{
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        http_response_code(419);
        exit('Invalid security token.');
    }
}
