<?php

declare(strict_types=1);

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../catalog.php';

$products = getProducts($database);
$product = getProductBySlug($database, 'mogra-pearl-necklace');

if (count($products) < 1 || $product === null) {
    throw new RuntimeException('Catalog query test failed.');
}

fwrite(STDOUT, "Catalog query test passed.\n");
