<?php
require_once __DIR__ . '/../../config/dbConnectionSingelton.php';

class Address {
    private $db;

    public function __construct($db = null) {
        if ($db === null) {
            $this->db = Database::getInstance()->getConnection();
        } else {
            $this->db = $db;
        }
    }

    public function getAddressById($id) {
        try {
            $query = "SELECT * FROM address WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getAddressById: " . $e->getMessage());
            throw new Exception("Failed to retrieve address information");
        }
    }

    public function getChildren($parentId) {
        try {
            $query = "SELECT * FROM address WHERE parent_id = ? ORDER BY name";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$parentId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getChildren: " . $e->getMessage());
            throw new Exception("Failed to retrieve location information");
        }
    }

    public function getCountries() {
        try {
            $query = "SELECT * FROM address WHERE type = 'country' ORDER BY name";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getCountries: " . $e->getMessage());
            throw new Exception("Failed to retrieve countries");
        }
    }

    public function getFullAddress($addressId) {
        $address = $this->getAddressById($addressId);
        if (!$address) return null;

        $fullAddress = [$address['name']];
        $currentId = $address['parent_id'];

        while ($currentId) {
            $parent = $this->getAddressById($currentId);
            if ($parent) {
                array_unshift($fullAddress, $parent['name']);
                $currentId = $parent['parent_id'];
            } else {
                break;
            }
        }

        return implode(', ', $fullAddress);
    }

    public function addUserAddress($userId, $addressId, $addressLine1, $addressLine2 = null, $isDefault = false) {
        try {
            $this->db->beginTransaction();

            // If this is set as default, unset any existing default address
            if ($isDefault) {
                $query = "UPDATE user_address SET is_default = FALSE WHERE user_id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$userId]);
            }

            // Insert the new address
            $query = "INSERT INTO user_address (user_id, address_id, address_line1, address_line2, is_default) 
                     VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId, $addressId, $addressLine1, $addressLine2, $isDefault]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getUserAddresses($userId) {
        try {
            $query = "SELECT ua.*, a.name as location_name, a.type as location_type 
                     FROM user_address ua 
                     JOIN address a ON ua.address_id = a.id 
                     WHERE ua.user_id = ? 
                     ORDER BY ua.is_default DESC, ua.created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getUserAddresses: " . $e->getMessage());
            throw new Exception("Failed to retrieve user addresses");
        }
    }

    public function updateUserAddress($addressId, $userId, $addressLine1, $addressLine2 = null, $isDefault = false) {
        try {
            $this->db->beginTransaction();

            if ($isDefault) {
                $query = "UPDATE user_address SET is_default = FALSE WHERE user_id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$userId]);
            }

            $query = "UPDATE user_address 
                     SET address_line1 = ?, address_line2 = ?, is_default = ? 
                     WHERE id = ? AND user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$addressLine1, $addressLine2, $isDefault, $addressId, $userId]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function deleteUserAddress($addressId, $userId) {
        $query = "DELETE FROM user_address WHERE id = ? AND user_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$addressId, $userId]);
    }

    public function getDefaultAddress($userId) {
        $query = "SELECT ua.*, a.name as location_name, a.type as location_type 
                 FROM user_address ua 
                 JOIN address a ON ua.address_id = a.id 
                 WHERE ua.user_id = ? AND ua.is_default = TRUE 
                 LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
} 