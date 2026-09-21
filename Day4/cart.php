<?php

declare(strict_types=1);

require __DIR__ . '/../Day3/bootstrap.php';
require __DIR__ . '/../Day3/catalog.php';
require_once __DIR__ . '/../Day2/auth.php';
startAuthenticationSession();

function cartItems(): array
{
    return $_SESSION['cart'] ?? [];
}

function saveCart(array $cart): void
{
    $_SESSION['cart'] = array_filter($cart, static fn ($quantity): bool => (int) $quantity > 0);
}

function addToCart(int $productId, int $quantity): void
{
    $cart = cartItems();
    $cart[$productId] = (int) ($cart[$productId] ?? 0) + max(1, $quantity);
    saveCart($cart);
}

function updateCart(array $quantities): void
{
    $cart = [];
    foreach ($quantities as $productId => $quantity) {
        $id = (int) $productId;
        $amount = (int) $quantity;
        if ($id > 0 && $amount > 0) {
            $cart[$id] = min($amount, 99);
        }
    }
    saveCart($cart);
}

function removeFromCart(int $productId): void
{
    $cart = cartItems();
    unset($cart[$productId]);
    saveCart($cart);
}

function loadCartProducts(PDO $database): array
{
    $cart = cartItems();
    if (!$cart) {
        return [];
    }

    $placeholders = implode(',', array_fill(0, count($cart), '?'));
    $statement = $database->prepare("SELECT id, name, slug, price, stock_quantity FROM products WHERE id IN ($placeholders) AND is_active = 1");
    $statement->execute(array_keys($cart));
    $items = [];
    foreach ($statement->fetchAll() as $product) {
        $product['quantity'] = min((int) $cart[$product['id']], (int) $product['stock_quantity']);
        if ($product['quantity'] > 0) {
            $product['line_total'] = $product['quantity'] * (float) $product['price'];
            $items[] = $product;
        }
    }
    return $items;
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $action = (string) ($_POST['action'] ?? '');
    if ($action === 'add') {
        addToCart((int) ($_POST['product_id'] ?? 0), (int) ($_POST['quantity'] ?? 1));
    } elseif ($action === 'update') {
        updateCart((array) ($_POST['quantities'] ?? []));
    } elseif ($action === 'remove') {
        removeFromCart((int) ($_POST['product_id'] ?? 0));
    }
    redirectTo('cart.php');
}

$items = loadCartProducts($database);
$total = array_sum(array_column($items, 'line_total'));
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Your Cart | ShivKrupa</title><link rel="stylesheet" href="../Day3/style.css"></head><body><nav><a href="../Day3/index.php">Continue shopping</a><a href="../Day2/profile.php">Account</a></nav><main><h1>Your cart</h1><?php if (!$items): ?><p>Your cart is empty.</p><?php else: ?><form method="post"><input type="hidden" name="action" value="update"><table><thead><tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th><th></th></tr></thead><tbody><?php foreach ($items as $item): ?><tr><td><?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8') ?></td><td>₹<?= number_format((float) $item['price'], 2) ?></td><td><input type="number" name="quantities[<?= (int) $item['id'] ?>]" min="0" max="99" value="<?= (int) $item['quantity'] ?>"></td><td>₹<?= number_format((float) $item['line_total'], 2) ?></td><td><button formaction="cart.php" name="action" value="remove" type="submit">Remove</button></td></tr><?php endforeach; ?></tbody></table><p><strong>Total: ₹<?= number_format($total, 2) ?></strong></p><button type="submit">Update cart</button></form><p><a href="../Day5/checkout.php">Proceed to checkout</a></p><?php endif; ?></main></body></html>
