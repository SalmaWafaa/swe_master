<?php
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

    // Method to add an item to the cart or update quantity if it exists
    public function addItemToCart($cartId, $productId, $quantity = 1) {
        // Check if item already exists in cart
        $stmt = $this->db->prepare("SELECT quantity FROM cartitem WHERE cart_id = :cart_id AND product_id = :product_id");
        $stmt->execute(['cart_id' => $cartId, 'product_id' => $productId]);
        $existingQuantity = $stmt->fetchColumn();

        if ($existingQuantity) {
            // Item exists, update quantity
            $newQuantity = $existingQuantity + $quantity;
            $updateStmt = $this->db->prepare("UPDATE cartitem SET quantity = :quantity WHERE cart_id = :cart_id AND product_id = :product_id");
            $updateStmt->execute([
                'quantity' => $newQuantity,
                'cart_id' => $cartId,
                'product_id' => $productId
            ]);
        } else {
            // Item does not exist, insert new item
            $insertStmt = $this->db->prepare("INSERT INTO cartitem (cart_id, product_id, quantity) VALUES (:cart_id, :product_id, :quantity)");
            $insertStmt->execute([
                'cart_id' => $cartId,
                'product_id' => $productId,
                'quantity' => $quantity
            ]);
        }
    }

    // Method to decrease item quantity in the cart
    public function decreaseItemQuantity($cartId, $productId) {
        $stmt = $this->db->prepare("SELECT quantity FROM cartitem WHERE cart_id = :cart_id AND product_id = :product_id");
        $stmt->execute(['cart_id' => $cartId, 'product_id' => $productId]);
        $currentQuantity = $stmt->fetchColumn();

        if ($currentQuantity > 1) {
            $newQuantity = $currentQuantity - 1;
            $updateStmt = $this->db->prepare("UPDATE cartitem SET quantity = :quantity WHERE cart_id = :cart_id AND product_id = :product_id");
            $updateStmt->execute([
                'quantity' => $newQuantity,
                'cart_id' => $cartId,
                'product_id' => $productId
            ]);
        } else {
            // If quantity is 1, delete the item instead
            $this->deleteItemFromCart($cartId, $productId);
        }
    }

    // Method to delete an item from the cart
    public function deleteItemFromCart($cartId, $productId) {
        $stmt = $this->db->prepare("DELETE FROM cartitem WHERE cart_id = :cart_id AND product_id = :product_id");
        $stmt->execute(['cart_id' => $cartId, 'product_id' => $productId]);
    }

    public function getCartItems() {
        $cartId = $this->getCartIdByUserId($this->userId);
        if (!$cartId) {
            return [];
        }

        $stmt = $this->db->prepare("
            SELECT
                p.id AS product_id,
                p.name,
                p.price,
                ci.quantity,
                (ci.quantity * p.price) AS total_price
            FROM cartitem ci
            JOIN products p ON ci.product_id = p.id
            LEFT JOIN productimages pi ON p.id = pi.product_id
            WHERE ci.cart_id = :cart_id
            GROUP BY ci.cart_item_id
            ORDER BY ci.cart_item_id DESC
        ");
        
        $stmt->execute(['cart_id' => $cartId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to get cart total
    public function getCartTotal() {
        $cartId = $this->getCartIdByUserId($this->userId);
        if (!$cartId) {
            return 0;
        }

        $stmt = $this->db->prepare("
            SELECT SUM(ci.quantity * p.price) as total
            FROM cartitem ci
            INNER JOIN products p ON ci.product_id = p.id
            WHERE ci.cart_id = :cart_id
        ");
        $stmt->execute(['cart_id' => $cartId]);
        return $stmt->fetchColumn() ?: 0;
    }

    public function clearCart() {
        $cartId = $this->getCartIdByUserId($this->userId);
        if ($cartId) {
            $stmt = $this->db->prepare("DELETE FROM cartitem WHERE cart_id = :cart_id");
            return $stmt->execute(['cart_id' => $cartId]);
        }
        return false;
    }

    public function getCart() {
        $stmt = $this->db->prepare("
            SELECT * FROM cart 
            WHERE user_id = :user_id
        ");
        $stmt->execute(['user_id' => $this->userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getItemQuantity($cartId, $itemId) {
        $sql = "SELECT quantity FROM cartitem WHERE cart_id = :cart_id AND cart_item_id = :item_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':cart_id', $cartId, PDO::PARAM_INT);
        $stmt->bindParam(':item_id', $itemId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['quantity'] : 0;
    }
}