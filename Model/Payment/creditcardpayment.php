<?php
// File: Model/Payment/CreditCardPayment.php
require_once 'PaymentStrategy.php';
require_once 'PaymentModel.php';


class CreditCardPayment implements PaymentStrategy {
    public function pay(int $orderId, float $amount) { // Changed int $amount to float $amount
        $paymentModel = new PaymentModel();
        $paymentModel->createPayment($orderId, 'Credit Card', $amount); // Changed 'credit_card' to 'Credit Card' for display
        $paymentModel->updateOrderPaymentType($orderId, 'Credit Card'); // Update orders table
    }
}

?>