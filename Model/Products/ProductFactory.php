<?php

require_once 'C:\xampp\htdocs\ecommerce_master\config\Database.php';

abstract class ProductFactory {
    protected $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    abstract public function createProduct($data);
}