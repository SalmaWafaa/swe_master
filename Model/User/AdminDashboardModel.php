<?php
class AdminDashboardModel {
    private $db;

    public function __construct() 
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllCustomers() 
    {
        try {
            $query = "SELECT id, first_name, last_name, email 
                     FROM users 
                     WHERE user_type_id = 2 
                     ORDER BY id DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getAllCustomers: " . $e->getMessage());
            return [];
        }
    }

    public function deleteCustomerById($id)
    {
        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            throw new \InvalidArgumentException('Invalid customer ID.');
        }

        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);

        if ($stmt->rowCount() === 0) {
            throw new \RuntimeException("Customer not found or already deleted.");
        }
    }

    public function getAllProducts() 
    {
        try {
            $query = "SELECT p.name, p.price, p.quantity, c.name as category_name 
                     FROM products p 
                     LEFT JOIN categories c ON p.category_id = c.id 
                     ORDER BY p.quantity ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getAllProducts: " . $e->getMessage());
            return [];
        }
    }

    public function getProductStatistics() 
    {
        try {
            $stats = [];
            
            // Get total products and total stock with more details
            $query = "SELECT 
                        COUNT(*) as total_products,
                        SUM(quantity) as total_stock,
                        SUM(CASE WHEN quantity < 10 AND quantity > 0 THEN 1 ELSE 0 END) as low_stock,
                        SUM(CASE WHEN quantity = 0 THEN 1 ELSE 0 END) as out_of_stock,
                        SUM(CASE WHEN on_sale > 0 THEN 1 ELSE 0 END) as products_on_sale,
                        AVG(rate) as average_rating
                     FROM products";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $stats['total_products'] = $result['total_products'];
            $stats['total_stock'] = $result['total_stock'] ?? 0;
            $stats['low_stock'] = $result['low_stock'];
            $stats['out_of_stock'] = $result['out_of_stock'];
            $stats['products_on_sale'] = $result['products_on_sale'];
            $stats['average_rating'] = round($result['average_rating'], 2);

            // Get low stock products details with more information
            $query = "SELECT p.name, p.quantity, p.price, p.on_sale, c.name as category_name,
                            (SELECT GROUP_CONCAT(DISTINCT color) FROM productcolors WHERE product_id = p.id) as colors,
                            (SELECT GROUP_CONCAT(DISTINCT size) FROM productsizes WHERE product_id = p.id) as sizes
                     FROM products p
                     LEFT JOIN categories c ON p.category_id = c.id
                     WHERE p.quantity < 10 AND p.quantity > 0
                     ORDER BY p.quantity ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['low_stock_products'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get category distribution
            $query = "SELECT c.name, COUNT(p.id) as product_count
                     FROM categories c
                     LEFT JOIN products p ON c.id = p.category_id
                     GROUP BY c.id, c.name
                     ORDER BY product_count DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['category_distribution'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $stats;
        } catch (PDOException $e) {
            error_log("Error in getProductStatistics: " . $e->getMessage());
            return [
                'total_products' => 0,
                'total_stock' => 0,
                'low_stock' => 0,
                'out_of_stock' => 0,
                'products_on_sale' => 0,
                'average_rating' => 0,
                'low_stock_products' => [],
                'category_distribution' => []
            ];
        }
    }

    public function updateProductStockAndPrice($id, $price, $stock): void
    {
        // Basic validation
        if (!filter_var($id, FILTER_VALIDATE_INT) || $id <= 0) {
            throw new \InvalidArgumentException('Invalid product ID');
        }
        if (!is_numeric($price) || $price < 0) {
            throw new \InvalidArgumentException('Invalid price');
        }
        if (!filter_var($stock, FILTER_VALIDATE_INT) || $stock < 0) {
            throw new \InvalidArgumentException('Invalid stock');
        }

        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare("UPDATE products SET price = ?, quantity = ? WHERE id = ?");
            $stmt->execute([$price, $stock, $id]);

            if ($stmt->rowCount() === 0) {
                error_log("No rows affected — maybe no change or invalid ID: {$id}");
            }

            $this->db->commit();
            error_log("Updated price and stock for product ID {$id}");

        } catch (\Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw new \RuntimeException("Failed to update product: " . $e->getMessage(), 0, $e);
        }
    }

    public function getAllOrdersWithStates() 
    {
        try {
            $query = "SELECT 
                        o.id AS order_id,
                        u.first_name,
                        u.last_name,
                        o.total,
                        o.status AS state,
                        o.date_created,
                        o.payment_type,
                        COUNT(oi.id) as total_items,
                        GROUP_CONCAT(DISTINCT p.name) as products
                     FROM orders o
                     JOIN users u ON o.customer_id = u.id
                     LEFT JOIN orderitems oi ON o.id = oi.order_id
                     LEFT JOIN products p ON oi.product_id = p.id
                     GROUP BY o.id, u.first_name, u.last_name, o.total, o.status, o.date_created, o.payment_type
                     ORDER BY o.date_created DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getAllOrdersWithStates: " . $e->getMessage());
            return [];
        }
    }



}
?> 