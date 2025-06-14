<?php

class CartView {
    private $controller;

    public function __construct(CartController $controller) {
        $this->controller = $controller;
    }

    public function renderCart(): void {
        if (!isset($_SESSION['customer_id'])) {
            echo "<p class='message'>Please log in to view your cart.</p>";
            return;
        }
        $customerId = $_SESSION['customer_id'];
        $this->controller->initializeCart($customerId);
        $cartItems = $this->controller->getItems();
        $totalAmount = $this->controller->getTotal();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <title>Your Shopping Cart</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                    padding: 20px;
                    background-color: #f8f9fa;
                }
                h1 {
                    text-align: center;
                }
                .cart-container {
                    width: 50%;
                    margin: auto;
                    background: white;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                table, th, td {
                    border: 1px solid #ddd;
                }
                th, td {
                    padding: 10px;
                    text-align: center;
                }
                th {
                    background-color: #007bff;
                    color: white;
                }
                .btn {
                    padding: 8px 12px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                }
                .btn-add {
                    background-color: #28a745;
                    color: white;
                }
                .btn-remove {
                    background-color: #dc3545;
                    color: white;
                }
                .btn:hover {
                    opacity: 0.8;
                }
                .total {
                    font-size: 18px;
                    font-weight: bold;
                    text-align: right;
                }
                .message {
                    text-align: center;
                    color: red;
                    font-size: 18px;
                }
            </style>
        </head>
        <body>
            <h1>Your Shopping Cart</h1>
            <div class="cart-container">
                <?php if (empty($cartItems)): ?>
                    <p class="message">Your cart is empty.</p>
                <?php else: ?>
                    <table>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                        <?php foreach ($cartItems as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                <td>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" name="add" class="btn btn-add">+</button>
                                    </form>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" name="remove" class="btn btn-remove">-</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                    <p class="total">Total: $<?php echo number_format($totalAmount, 2); ?></p>
                <?php endif; ?>
            </div>
        </body>
        </html>
        <?php
    }
}
?>
