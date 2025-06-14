<?php
require_once 'PaymentStrategy.php';

class BankTransferPayment implements PaymentStrategy {
    public function pay($amount) {
        echo "Paid $amount via Bank Transfer.";
    }
}
?>
