<?php

require_once 'User.php';
require_once 'Admin.php';
require_once 'Customer.php';

class UserFactory {
    private function __construct() {
        // Private constructor to prevent instantiation
    }

    public static function createUser($type, $id, $firstName, $lastName, $email, $password) {
        switch ($type) {
            case 'admin':
                return new Admin($id, $firstName, $lastName, $email, $password);
            case 'customer':
                return new Customer($id, $firstName, $lastName, $email, $password);
            default:
                throw new Exception("Invalid user type");
        }
    }
}