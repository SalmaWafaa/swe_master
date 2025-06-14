
<?php 

class ProductTypeModel {

private $conn;
private $table = 'producttypes'; // Assuming your product types table is named 'product_types'

public function __construct() {
    $db = Database::getInstance();
    $this->conn = $db->getConnection();
}

// Method to fetch all product types
public function getAllProductTypes() {
    $query = "SELECT id, name FROM {$this->table}"; // Adjust table name and column names accordingly
    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return all product types
}
}
?>