<?php
declare(strict_types=1);
require __DIR__ . '/bootstrap.php';
require __DIR__ . '/catalog.php';
$product = getProductBySlug($database, trim((string) ($_GET['slug'] ?? '')));
http_response_code($product === null ? 404 : 200);
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?= $product ? htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') : 'Product not found' ?> | ShivKrupa</title><link rel="stylesheet" href="style.css"></head><body><nav><a href="index.php">Home</a><a href="../Day4/cart.php">Cart</a></nav><main><?php if ($product): ?><h1><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></h1><p class="price">₹<?= number_format((float) $product['price'], 2) ?></p><p><?= nl2br(htmlspecialchars((string) $product['description'], ENT_QUOTES, 'UTF-8')) ?></p><p><?= (int) $product['stock_quantity'] ?> available</p><form method="post" action="../Day4/cart.php"><input type="hidden" name="action" value="add"><input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>"><label>Quantity <input type="number" name="quantity" min="1" max="<?= max(1, (int) $product['stock_quantity']) ?>" value="1"></label><button type="submit" <?= (int) $product['stock_quantity'] < 1 ? 'disabled' : '' ?>>Add to cart</button></form><?php else: ?><h1>Product not found</h1><p><a href="index.php">Return to collection</a></p><?php endif; ?></main></body></html>
