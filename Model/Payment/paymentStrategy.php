<?php
interface PaymentStrategy {
    public function pay(int $orderId, float $amount);
}
?>
