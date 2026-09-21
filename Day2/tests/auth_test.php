<?php

declare(strict_types=1);

require __DIR__ . '/../bootstrap.php';

$email = 'day2-test-' . bin2hex(random_bytes(5)) . '@example.test';
$password = 'Correct-Horse-42';
$userId = null;

try {
    $userId = registerUser($database, 'Day 2 Test User', $email, $password);
    $authenticated = authenticateUser($database, $email, $password);

    if ($authenticated === null || (int) $authenticated['id'] !== $userId) {
        throw new RuntimeException('Valid credentials were not authenticated.');
    }
    if (authenticateUser($database, $email, 'wrong-password') !== null) {
        throw new RuntimeException('Invalid credentials were accepted.');
    }

    loginUser($authenticated);
    if ((int) $_SESSION['user_id'] !== $userId) {
        throw new RuntimeException('Authenticated session was not created.');
    }

    fwrite(STDOUT, "Authentication integration test passed.\n");
} finally {
    if ($userId !== null) {
        $statement = $database->prepare('DELETE FROM users WHERE id = :id');
        $statement->execute(['id' => $userId]);
    }
    logoutUser();
}
