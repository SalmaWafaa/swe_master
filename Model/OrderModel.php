<?php

require_once 'PaymentStrategy.php';
require_once 'C:/xampp/htdocs/ecommerce_master/config/Database.php';

class Order {
    private $paymentMethod;
    private $db;

    public function __construct(PaymentStrategy $paymentMethod) {
        $this->paymentMethod = $paymentMethod;
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }

    public function processPayment(float $amount): bool {
        return $this->paymentMethod->pay($amount);
    }

    public static function getTotalAmount(int $orderId): float {
        $db = DatabaseConnection::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT total FROM orders WHERE id = ?");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ? (float) $result['total'] : 0.0;
    }

    
}