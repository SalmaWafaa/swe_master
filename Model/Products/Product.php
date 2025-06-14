<?php

require_once 'C:\xampp\htdocs\ecommerce_master\config\Database.php';

class Product {
    protected $conn;
    protected $table = 'products';

    // Product properties
    public $id;
    public $name;
    public $category_id;
    public $product_type_id;
    public $description;
    public $price;
    public $on_sale;
    public $rate;
    public $quantity;

    // Constructor with database connection
    public function __construct($data = []) {
        $database = new Database();
        $this->conn = $database->getConnection();

        // Initialize properties if data is provided
        if (!empty($data)) {
            $this->id = $data['id'] ?? null;
            $this->name = $data['name'] ?? '';
            $this->category_id = $data['category_id'] ?? '';
            $this->product_type_id = $data['product_type_id'] ?? '';
            $this->description = $data['description'] ?? '';
            $this->price = $data['price'] ?? 0;
            $this->on_sale = $data['on_sale'] ?? 0;
            $this->rate = $data['rate'] ?? 0;
            $this->quantity = $data['quantity'] ?? 0;
        }
    }

    // Create a new product
    public function create() {
        // Check if the category_id exists
        $query = "SELECT id FROM categories WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->category_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            throw new Exception("Invalid category_id: Category does not exist.");
        }
        $stmt->close();

        // Check if the product_type_id exists
        $query = "SELECT id FROM producttypes WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->product_type_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            throw new Exception("Invalid product_type_id: Product type does not exist.");
        }
        $stmt->close();

        // Insert the product
        $query = "INSERT INTO {$this->table} 
                  (name, category_id, product_type_id, description, price, on_sale, rate, quantity) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(
            "siisdddi",
            $this->name,
            $this->category_id,
            $this->product_type_id,
            $this->description,
            $this->price,
            $this->on_sale,
            $this->rate,
            $this->quantity
        );

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }

        $stmt->close();
        return false;
    }

    // Read all products
    public function read() {
        $query = "SELECT * FROM {$this->table}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $products;
    }

    // Read a single product by ID
    public function readOne($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $stmt->close();
        return $product;
    }

    // Update a product
    public function update() {
        $query = "UPDATE {$this->table} 
                  SET name = ?, category_id = ?, product_type_id = ?, 
                      description = ?, price = ?, on_sale = ?, 
                      rate = ?, quantity = ? 
                  WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(
            "siisdddii",
            $this->name,
            $this->category_id,
            $this->product_type_id,
            $this->description,
            $this->price,
            $this->on_sale,
            $this->rate,
            $this->quantity,
            $this->id
        );

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }

        $stmt->close();
        return false;
    }

    // Delete a product
    // Duplicate delete method removed.

    // Get products by category ID
    public function getProductsByCategory($category_id) {
        $query = "SELECT * FROM {$this->table} WHERE category_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $products;
    }
    public function delete() {
        $query = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id);
    
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
    
        $stmt->close();
        return false;
    }
    // Get size chart (default implementation)
    public function getSizeChart() {
        return "Default size chart";
    }
}
