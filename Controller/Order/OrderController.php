<?php
require_once 'Model/Order/OrderModel.php';
require_once 'Model/Cart/CartModel.php'; // Ensure CartModel is included

class OrderController
{
    public function calculateTotal()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = (int)$_POST['order_id'];
            $promo = (float)$_POST['promo'];
            $shipping = (float)$_POST['shipping'];
            $tax = (float)$_POST['tax'];

            try {
                $model = new OrderModel();
                $final = $model->applyDecorators($orderId, $promo, $shipping, $tax);
                header("Location: index.php?controller=Order&action=calculateForm&success=1&total=$final");
            } catch (Exception $e) {
                header("Location: index.php?controller=Order&action=calculateForm&error=1&message=" . urlencode($e->getMessage()));
            }
        }
    }

    public function proceedToPayment()
{
    session_start();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['city'])) {
            $city = $_POST['city'];
            $promoCode = $_POST['promo'] ?? '';

            $userId = $_SESSION['user_id'] ?? null;
            if (!$userId) {
                header("Location: index.php?controller=Auth&action=login&error=not_logged_in");
                exit();
            }

            $cartModel = CartModel::getInstance($userId);
            $cartItems = $cartModel->getCartItems();

            if (empty($cartItems)) {
                header("Location: index.php?controller=Order&action=calculateForm&error=empty_cart");
                exit();
            }

            $subtotal = 0;
            foreach ($cartItems as $item) {
                $subtotal += ($item['price'] * $item['quantity']);

            }

            $shippingRates = [
                'Cairo' => 50, 'Alexandria' => 60, 'Giza' => 65, 'Luxor' => 70, 'Aswan' => 80,
                'Mansoura' => 75, 'Tanta' => 72, 'Zagazig' => 68, 'Ismailia' => 85, 'Suez' => 90
            ];

            $shippingCost = $shippingRates[$city] ?? 0;
            $promoDiscount = 0;
            if (strtoupper(trim($promoCode)) === 'SYS20') {
                $promoDiscount = $subtotal * 0.2;
            }
            $tax = $subtotal * 0.14;
            $finalTotal = ($subtotal + $shippingCost + $tax) - $promoDiscount;

            $_SESSION['final_total'] = $finalTotal;

           try {
                $orderModel = new OrderModel();
                // Assume these are fetched or validated from user input/profile
                $billingId = 1; // Placeholder: replace with actual billing address ID
                $shippingId = 1; // Placeholder: replace with actual shipping address ID

                $orderId = $orderModel->createOrder(
                    $userId,
                    $shippingId,
                    $billingId,
                    $finalTotal,
                    'Pending', // Default status upon creation
                    //'Unpaid'   // Initial payment_type in orders table
                    $_SESSION['cart_id'] ?? null // if you are saving cart_id in orders
                );

                // Insert order items
                foreach ($cartItems as $item) {
                    $itemTotal = $item['price'] * $item['quantity'];
                    $orderModel->createOrderItem($orderId, $item['product_id'], $item['quantity'], $item['price'], $itemTotal);
                }

                // Clear the cart after successful order creation
             //   $cartModel->clearCart($userId);

                $_SESSION['order_id'] = $orderId;
                $_SESSION['final_total'] = $finalTotal; // Make sure final_total is stored for the payment form

                // Redirect to the payment form managed by PaymentController
                header("Location: index.php?controller=Payment&action=paymentForm");
                exit();

            } catch (Exception $e) {
                error_log("Order creation failed in proceedToPayment: " . $e->getMessage());
                header("Location: index.php?controller=Order&action=calculateForm&error=1&message=" . urlencode("Failed to create order: " . $e->getMessage()));
                exit();
            }
        } else {
            header("Location: index.php?controller=Order&action=calculateForm&error=missing_city");
            exit();
        }
    }
}

    public function advanceState()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = (int)$_POST['order_id'];
            try {
                $model = new OrderModel();
                $model->advanceOrderState($orderId);
                header("Location: index.php?controller=Order&action=calculateForm&state=1");
            } catch (Exception $e) {
                header("Location: index.php?controller=Order&action=calculateForm&state=0&message=" . urlencode($e->getMessage()));
            }
        }
    }

    public function calculateForm()
    {
        session_start();
        // Assuming CartModel is correctly initialized and available
        // require_once 'Model/Cart/CartModel.php'; // Already required in proceedToPayment if needed

        $promoCode = $_POST['promo'] ?? '';
        $city = $_POST['city'] ?? '';
        $shippingRates = [
            'Cairo' => 50, 'Alexandria' => 60, 'Giza' => 65, 'Luxor' => 70, 'Aswan' => 80,
            'Mansoura' => 75, 'Tanta' => 72, 'Zagazig' => 68, 'Ismailia' => 85, 'Suez' => 90
        ];

        $userId = $_SESSION['user_id'] ?? null;
        $cartModel = CartModel::getInstance($userId);
        $cartItems = $cartModel->getCartItems();

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += ($item['price'] * $item['quantity']); // Make sure quantity is used for subtotal
        }

        $promoDiscount = 0;
        if (strtoupper(trim($promoCode)) === 'SYS20') {
            $promoDiscount = $subtotal * 0.2;
        }

        $shippingCost = $shippingRates[$city] ?? 0;
        $tax = $subtotal * 0.14;
        $finalTotal = ($subtotal + $shippingCost + $tax) - $promoDiscount;

        $_SESSION['final_total'] = $finalTotal; // Store in session for later use if needed

        // Retrieve messages from URL parameters
        $success = $_GET['success'] ?? null;
        $error = $_GET['error'] ?? null;
        $message = $_GET['message'] ?? null;
        $total = $_GET['total'] ?? null; // For displaying calculated total after applyDecorators

        include 'View/Order/calculate_total_form.php';
    }
}