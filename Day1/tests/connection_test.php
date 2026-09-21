<?php

declare(strict_types=1);

$config = require __DIR__ . '/../config/config.php';
require __DIR__ . '/../config/database.php';

try {
    $connection = createDatabaseConnection($config['database']);
    $connection->query('SELECT 1');
    fwrite(STDOUT, "Database connection successful.\n");
    exit(0);
} catch (Throwable $exception) {
    fwrite(STDERR, "Database connection failed: {$exception->getMessage()}\n");
    exit(1);
}
