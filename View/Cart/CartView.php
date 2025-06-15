<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
</head>
<body>
    <h1>Your Shopping Cart</h1>

    <?php if (empty($cartItems)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>Product Image</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price (Each)</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td><img src="/swe_master/uploads/<?= htmlspecialchars($item['product_image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" width="80"></td>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= htmlspecialchars($item['quantity']) ?></td>
                        <td>$<?= htmlspecialchars(number_format($item['price'], 2)) ?></td>
                        <td>$<?= htmlspecialchars(number_format($item['total_price'], 2)) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Add Proceed to Payment Button -->
        <!-- <div style="margin-top: 20px;">
            <form action="/Controller/PaymentController.php?controller=Payment&action=showPaymentForm" method="GET">
                <input type="hidden" name="controller" value="Payment">
                <input type="hidden" name="action" value="showPaymentForm">
                <button type="submit" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">Proceed to Payment</button>
            </form>
        </div>
    <!-- Existing cart table code... -->

    <form method="post" action="index.php?controller=Payment&action=process">
    <!-- Hidden inputs with order ID and amount -->
    <input type="hidden" name="order_id" value="<?= $orderId ?>">
    <input type="hidden" name="paid_amount" value="<?= $finalAmount ?>">

    <button type="submit" name="proceed" class="btn btn-success w-100"
        <?= empty($city) ? 'disabled' : '' ?>>
        <i class="fas fa-credit-card"></i> Proceed to Payment
    </button>
</form>


       <form action="index.php" method="get">
    <input type="hidden" name="controller" value="Order">
    <input type="hidden" name="action" value="calculateForm">
    <button type="submit">🛒 Place Order</button>
</form>

    
    <!-- Payment type dropdown -->
    <label for="payment_type">Choose Payment Method:</label>
    <select name="payment_type" id="payment_type" style="padding: 5px; font-size: 14px;">
        <option value="CreditCard">Credit Card</option>
        <option value="PayPal">PayPal</option>
        <option value="BankTransfer">Bank Transfer</option>
    </select>

  <form action="index.php?controller=Order&action=advanceState" method="post">
    <input type="hidden" name="order_id" value="<?php echo $orderId; ?>">
    <button type="submit" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">
        🛒 Place Order
    </button>
</form>




</form>



    <?php endif; ?>
</body>
</html>
