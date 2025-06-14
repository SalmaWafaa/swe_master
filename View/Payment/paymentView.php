<?php
require_once '../Model/OrderModel.php';


$orderId = 1; // Assuming order ID is 1
$totalAmount = Order::getTotalAmount($orderId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
        }
        .total-amount {
            font-size: 24px;
            font-weight: bold;
            color: #4CAF50;
            border: 2px solid #4CAF50;
            padding: 10px;
            display: inline-block;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <h2>Complete Your Payment</h2>
    
    <div class="total-amount">
        Total Amount: $<span id="amount"><?= number_format($totalAmount, 2) ?></span>
    </div>

    <h3>Select Payment Method</h3>
    <form method="POST" action="process_payment.php">
        <label>
            <input type="radio" name="payment_method" value="credit" required> Credit Card
        </label><br>
        <label>
            <input type="radio" name="payment_method" value="paypal"> PayPal
        </label><br>
        <label>
            <input type="radio" name="payment_method" value="bank"> Bank Transfer
        </label><br><br>
        <button type="submit">Pay Now</button>
    </form>

</body>
</html>
