<?php

declare(strict_types=1);

require __DIR__ . '/../Day2/bootstrap.php';
ob_start();
require_once __DIR__ . '/../Day4/cart.php';
ob_end_clean();

function createOrder(PDO $database, int $userId, array $shipping, string $paymentResult): int
{
    $items = loadCartProducts($database);
    if (!$items) {
        throw new RuntimeException('Your cart is empty.');
    }

    $total = array_sum(array_column($items, 'line_total'));
    $database->beginTransaction();
    try {
        $order = $database->prepare('INSERT INTO orders (user_id, total_amount, shipping_name, shipping_email, shipping_phone, shipping_address) VALUES (:user_id, :total, :name, :email, :phone, :address)');
        $order->execute(['user_id' => $userId, 'total' => $total, 'name' => trim($shipping['name']), 'email' => trim($shipping['email']), 'phone' => trim($shipping['phone']), 'address' => trim($shipping['address'])]);
        $orderId = (int) $database->lastInsertId();
        $itemStatement = $database->prepare('INSERT INTO order_items (order_id, product_id, product_name, unit_price, quantity) VALUES (:order_id, :product_id, :name, :price, :quantity)');
        foreach ($items as $item) {
            $itemStatement->execute(['order_id' => $orderId, 'product_id' => $item['id'], 'name' => $item['name'], 'price' => $item['price'], 'quantity' => $item['quantity']]);
        }
        $payment = $database->prepare('INSERT INTO payments (order_id, provider, status, amount, transaction_reference) VALUES (:order_id, :provider, :status, :amount, :reference)');
        $status = $paymentResult === 'failure' ? 'failed' : 'success';
        $payment->execute(['order_id' => $orderId, 'provider' => 'demo', 'status' => $status, 'amount' => $total, 'reference' => 'demo-' . bin2hex(random_bytes(8))]);
        if ($status === 'success') {
            $database->prepare('UPDATE orders SET status = "confirmed" WHERE id = :id')->execute(['id' => $orderId]);
        }
        $database->commit();
        $_SESSION['cart'] = [];
        return $orderId;
    } catch (Throwable $exception) {
        $database->rollBack();
        throw $exception;
    }
}

function getOrder(PDO $database, int $orderId, int $userId): ?array
{
    $statement = $database->prepare('SELECT id, status, total_amount, shipping_name, shipping_email, created_at FROM orders WHERE id = :id AND user_id = :user_id');
    $statement->execute(['id' => $orderId, 'user_id' => $userId]);
    $order = $statement->fetch();
    return $order === false ? null : $order;
}
