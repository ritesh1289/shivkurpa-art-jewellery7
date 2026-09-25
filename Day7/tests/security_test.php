<?php

declare(strict_types=1);

require __DIR__ . '/../../Day2/bootstrap.php';

$_SESSION['csrf_token'] = str_repeat('a', 64);
if (!verifyCsrfToken(str_repeat('a', 64)) || verifyCsrfToken(str_repeat('b', 64))) {
    throw new RuntimeException('CSRF token validation failed.');
}

fwrite(STDOUT, "Security test passed.\n");
