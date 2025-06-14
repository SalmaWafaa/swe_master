<?php
require_once __DIR__ . '/../Model/OrderModel.php';
require_once __DIR__ . '/../Model/creditcardpayment.php';
// require_once __DIR__ . '/../Model/PayPalPayment.php';
require_once __DIR__ . '/../Model/bankTransferpayment.php';

class OrderController {
    public function processPayment($orderId, $paymentMethod) {
        $totalAmount = Order::getTotalAmount($orderId);
        
        if ($totalAmount <= 0) {
            die("Invalid order or amount.<br>");
        }

        $paymentStrategy = null;
        
        switch ($paymentMethod) {
            case "credit":
                $paymentStrategy = new CreditCardPayment();
                break;
            // case "paypal":
            //     $paymentStrategy = new PayPalPayment();
            //     break;
            case "bank":
                $paymentStrategy = new BankTransferPayment();
                break;
            default:
                die("Invalid payment method.<br>");
        }

        $paymentStrategy->pay($totalAmount);
    }
}
?>
