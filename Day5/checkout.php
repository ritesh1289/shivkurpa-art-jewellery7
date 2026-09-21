<?php
declare(strict_types=1);
require __DIR__ . '/bootstrap.php';
$user = requireAuthentication($database);
$items = loadCartProducts($database);
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shipping = ['name' => (string) ($_POST['name'] ?? ''), 'email' => (string) ($_POST['email'] ?? ''), 'phone' => (string) ($_POST['phone'] ?? ''), 'address' => (string) ($_POST['address'] ?? '')];
    if (!$items || trim($shipping['address']) === '' || !filter_var($shipping['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Add a valid shipping address and email, and ensure your cart has items.';
    } else {
        try { $orderId = createOrder($database, (int) $user['id'], $shipping, (string) ($_POST['payment_result'] ?? 'success')); redirectTo('order.php?id=' . $orderId); } catch (Throwable $exception) { $errors[] = 'Unable to create the order right now.'; }
    }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Checkout | ShivKrupa</title><link rel="stylesheet" href="../Day3/style.css"></head><body><main><h1>Checkout</h1><?php foreach ($errors as $error): ?><p role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endforeach; ?><p>Total: ₹<?= number_format(array_sum(array_column($items, 'line_total')), 2) ?></p><form method="post"><label>Name <input name="name" value="<?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?>" required></label><label>Email <input type="email" name="email" value="<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>" required></label><label>Phone <input name="phone"></label><label>Address <textarea name="address" required></textarea></label><label>Payment result <select name="payment_result"><option value="success">Successful demo payment</option><option value="failure">Failed demo payment</option></select></label><button type="submit">Place order</button></form></main></body></html>
