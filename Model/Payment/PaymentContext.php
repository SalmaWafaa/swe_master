<?php
require_once 'PaymentStrategy.php';
// File: Model/Payment/PaymentContext.php
class PaymentContext {
    private $strategy;

    public function setStrategy(PaymentStrategy $strategy) {
        $this->strategy = $strategy;
    }

    public function executePayment(int $orderId, float $amount) { // Changed int $amount to float $amount
        $this->strategy->pay($orderId, $amount);
    }
}
?>