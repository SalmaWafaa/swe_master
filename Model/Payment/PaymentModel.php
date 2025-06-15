<?php
// Make sure dbConnectionSingelton.php is correctly required
require_once __DIR__ . '/../../config/dbConnectionSingelton.php';

class PaymentModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // New method to fetch billing_address_id from an order
    public function getBillingAddressIdForOrder(int $orderId): ?int {
        $stmt = $this->db->prepare("SELECT billing_address_id FROM orders WHERE id = ?");
        $stmt->execute([$orderId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['billing_address_id'] : null;
    }

    public function createPayment(int $orderId, string $method, float $amount): void {
        $billingId = $this->getBillingAddressIdForOrder($orderId); // Dynamically fetch billing ID

        if ($billingId === null) {
            throw new Exception("Billing address ID not found for order ID: " . $orderId);
        }

        // Make sure order ID exists before inserting payment
        $check = $this->db->prepare("SELECT id FROM orders WHERE id = ?");
        $check->execute([$orderId]);
        if (!$check->fetch()) {
            throw new Exception("Invalid order ID " . $orderId . " for payment.");
        }

        $status = 'paid';
        $code = 'pay_' . uniqid(); // Generate a unique payment code
        $timestamp = date("Y-m-d H:i:s");

        $stmt = $this->db->prepare("
            INSERT INTO payment
            (order_id, billing_address_id, payment_method, payment_status, payment_code, paid_amount, paid_at, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $orderId,
            $billingId,
            $method,
            $status,
            $code,
            $amount,
            $timestamp,
            $timestamp
        ]);
    }

    // New method to update payment type in orders table
    public function updateOrderPaymentType(int $orderId, string $paymentType): void {
        $stmt = $this->db->prepare("UPDATE orders SET payment_type = ?, status = 'Processing' WHERE id = ?"); // Also update order status
        $stmt->execute([$paymentType, $orderId]);
    }
}
?>