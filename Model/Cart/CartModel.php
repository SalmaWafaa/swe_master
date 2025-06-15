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
    public function addItemToCart($productId, $quantity = 1, $size = null, $color = null) {
        $cartId = $this->getCartIdByUserId($this->userId);
        if (!$cartId) {
            $cartId = $this->createCart($this->userId);
        }

        // Check if item with same size and color already exists
        $stmt = $this->db->prepare("
            SELECT cart_item_id, quantity 
            FROM cartitem 
            WHERE cart_id = :cart_id 
            AND product_id = :product_id 
            AND (size = :size OR (size IS NULL AND :size IS NULL))
            AND (color = :color OR (color IS NULL AND :color IS NULL))
        ");
        
        $stmt->execute([
            'cart_id' => $cartId,
            'product_id' => $productId,
            'size' => $size,
            'color' => $color
        ]);
        
        $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingItem) {
            // Update quantity of existing item
            $newQuantity = $existingItem['quantity'] + $quantity;
            $stmt = $this->db->prepare("
                UPDATE cartitem 
                SET quantity = :quantity 
                WHERE cart_item_id = :cart_item_id
            ");
            return $stmt->execute([
                'cart_item_id' => $existingItem['cart_item_id'],
                'quantity' => $newQuantity
            ]);
        } else {
            // Insert new item
            $stmt = $this->db->prepare("
                INSERT INTO cartitem (cart_id, product_id, quantity, size, color) 
                VALUES (:cart_id, :product_id, :quantity, :size, :color)
            ");
            return $stmt->execute([
                'cart_id' => $cartId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'size' => $size,
                'color' => $color
            ]);
        }
    }

    // Method to update item quantity
    public function updateItemQuantity($cartId, $itemId, $quantity, $size = null, $color = null) {
        if ($quantity <= 0) {
            return $this->removeItemFromCart($cartId, $itemId, $size, $color);
        }

        $stmt = $this->db->prepare("
            UPDATE cartitem 
            SET quantity = :quantity 
            WHERE cart_item_id = :cart_item_id
        ");
        return $stmt->execute([
            'cart_item_id' => $itemId,
            'quantity' => $quantity
        ]);
    }

    // Method to remove item from cart
    public function removeItemFromCart($cartId, $itemId, $size = null, $color = null) {
        $stmt = $this->db->prepare("
            DELETE FROM cartitem 
            WHERE cart_item_id = :cart_item_id
        ");
        return $stmt->execute([
            'cart_item_id' => $itemId
        ]);
    }
   
    public function getCartItems() {
        $cartId = $this->getCartIdByUserId($this->userId);
        if (!$cartId) {
            return [];
        }

        $stmt = $this->db->prepare("
            SELECT 
                ci.cart_item_id,
                ci.product_id,
                p.name,
                p.price,
                ci.quantity,
                ci.size,
                ci.color,
                (ci.quantity * p.price) as total_price,
                pi.image_url
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