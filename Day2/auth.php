<?php

declare(strict_types=1);

function startAuthenticationSession(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $isHttps = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';

    session_set_cookie_params([
        'httponly' => true,
        'secure' => $isHttps,
        'samesite' => 'Lax',
        'path' => '/',
    ]);

    session_start();
}

function findUserByEmail(PDO $database, string $email): ?array
{
    $statement = $database->prepare(
        'SELECT id, name, email, password_hash, role FROM users WHERE email = :email LIMIT 1'
    );
    $statement->execute(['email' => strtolower(trim($email))]);
    $user = $statement->fetch();

    return $user === false ? null : $user;
}

function findUserById(PDO $database, int $userId): ?array
{
    $statement = $database->prepare(
        'SELECT id, name, email, role FROM users WHERE id = :id LIMIT 1'
    );
    $statement->execute(['id' => $userId]);
    $user = $statement->fetch();

    return $user === false ? null : $user;
}

function registerUser(PDO $database, string $name, string $email, string $password): int
{
    $statement = $database->prepare(
        'INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :password_hash)'
    );
    $statement->execute([
        'name' => trim($name),
        'email' => strtolower(trim($email)),
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
    ]);

    return (int) $database->lastInsertId();
}

function authenticateUser(PDO $database, string $email, string $password): ?array
{
    $user = findUserByEmail($database, $email);

    if ($user === null || !password_verify($password, $user['password_hash'])) {
        return null;
    }

    unset($user['password_hash']);
    return $user;
}

function loginUser(array $user): void
{
    startAuthenticationSession();
    session_regenerate_id(true);
    $_SESSION['user_id'] = (int) $user['id'];
}

function currentUser(PDO $database): ?array
{
    startAuthenticationSession();

    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    $user = findUserById($database, (int) $_SESSION['user_id']);

    if ($user === null) {
        logoutUser();
    }

    return $user;
}

function requireAuthentication(PDO $database): array
{
    $user = currentUser($database);

    if ($user === null) {
        $returnTo = urlencode($_SERVER['REQUEST_URI'] ?? '/Day2/profile.php');
        header('Location: login.php?return_to=' . $returnTo);
        exit;
    }

    return $user;
}

function logoutUser(): void
{
    startAuthenticationSession();
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $parameters = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $parameters['path'], '', (bool) $parameters['secure'], (bool) $parameters['httponly']);
    }

    session_destroy();
}

function redirectTo(string $location): never
{
    header('Location: ' . $location);
    exit;
}
