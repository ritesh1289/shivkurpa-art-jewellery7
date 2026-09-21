<?php

declare(strict_types=1);

require __DIR__ . '/../cart.php';

$_SESSION = [];
addToCart(7, 2);
addToCart(7, 1);
if (cartItems()[7] !== 3) {
    throw new RuntimeException('Cart quantity accumulation failed.');
}
updateCart(['7' => 4]);
if (cartItems()[7] !== 4) {
    throw new RuntimeException('Cart quantity update failed.');
}
removeFromCart(7);
if (cartItems()) {
    throw new RuntimeException('Cart removal failed.');
}
fwrite(STDOUT, "Cart integration test passed.\n");
