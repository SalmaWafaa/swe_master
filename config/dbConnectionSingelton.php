<?php

class DatabaseConnection {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $this->connection = new mysqli("localhost", "root", "", "swe_master");
        
        if ($this->connection->connect_error) {
            throw new Exception("Database Connection Failed: " . $this->connection->connect_error);
        }

        // Set character encoding
        $this->connection->set_charset("utf8mb4");
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new DatabaseConnection();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    public function closeConnection() {
        if ($this->connection) {
            $this->connection->close();
            $this->connection = null;
            self::$instance = null;
        }
    }
}

?>
