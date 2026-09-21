<?php

declare(strict_types=1);

if (!isset($GLOBALS['shivkrupa_config'])) {
	$GLOBALS['shivkrupa_config'] = require __DIR__ . '/../Day1/config/config.php';
}
$config = $GLOBALS['shivkrupa_config'];
require_once __DIR__ . '/../Day1/config/database.php';
$database = createDatabaseConnection($config['database']);
