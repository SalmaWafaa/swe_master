<?php

require_once 'C:\xampp\htdocs\ecommerce_master\config\Database.php';
require_once 'OrderModel.php';
require_once 'PaymentStrategy.php';

class Cart {
    private static $instance = null;
    private $conn;
    private $table = 'cart'; // Main cart table
    private $itemsTable = 'cart'; // Table for cart items
    private $cartId;

    private function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public static function getInstance(): Cart {
        if (self::$instance === null) {
            self::$instance = new Cart();
        }
        return self::$instance;
    }

    public function initializeCart(int $customerId): void {
        $query = "SELECT id FROM {$this->table} WHERE customer_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $cart = $result->fetch_assoc();
        $stmt->close();

        if ($cart) {
            $this->cartId = $cart['id'];
        // 
        } 
        else {
            $query = "INSERT INTO cart id VALUES (?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $customerId);
            $stmt->execute();
            $this->cartId = $stmt->insert_id;
            $stmt->close();
        }
    }

    public function addItem(int $productId, int $quantity): void {
        if (!$this->cartId) {
            throw new Exception("Cart not initialized. Call initializeCart() first.");
        }

        $query = "SELECT id, quantity FROM {$this->itemsTable} WHERE id = ? AND product_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $this->cartId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $item = $result->fetch_assoc();
        $stmt->close();

        if ($item) {
            $newQuantity = $item['quantity'] + $quantity;
            $query = "UPDATE {$this->itemsTable} SET quantity = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ii", $newQuantity, $item['id']);
            $stmt->execute();
            $stmt->close();
        } 
        else {
            $stmt = $this->conn->prepare("INSERT INTO cart (customer_id, product_id, quantity) 
                              VALUES (?, ?, ?) 
                              ON DUPLICATE KEY UPDATE quantity = quantity + ?");
$stmt->bind_param("iiii", $customer_id, $product_id, $quantity, $quantity);
$stmt->execute();

        }
    }

    public function removeItem(int $productId): void {
        if (!$this->cartId) {
            throw new Exception("Cart not initialized. Call initializeCart() first.");
        }

        $query = "DELETE FROM {$this->itemsTable} WHERE id = ? AND product_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $this->cartId, $productId);
        $stmt->execute();
        $stmt->close();
    }

    public function getTotal(): float {
        if (!$this->cartId) {
            throw new Exception("Cart not initialized. Call initializeCart() first.");
        }

        $query = "SELECT SUM(p.price * ci.quantity) AS total FROM {$this->itemsTable} ci JOIN products p ON ci.product_id = p.product_id WHERE ci.cart_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->cartId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return (float) $row['total'];
    }

    public function getItems(): array {
        $query = "SELECT  p.id, p.name, p.price, ci.quantity FROM {$this->itemsTable} ci JOIN products p ON ci.product_id = p.product_id WHERE ci.cart_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->cartId);
        $stmt->execute();
        $result = $stmt->get_result();
        $items = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $items;
    }
}
