<?php
declare(strict_types=1);
require __DIR__ . '/bootstrap.php';
require __DIR__ . '/catalog.php';
$slug = trim((string) ($_GET['slug'] ?? ''));
$products = getProducts($database, $slug);
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Category | ShivKrupa</title><link rel="stylesheet" href="style.css"></head><body><nav><a href="index.php">Home</a><a href="../Day4/cart.php">Cart</a></nav><main><h1>Category collection</h1><div class="grid"><?php foreach ($products as $product): ?><article class="card"><a href="product.php?slug=<?= urlencode($product['slug']) ?>"><h2><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></h2></a><p class="price">₹<?= number_format((float) $product['price'], 2) ?></p><p><?= htmlspecialchars((string) $product['description'], ENT_QUOTES, 'UTF-8') ?></p></article><?php endforeach; ?></div></main></body></html>
