<?php
declare(strict_types=1);
require __DIR__ . '/bootstrap.php';
require __DIR__ . '/catalog.php';
$products = getProducts($database);
$categories = getCategories($database);
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>ShivKrupa Art Jewellery</title><link rel="stylesheet" href="style.css"></head><body>
<nav><a href="index.php">ShivKrupa Art Jewellery</a><?php foreach ($categories as $category): ?><a href="category.php?slug=<?= urlencode($category['slug']) ?>"><?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?></a><?php endforeach; ?><a href="../Day4/cart.php">Cart</a><a href="../Day2/login.php">Account</a></nav>
<main><h1>Art jewellery collection</h1><div class="grid"><?php foreach ($products as $product): ?><article class="card"><a href="product.php?slug=<?= urlencode($product['slug']) ?>"><img src="<?= htmlspecialchars($product['image_path'] ?: 'https://placehold.co/500x350/eadacc/30231d?text=Jewellery', ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>"><h2><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></h2></a><p class="price">₹<?= number_format((float) $product['price'], 2) ?></p><p><?= htmlspecialchars((string) $product['description'], ENT_QUOTES, 'UTF-8') ?></p></article><?php endforeach; ?></div><?php if (!$products): ?><p>No products are available yet.</p><?php endif; ?></main></body></html>
