<?php
require_once 'condata.php';

class CartModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getCartIdByUserId($userId) {
        $stmt = $this->db->prepare("SELECT cart_id FROM cart WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchColumn();
    }

    public function createCart($userId) {
        $stmt = $this->db->prepare("INSERT INTO cart (user_id) VALUES (:user_id)");
        $stmt->execute(['user_id' => $userId]);
        return $this->db->lastInsertId();
    }

    public function addItemToCart($cartId, $productId, $quantity) {
        try {
            // Prepare the SQL query
            $stmt = $this->db->prepare("INSERT INTO cartitem (cart_id, product_id, quantity) VALUES (:cart_id, :product_id, :quantity)");
    
            // Bind values and execute
            $stmt->execute([
                'cart_id' => $cartId,
                'product_id' => $productId,
                'quantity' => $quantity
            ]);
    
            echo "Query executed successfully. CartId: $cartId, ProductId: $productId, Quantity: $quantity";
        } catch (PDOException $e) {
            // Catch any exceptions and show an error message
            echo "Error: " . $e->getMessage();
        }
    }
    
    
}
?>
