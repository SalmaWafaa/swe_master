<?php

class AddressController {
    private $addressModel;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->addressModel = new Address($db);
    }

    public function getCountries() {
        try {
            $countries = $this->addressModel->getCountries();
            echo json_encode(['success' => true, 'data' => $countries]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getChildren() {
        try {
            if (!isset($_GET['parent_id'])) {
                throw new Exception('Parent ID is required');
            }

            $children = $this->addressModel->getChildren($_GET['parent_id']);
            echo json_encode(['success' => true, 'data' => $children]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function addAddress() {
        try {
            if (!isset($_SESSION['user_id'])) {
                throw new Exception('User must be logged in');
            }

            $requiredFields = ['address_id', 'address_line1'];
            foreach ($requiredFields as $field) {
                if (!isset($_POST[$field]) || empty($_POST[$field])) {
                    throw new Exception("$field is required");
                }
            }

            $userId = $_SESSION['user_id'];
            $addressId = $_POST['address_id'];
            $addressLine1 = $_POST['address_line1'];
            $addressLine2 = $_POST['address_line2'] ?? null;
            $isDefault = isset($_POST['is_default']) ? (bool)$_POST['is_default'] : false;

            $this->addressModel->addUserAddress($userId, $addressId, $addressLine1, $addressLine2, $isDefault);
            echo json_encode(['success' => true, 'message' => 'Address added successfully']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function updateAddress() {
        try {
            if (!isset($_SESSION['user_id'])) {
                throw new Exception('User must be logged in');
            }

            $requiredFields = ['address_id', 'address_line1'];
            foreach ($requiredFields as $field) {
                if (!isset($_POST[$field]) || empty($_POST[$field])) {
                    throw new Exception("$field is required");
                }
            }

            $userId = $_SESSION['user_id'];
            $addressId = $_POST['address_id'];
            $addressLine1 = $_POST['address_line1'];
            $addressLine2 = $_POST['address_line2'] ?? null;
            $isDefault = isset($_POST['is_default']) ? (bool)$_POST['is_default'] : false;

            $this->addressModel->updateUserAddress($addressId, $userId, $addressLine1, $addressLine2, $isDefault);
            echo json_encode(['success' => true, 'message' => 'Address updated successfully']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteAddress() {
        try {
            if (!isset($_SESSION['user_id'])) {
                throw new Exception('User must be logged in');
            }

            if (!isset($_POST['address_id'])) {
                throw new Exception('Address ID is required');
            }

            $userId = $_SESSION['user_id'];
            $addressId = $_POST['address_id'];

            $this->addressModel->deleteUserAddress($addressId, $userId);
            echo json_encode(['success' => true, 'message' => 'Address deleted successfully']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getUserAddresses() {
        try {
            if (!isset($_SESSION['user_id'])) {
                throw new Exception('User must be logged in');
            }

            $addresses = $this->addressModel->getUserAddresses($_SESSION['user_id']);
            echo json_encode(['success' => true, 'data' => $addresses]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getDefaultAddress() {
        try {
            if (!isset($_SESSION['user_id'])) {
                throw new Exception('User must be logged in');
            }

            $address = $this->addressModel->getDefaultAddress($_SESSION['user_id']);
            echo json_encode(['success' => true, 'data' => $address]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
} 