<?php
class OrderController {
    protected $orderModel;
    protected $cartModel;

    public function __construct($db) {
        $this->orderModel = new OrderModel($db);
        $this->cartModel = new CartModel($db);
    }

    public function checkout() {
        $userId = $_SESSION['user_id'];
        $cartItems = $this->cartModel->getCartByUser($userId);

        if (empty($cartItems)) {
            die('Your cart is empty.');
        }

        $shippingAddressId = $_POST['shipping_address_id'];
        $billingAddressId = $_POST['billing_address_id'];
        $paymentMethod = $_POST['payment_method'];

        $orderId = $this->orderModel->createOrder($userId, $cartItems, $shippingAddressId, $billingAddressId, $paymentMethod);

        // Clear Cart after successful order
        $this->cartModel->clearCart($userId);

        header("Location: order_success.php?order_id=" . $orderId);
        exit();
    }
}
?>
