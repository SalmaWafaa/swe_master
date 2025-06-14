<?php
require_once 'PaymentStrategy.php';

class CreditCardPayment implements PaymentStrategy {
    public function pay($amount) {
        echo "Paid $amount using Credit Card.";
    }
}
?>
