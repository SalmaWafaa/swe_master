<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
</head>
<body>
    <h1>Your Cart</h1>

    <?php if (!empty($cartItems)): ?>
        <ul>
            <?php foreach ($cartItems as $item): ?>
                <li>
                    <?= htmlspecialchars($item['name']) ?> - <?= htmlspecialchars($item['quantity']) ?> x <?= htmlspecialchars($item['price']) ?>$
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

    <a href="checkout.php">Proceed to Checkout</a>
</body>
</html>
