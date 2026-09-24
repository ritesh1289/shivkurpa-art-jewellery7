<?php

declare(strict_types=1);

require __DIR__ . '/../Day2/bootstrap.php';
require_once __DIR__ . '/../Day7/security.php';

function requireAdmin(PDO $database): array
{
    $user = requireAuthentication($database);
    if (($user['role'] ?? '') !== 'admin') {
        http_response_code(403);
        exit('Administrator access required.');
    }
    return $user;
}

function adminHeader(string $title): void
{
    echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</title></head><body><nav><a href="dashboard.php">Dashboard</a> <a href="products.php">Products</a> <a href="categories.php">Categories</a> <a href="orders.php">Orders</a> <a href="users.php">Users</a></nav><main>';
}

function adminFooter(): void
{
    echo '</main></body></html>';
}
