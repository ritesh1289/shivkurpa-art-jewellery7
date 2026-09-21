<?php
declare(strict_types=1);
require __DIR__ . '/bootstrap.php';
$user = requireAuthentication($database);
$order = getOrder($database, (int) ($_GET['id'] ?? 0), (int) $user['id']);
http_response_code($order ? 200 : 404);
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Order | ShivKrupa</title></head><body><main><?php if ($order): ?><h1>Order #<?= (int) $order['id'] ?></h1><p>Status: <?= htmlspecialchars($order['status'], ENT_QUOTES, 'UTF-8') ?></p><p>Total: ₹<?= number_format((float) $order['total_amount'], 2) ?></p><p>Payment status is recorded with the order.</p><?php else: ?><h1>Order not found</h1><?php endif; ?></main></body></html>
