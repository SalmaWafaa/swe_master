<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: /swe_master/index.php?controller=User&action=login');
    exit;
}

require_once __DIR__ . '/../../Model/Cart/CartModel.php';

$cartModel = CartModel::getInstance($_SESSION['user_id']);
$cartItems = $cartModel->getCartItems();
$cartTotal = $cartModel->getCartTotal();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart</title>
    <link rel="stylesheet" href="/swe_master/assets/css/cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="cart-container">
        <h1>Your Shopping Cart</h1>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php 
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-error">
                <?php 
                echo $_SESSION['error_message'];
                unset($_SESSION['error_message']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (empty($cartItems)): ?>
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <p>Your cart is empty</p>
                <a href="/swe_master/index.php" class="continue-shopping">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="cart-content">
                <div class="cart-items">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="cart-item">
                            <div class="item-details">
                                <div class="item-image-container">
                                    <?php if (!empty($item['image_url'])): ?>
                                        <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                                             alt="<?php echo htmlspecialchars($item['name']); ?>"
                                             class="item-image">
                                    <?php else: ?>
                                        <div class="no-image">
                                            <i class="fas fa-image"></i>
                                            <span>No Image</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="item-info">
                                    <h3 class="item-name"><?php echo htmlspecialchars($item['name']); ?></h3>
                                    <p class="item-price">$<?php echo number_format($item['price'], 2); ?></p>
                                    <?php if (!empty($item['size'])): ?>
                                        <p class="item-variants">Size: <?php echo htmlspecialchars($item['size']); ?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($item['color'])): ?>
                                        <p class="item-variants">Color: <?php echo htmlspecialchars($item['color']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="quantity-controls">
                                <form action="/swe_master/Controller/Cart/UpdateCartController.php" method="POST" class="quantity-form">
                                    <input type="hidden" name="item_id" value="<?php echo $item['cart_item_id']; ?>">
                                    <button type="submit" name="action" value="decrease" class="quantity-btn">-</button>
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" 
                                           class="quantity-input" onchange="this.form.submit()">
                                    <button type="submit" name="action" value="increase" class="quantity-btn">+</button>
                                </form>
                            </div>
                            <div class="item-total">
                                $<?php echo number_format($item['total_price'], 2); ?>
                            </div>
                            <form action="/swe_master/Controller/Cart/RemoveCartItemController.php" method="POST" class="remove-form">
                                <input type="hidden" name="item_id" value="<?php echo $item['cart_item_id']; ?>">
                                <button type="submit" class="remove-item" onclick="return confirm('Are you sure you want to remove this item?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart-summary">
                    <h2>Order Summary</h2>
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>$<?php echo number_format($cartTotal, 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span>Free</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span>$<?php echo number_format($cartTotal, 2); ?></span>
                    </div>

                    <div class="payment-method">
                        <label for="payment">Payment Method</label>
                        <select id="payment" name="payment">
                            <option value="credit">Credit Card</option>
                            <option value="debit">Debit Card</option>
                            <option value="paypal">PayPal</option>
                        </select>
                    </div>

                    <form action="/swe_master/Controller/CheckoutController.php" method="POST">
                        <input type="hidden" name="action" value="process">
                        <button type="submit" class="checkout-btn">Proceed to Checkout</button>
                    </form>
                    <a href="/swe_master/index.php" class="continue-shopping">Continue Shopping</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
