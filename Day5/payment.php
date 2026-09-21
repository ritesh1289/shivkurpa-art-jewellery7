<?php
declare(strict_types=1);
require __DIR__ . '/bootstrap.php';
$user = requireAuthentication($database);
$order = getOrder($database, (int) ($_GET['id'] ?? 0), (int) $user['id']);
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><title>Payment verification</title></head><body><main><?php if ($order): ?><h1>Payment <?= htmlspecialchars($order['status'], ENT_QUOTES, 'UTF-8') ?></h1><p>Order #<?= (int) $order['id'] ?> is <?= htmlspecialchars($order['status'], ENT_QUOTES, 'UTF-8') ?>.</p><?php else: ?><h1>Payment record not found</h1><?php endif; ?></main></body></html>
