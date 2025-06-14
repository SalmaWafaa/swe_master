<?php
require_once 'User.php';
require_once __DIR__ . '/../../config/dbConnectionSingelton.php';

class Admin extends User 
{
    public function __construct($id = null, $firstName = null, $lastName = null, $email = null, $password = null) 
    {
        parent::__construct($id, $firstName, $lastName, $email, $password);
    }

    public function login() 
    {
        $query = "SELECT * FROM users WHERE email = ? AND user_type_id = 1";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($this->password, $row['password'])) {
                // Update object properties
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
                  VALUES (?, ?, ?, ?, 1)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssss", $this->firstName, $this->lastName, $this->email, $hashedPassword);
        return $stmt->execute();
    }

    public function editAccount() {
        $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);
        $query = "UPDATE users SET 
                  first_name = ?, 
                  last_name = ?, 
                  email = ?, 
                  password = ? 
                  WHERE id = ? AND user_type_id = 1";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssssi", 
            $this->firstName, 
            $this->lastName, 
            $this->email, 
            $hashedPassword, 
            $this->id
        );
        return $stmt->execute();
    }
}