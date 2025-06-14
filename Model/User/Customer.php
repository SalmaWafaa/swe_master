<?php

require_once 'User.php';
require_once __DIR__ . '/../../config/dbConnectionSingelton.php';

class Customer extends User {
    public function __construct($id = null, $firstName = null, $lastName = null, $email = null, $password = null) {
        parent::__construct($id, $firstName, $lastName, $email, $password);
        $this->db = Database::getInstance()->getConnection(); // Initialize the database connection
    }

    public function login() {
        $query = "SELECT * FROM users WHERE email = ? AND user_type_id = 2";
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            throw new Exception("Failed to prepare statement.");
        }

        // Bind parameters using PDO's bindValue method
        $stmt->bindValue(1, $this->email, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);  // Fetch the result as an associative array

        if ($row) {
            if (password_verify($this->password, $row['password'])) {
                // Update object properties with the data retrieved from the database
                $this->id = $row['id'];
                $this->firstName = $row['first_name'];
                $this->lastName = $row['last_name'];
                return true;
            }
        }
        return false;
    }

    public function register() {
        $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);
        $query = "INSERT INTO users (first_name, last_name, email, password, user_type_id) 
                  VALUES (?, ?, ?, ?, 2)";
        $stmt = $this->db->prepare($query);

        // Bind parameters using PDO's bindValue method
        $stmt->bindValue(1, $this->firstName, PDO::PARAM_STR);
        $stmt->bindValue(2, $this->lastName, PDO::PARAM_STR);
        $stmt->bindValue(3, $this->email, PDO::PARAM_STR);
        $stmt->bindValue(4, $hashedPassword, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function editAccount() {
        $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);
        $query = "UPDATE users SET 
                  first_name = ?, 
                  last_name = ?, 
                  email = ?, 
                  password = ? 
                  WHERE id = ? AND user_type_id = 2";
        $stmt = $this->db->prepare($query);

        // Bind parameters using PDO's bindValue method
        $stmt->bindValue(1, $this->firstName, PDO::PARAM_STR);
        $stmt->bindValue(2, $this->lastName, PDO::PARAM_STR);
        $stmt->bindValue(3, $this->email, PDO::PARAM_STR);
        $stmt->bindValue(4, $hashedPassword, PDO::PARAM_STR);
        $stmt->bindValue(5, $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}


// require_once 'User.php';
// require_once __DIR__ . '/../config/dbConnectionSingelton.php';

// class Customer extends User {
//     private $db;

//     public function __construct($id, $firstName, $lastName, $email, $password) {
//         parent::__construct($id, $firstName, $lastName, $email, $password);
//         $database = Database::getInstance();
//         $this->db = $database->getConnection();
//     }

//     // Implementation of abstract methods from the User class
//     public function login() {
//         // Check if the customer exists in the database
//         $query = "SELECT * FROM users WHERE email = ? AND user_type_id = 2"; // Assuming 2 is the customer type
//         $stmt = $this->db->prepare($query);
//         $stmt->bind_param("s", $this->email);
//         $stmt->execute();
//         $result = $stmt->get_result();

//         if ($result->num_rows > 0) {
//             $row = $result->fetch_assoc();
//             if (password_verify($this->password, $row['password'])) {
//                 // Set customer details
//                 $this->id = $row['id'];
//                 $this->firstName = $row['first_name'];
//                 $this->lastName = $row['last_name'];
//                 return true;
//             }
//         }
//         return false;
//     }

//     public function register() {
//         // Hash the password
//         $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);

//         // Insert the customer into the database
//         $query = "INSERT INTO users (first_name, last_name, email, password, user_type_id) VALUES (?, ?, ?, ?, 2)";
//         $stmt = $this->db->prepare($query);
//         $stmt->bind_param("ssss", $this->firstName, $this->lastName, $this->email, $hashedPassword);

//         return $stmt->execute();
//     }

//     // Method to fetch customer details by ID
//     public function getCustomerById() {
//         $query = "SELECT * FROM users WHERE id = ?";
//         $stmt = $this->db->prepare($query);
//         $stmt->bind_param("i", $this->id);
//         $stmt->execute();
//         $result = $stmt->get_result();

//         return $result->fetch_assoc();
//     }

//     // Method to update customer details
//     public function editAccount() {
//         $query = "UPDATE users SET first_name = ?, last_name = ?, email = ?, password = ? WHERE id = ?";
//         $stmt = $this->db->prepare($query);
//         $stmt->bind_param("ssssi", $this->firstName, $this->lastName, $this->email, $this->password, $this->id);

//         return $stmt->execute();
//     }

    // // Customer-specific methods
    // public function addOrder($order) {
    //     $query = "INSERT INTO orders (customer_id, total, status, address, payment_type) VALUES (?, ?, ?, ?, ?)";
    //     $stmt = $this->db->prepare($query);
    //     $stmt->bind_param("idsss", $this->id, $order['total'], $order['status'], $order['address'], $order['payment_type']);

    //     if ($stmt->execute()) {
    //         $orderId = $stmt->insert_id;

    //         // Add order items
    //         foreach ($order['items'] as $item) {
    //             $this->addOrderItem($orderId, $item['product_id'], $item['quantity']);
    //         }

    //         return $orderId;
    //     }

    //     return false;
    // }

    // private function addOrderItem($orderId, $productId, $quantity) {
    //     $query = "INSERT INTO orderitems (order_id, product_id, quantity) VALUES (?, ?, ?)";
    //     $stmt = $this->db->prepare($query);
    //     $stmt->bind_param("iii", $orderId, $productId, $quantity);

    //     return $stmt->execute();
    // }

    // public function addToCart($productId, $quantity) {
    //     // Check if the customer already has a cart
    //     $cartId = $this->getCartId();

    //     if (!$cartId) {
    //         // Create a new cart for the customer
    //         $query = "INSERT INTO cart (customer_id) VALUES (?)";
    //         $stmt = $this->db->prepare($query);
    //         $stmt->bind_param("i", $this->id);
    //         $stmt->execute();
    //         $cartId = $stmt->insert_id;
    //     }

    //     // Add the product to the cart
    //     $query = "INSERT INTO cartitems (cart_id, product_id, quantity) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE quantity = quantity + ?";
    //     $stmt = $this->db->prepare($query);
    //     $stmt->bind_param("iiii", $cartId, $productId, $quantity, $quantity);

    //     return $stmt->execute();
    // }

    // private function getCartId() {
    //     $query = "SELECT id FROM cart WHERE customer_id = ?";
    //     $stmt = $this->db->prepare($query);
    //     $stmt->bind_param("i", $this->id);
    //     $stmt->execute();
    //     $result = $stmt->get_result();

    //     if ($result->num_rows > 0) {
    //         $row = $result->fetch_assoc();
    //         return $row['id'];
    //     }

    //     return false;
    // }

    // public function addToFavorites($productId) {
    //     $query = "INSERT INTO favorites (customer_id, product_id) VALUES (?, ?)";
    //     $stmt = $this->db->prepare($query);
    //     $stmt->bind_param("ii", $this->id, $productId);

    //     return $stmt->execute();
    // }

    // public function addReview($productId, $orderId, $rating, $comment) {
    //     $query = "INSERT INTO reviews (customer_id, product_id, order_id, rating, comment) VALUES (?, ?, ?, ?, ?)";
    //     $stmt = $this->db->prepare($query);
    //     $stmt->bind_param("iiiis", $this->id, $productId, $orderId, $rating, $comment);

    //     return $stmt->execute();
    // }

    // public function viewOrderHistory() {
    //     $query = "SELECT * FROM orders WHERE customer_id = ?";
    //     $stmt = $this->db->prepare($query);
    //     $stmt->bind_param("i", $this->id);
    //     $stmt->execute();
    //     $result = $stmt->get_result();

    //     return $result->fetch_all(MYSQLI_ASSOC);
    // }

    // public function contactUs($subject, $message) {
    //     $query = "INSERT INTO contactus (customer_id, subject, message) VALUES (?, ?, ?)";
    //     $stmt = $this->db->prepare($query);
    //     $stmt->bind_param("iss", $this->id, $subject, $message);

    //     return $stmt->execute();
    // }
//}


