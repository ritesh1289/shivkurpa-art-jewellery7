<?php
declare(strict_types=1);
require __DIR__ . '/../bootstrap.php';
$email = 'order-test-' . bin2hex(random_bytes(4)) . '@example.test';
$userId = registerUser($database, 'Order Test User', $email, 'Correct-Horse-42');
$_SESSION['cart'] = [];
$product = $database->query('SELECT id FROM products WHERE stock_quantity > 0 LIMIT 1')->fetchColumn();
$_SESSION['cart'][(int) $product] = 1;
$orderId = createOrder($database, $userId, ['name' => 'Test User', 'email' => 'test@example.test', 'phone' => '', 'address' => 'Test address'], 'success');
if (getOrder($database, $orderId, $userId) === null) { throw new RuntimeException('Order was not created.'); }
$database->prepare('DELETE FROM payments WHERE order_id = :id')->execute(['id' => $orderId]);
$database->prepare('DELETE FROM orders WHERE id = :id')->execute(['id' => $orderId]);
$database->prepare('DELETE FROM users WHERE id = :id')->execute(['id' => $userId]);
fwrite(STDOUT, "Order integration test passed.\n");
