<?php
// File: Model/Payment/BankTransferPayment.php
require_once 'PaymentStrategy.php';
require_once 'PaymentModel.php';


class BankTransferPayment implements PaymentStrategy {
    public function pay(int $orderId, float $amount) { // Changed int $amount to float $amount
        $paymentModel = new PaymentModel();
        $paymentModel->createPayment($orderId, 'Bank Transfer', $amount);
        $paymentModel->updateOrderPaymentType($orderId, 'Bank Transfer'); // Update orders table
    }
}

?>