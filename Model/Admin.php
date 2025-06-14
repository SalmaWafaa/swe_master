<?php

require_once 'User.php';
require_once 'C:\xampp\htdocs\ecommerce_master\config\Database.php';

class Admin extends User {
    private $db;

    public function __construct($id, $firstName, $lastName, $email, $password) {
        parent::__construct($id, $firstName, $lastName, $email, $password);
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }

    // Implementation of abstract methods from the User class
    public function login() {
        // Check if the admin exists in the database
        $query = "SELECT * FROM users WHERE email = ? AND user_type_id = 1"; // Assuming 1 is the admin type
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($this->password, $row['password'])) {
                // Set admin details
                $this->id = $row['id'];
                $this->firstName = $row['first_name'];
                $this->lastName = $row['last_name'];
                return true;
            }
        }
        return false;
    }

    public function register() {
        // Hash the password
        $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);

        // Insert the admin into the database
        $query = "INSERT INTO users (first_name, last_name, email, password, user_type_id) VALUES (?, ?, ?, ?, 1)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssss", $this->firstName, $this->lastName, $this->email, $hashedPassword);

        return $stmt->execute();
    }

    public function editAccount() {
        // Update admin details in the database
        $query = "UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sssi", $this->firstName, $this->lastName, $this->email, $this->id);

        return $stmt->execute();
    }

    // Admin-specific methods
    public function addCategory($category) {
        $query = "INSERT INTO categories (name, image, parent_id) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssi", $category['name'], $category['image'], $category['parent_id']);

        return $stmt->execute();
    }

    public function updateCategory($category) {
        $query = "UPDATE categories SET name = ?, image = ?, parent_id = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssii", $category['name'], $category['image'], $category['parent_id'], $category['id']);

        return $stmt->execute();
    }

    public function deleteCategory($categoryId) {
        $query = "DELETE FROM categories WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $categoryId);

        return $stmt->execute();
    }

    public function addProduct($product) {
        $query = "INSERT INTO products (name, category_id, product_type_id, description, price, on_sale, rate, quantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param(
            "siisddii",
            $product['name'],
            $product['category_id'],
            $product['product_type_id'],
            $product['description'],
            $product['price'],
            $product['on_sale'],
            $product['rate'],
            $product['quantity']
        );

        return $stmt->execute();
    }

    public function updateProduct($product) {
        $query = "UPDATE products SET name = ?, category_id = ?, product_type_id = ?, description = ?, price = ?, on_sale = ?, rate = ?, quantity = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param(
            "siisddiii",
            $product['name'],
            $product['category_id'],
            $product['product_type_id'],
            $product['description'],
            $product['price'],
            $product['on_sale'],
            $product['rate'],
            $product['quantity'],
            $product['id']
        );

        return $stmt->execute();
    }

    public function deleteProduct($productId) {
        $query = "DELETE FROM products WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $productId);

        return $stmt->execute();
    }

    public function manageOrders() {
        // Fetch all orders from the database
        $query = "SELECT * FROM orders";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function respondToContactUs($messageId, $response) {
        // Update the contact message with a response
        $query = "UPDATE contactus SET response = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $response, $messageId);

        return $stmt->execute();
    }
}