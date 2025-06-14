<?php
class OrderModel {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createOrder($userId, $cartItems, $shippingAddressId, $billingAddressId, $paymentMethod) {
        try {
            $this->db->beginTransaction();

            // Step 1: Insert into Orders
            $stmt = $this->db->prepare("INSERT INTO orders (customer_id, shipping_address_id, billing_address_id, status, created_at) 
                                        VALUES (:customer_id, :shipping_address_id, :billing_address_id, 'Pending', NOW())");
            $stmt->execute([
                'customer_id' => $userId,
                'shipping_address_id' => $shippingAddressId,
                'billing_address_id' => $billingAddressId
            ]);
            $orderId = $this->db->lastInsertId();

            // Step 2: Insert into Order Items
            foreach ($cartItems as $item) {
                $stmt = $this->db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) 
                                            VALUES (:order_id, :product_id, :quantity, :price)");
                $stmt->execute([
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }

            // Step 3: Insert into Payment
            $totalAmount = 0;
            foreach ($cartItems as $item) {
                $totalAmount += $item['quantity'] * $item['price'];
            }

            $stmt = $this->db->prepare("INSERT INTO payment (order_id, billing_address_id, payment_method, paid_amount) 
                                        VALUES (:order_id, :billing_address_id, :payment_method, :paid_amount)");
            $stmt->execute([
                'order_id' => $orderId,
                'billing_address_id' => $billingAddressId,
                'payment_method' => $paymentMethod,
                'paid_amount' => $totalAmount
            ]);

            $this->db->commit();

            return $orderId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getOrderById($orderId) {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = :id");
        $stmt->execute(['id' => $orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
