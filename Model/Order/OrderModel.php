<?php
require_once __DIR__ . '/../../config/dbConnectionSingelton.php';
require_once __DIR__ . '/OrderStateFactory.php';
require_once __DIR__ . '/OrderDeco/BaseOrderTotal.php';
require_once __DIR__ . '/OrderDeco/PromoCodeDecorator.php';
require_once __DIR__ . '/OrderDeco/ShippingCostDecorator.php';
require_once __DIR__ . '/OrderDeco/TaxDecorator.php';

class OrderModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

  // In Model/Order/OrderModel.php
// In Model/Order/OrderModel.php
public function createOrder(int $customerId, ?int $shippingAddressId, int $billingAddressId, float $total, string $paymentType = 'Pending', ?int $cartId): int {
    $stmt = $this->db->prepare("
        INSERT INTO orders (customer_id, shipping_address_id, billing_address_id, status, total, payment_type, cart_id)
        VALUES (?, ?, ?, 'Pending', ?, ?, ?) -- Removed created_at from here
    ");
    // $now = date('Y-m-d H:i:s'); // This line is no longer needed if you remove the column
    $stmt->execute([$customerId, $shippingAddressId, $billingAddressId, $total, $paymentType, $cartId]); // Removed $now from here

    return (int)$this->db->lastInsertId();
}

  

    public function createOrderItem(int $orderId, int $productId, int $quantity, float $price, float $total): void {
        $stmt = $this->db->prepare("
            INSERT INTO orderitems (order_id, product_id, quantity, price, total, created_at)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $now = date('Y-m-d H:i:s');
        $stmt->execute([$orderId, $productId, $quantity, $price, $total, $now]);
    }

    public function applyDecorators(int $orderId, float $promo, float $shipping, float $tax): float {
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(oi.quantity * oi.price), 0) AS total_from_order_items -- Fetch total from order items if already created, or from cart if not.
            FROM orderitems oi
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$orderId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // If order items exist, use their total. Otherwise, we might need to get from cart_items first if this method is called pre-order-item creation.
        // Given the current flow, this seems to be called after createOrder, so we should rely on cart_items for the base.
        if (!$row || (float)$row['total_from_order_items'] == 0) {
            $stmt = $this->db->prepare("
                SELECT COALESCE(SUM(ci.quantity * p.price), 0) AS total
                FROM cart_items ci
                JOIN products p ON ci.product_id = p.id
                WHERE ci.customer_id = (
                    SELECT customer_id FROM orders WHERE id = ?
                )
            ");
            $stmt->execute([$orderId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) throw new Exception("Cart not found for order.");
        }


        $order = new BaseOrderTotal((float)$row['total']);
        if ($promo > 0) $order = new PromoCodeDecorator($order, $promo);
        if ($shipping > 0) $order = new ShippingCostDecorator($order, $shipping);
        if ($tax > 0) $order = new TaxDecorator($order, $tax);

        $final = $order->getTotal(); // Assuming calculateTotal is the method to get the decorated total, not update
        // The original code had $order->update($orderId); which implies an update method in decorators.
        // If update method in decorators is meant to persist changes, then it should be called, but typically decorators just calculate.
        // For now, let's assume calculateTotal gives the final value.

        $this->db->prepare("UPDATE orders SET total = ? WHERE id = ?")->execute([$final, $orderId]);
        return $final;
    }

    public function advanceOrderState(int $orderId): void {
        $stmt = $this->db->prepare("SELECT status FROM orders WHERE id = ?");
        $stmt->execute([$orderId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) throw new Exception("Order not found.");

        $current = $row['status'];
        $next = OrderStateFactory::create($current)->next();
        $this->db->prepare("UPDATE orders SET status = ? WHERE id = ?")->execute([$next, $orderId]);
    }
}