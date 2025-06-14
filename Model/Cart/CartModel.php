<?php
require_once __DIR__ . '/../../config/dbConnectionSingelton.php';


class CartModel {
    private $db;
    private $userId;
    private static $instances = [];

    // Private constructor to prevent creating a new instance via `new`
    private function __construct($userId) {
        $this->db = Database::getInstance()->getConnection();
        $this->userId = $userId;
    }

    // Ensure a single instance of CartModel per user
    public static function getInstance($userId) {
        if (!isset(self::$instances[$userId])) {
            self::$instances[$userId] = new CartModel($userId);
        }
        return self::$instances[$userId];
    }

    // Method to get the cart ID for a user
    public function getCartIdByUserId($userId) {
        $stmt = $this->db->prepare("SELECT cart_id FROM cart WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchColumn();
    }

    // Method to create a cart for the user
    public function createCart($userId) {
        $stmt = $this->db->prepare("INSERT INTO cart (user_id) VALUES (:user_id)");
        $stmt->execute(['user_id' => $userId]);
        return $this->db->lastInsertId();
    }

    // Method to add an item to the cart
    public function addItemToCart($cartId, $productId, $quantity) {
        $stmt = $this->db->prepare("INSERT INTO cartitem (cart_id, product_id, quantity) VALUES (:cart_id, :product_id, :quantity)");
        $stmt->execute([
            'cart_id' => $cartId,
            'product_id' => $productId,
            'quantity' => $quantity
        ]);
    }
   
    public function getCartItems() {
        $stmt = $this->db->prepare("
            SELECT 
                p.id,
                p.name,
                ci.quantity,
                p.price, 
                (ci.quantity * p.price) AS total_price
            FROM cartitem ci
            INNER JOIN cart c ON ci.cart_id = c.cart_id
            INNER JOIN products p ON ci.product_id = p.id
            WHERE c.user_id = :user_id
        ");
        $stmt->execute(['user_id' => $this->userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}