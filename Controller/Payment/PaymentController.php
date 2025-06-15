<?php
// File: Controller/PaymentController.php
require_once 'Model/Payment/PaymentContext.php';
require_once 'Model/Payment/CreditCardPayment.php';
require_once 'Model/Payment/BankTransferPayment.php';
//require_once 'Model/Payment/CashPayment.php'; // Include the new strategy

class PaymentController {
    public function process() {
        session_start(); // Ensure session is started

        $orderId = isset($_POST['order_id']) ? filter_var($_POST['order_id'], FILTER_VALIDATE_INT) : null;
        $amount = isset($_POST['amount']) ? filter_var($_POST['amount'], FILTER_VALIDATE_FLOAT) : null;
        $paymentMethod = isset($_POST['payment_method']) ? htmlspecialchars($_POST['payment_method']) : null;

        // Basic validation
        if ($orderId === null || $orderId === false || $amount === null || $amount === false || $paymentMethod === null) {
            header("Location: index.php?controller=Payment&action=error&message=" . urlencode("Invalid order details or payment method selected."));
            exit();
        }

        try {
            $context = new PaymentContext();

            switch ($paymentMethod) {
                case 'credit_card':
                    $context->setStrategy(new CreditCardPayment());
                    break;
                case 'bank_transfer':
                    $context->setStrategy(new BankTransferPayment());
                    break;
                // case 'cash_on_delivery':
                //     $context->setStrategy(new CashPayment());
                //     break;
                default:
                    throw new Exception("Unsupported payment method selected.");
            }

            $context->executePayment($orderId, $amount);

            // Payment successful, redirect to success page
            header("Location: index.php?controller=Payment&action=success");
            exit();

        } catch (Exception $e) {
            // Log the error for debugging (check your web server error logs)
            error_log("Payment processing failed: " . $e->getMessage());
            // Redirect to an error page with a user-friendly message
            header("Location: index.php?controller=Payment&action=error&message=" . urlencode("Payment failed: " . $e->getMessage()));
            exit();
        }
    }

    public function success() {
        // This method will display the payment success page
        include 'View/Payment/PaymentSuccess.php'; // Create this file
    }

    

    public function paymentForm() {
        session_start(); // Start session to access order details
        $orderId = $_SESSION['order_id'] ?? null;
        $totalAmount = $_SESSION['final_total'] ?? null;

        if (!$orderId || !$totalAmount) {
            // Redirect if no order details are found in session
            header("Location: index.php?controller=Order&action=calculateForm&error=1&message=" . urlencode("No order to process payment for. Please complete your order first."));
            exit();
        }

        include 'View/Payment/paymentView.php'; // This will be your payment form
    }
}
?>