<?php
require_once 'C:/xampp/htdocs/swe_master/Model/Products/AbstractProduct.php';
require_once 'C:/xampp/htdocs/swe_master/Model/Products/ProductIterator.php';
require_once 'C:/xampp/htdocs/swe_master/Model/Products/ProductFactory.php';
require_once 'C:/xampp/htdocs/swe_master/Model/Category/CategoryComposite.php';

class ProductModel {
    private string $table = 'products';
    private ConcreteProductFactory $productFactory;

    public function __construct() {
        $this->productFactory = new ConcreteProductFactory();
    }

    private function getConnection(): PDO {
        $db = Database::getInstance();
        $conn = $db->getConnection();
        
        if (!$conn) {
            error_log("Database connection failed");
            throw new \RuntimeException("Failed to get database connection");
        }
        
        return $conn;
    }

    public function getProductIterator($criteria = []): ProductIterator {
        return new ProductIterator($this->table, $criteria);
    }

    public function addProduct(array $productData): int {
        $conn = $this->getConnection();
        $conn->beginTransaction();
        
        try {
            error_log("Starting addProduct with data: " . print_r($productData, true));
            $this->validateProductData($productData);

            $stmt = $conn->prepare(
                "INSERT INTO {$this->table} (name, description, price, category_id, product_type_id, on_sale, quantity) 
                VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            
            $stmt->execute([
                $productData['name'],
                $productData['description'],
                $productData['price'],
                $productData['category_id'],
                $productData['product_type_id'],
                $productData['on_sale'],
                $productData['quantity'] ?? 0
            ]);
            
            $productId = $conn->lastInsertId();
            error_log("Product inserted with ID: $productId");
            
            $this->insertImages($productId, $productData['images']);
            $this->insertSizes($productId, $productData['sizes']);
            $this->insertColors($productId, $productData['colors']);
            
            $conn->commit();
            error_log("Transaction committed successfully");
            
            return (int)$productId;
        } catch (\Exception $e) {
            $conn->rollBack();
            error_log("ADD PRODUCT ERROR: " . $e->getMessage());
            if ($conn->errorInfo()) {
                error_log("PDO Error Info: " . print_r($conn->errorInfo(), true));
            }
            throw $e;
        }
    }

    public function updateProduct(int $id, array $productData): bool {
        $conn = $this->getConnection();
        $conn->beginTransaction();
        
        try {
            error_log("Starting updateProduct for ID $id with data: " . print_r($productData, true));
            $this->validateProductData($productData);

            $stmt = $conn->prepare(
                "UPDATE {$this->table} SET 
                name = ?, description = ?, price = ?, 
                category_id = ?, product_type_id = ?, 
                on_sale = ?, quantity = ?
                WHERE id = ?"
            );
            
            $stmt->execute([
                $productData['name'],
                $productData['description'],
                $productData['price'],
                $productData['category_id'],
                $productData['product_type_id'],
                $productData['on_sale'],
                $productData['quantity'] ?? 0,
                $id
            ]);
            
            $rowCount = $stmt->rowCount();
            error_log("Rows affected: $rowCount");
            
            if ($rowCount === 0) {
                throw new \RuntimeException("No rows were updated - product may not exist");
            }
            
            $this->updateRelatedData($id, $productData);
            $conn->commit();
            error_log("Update transaction committed successfully");
            
            return true;
        } catch (\Exception $e) {
            $conn->rollBack();
            error_log("UPDATE PRODUCT ERROR: " . $e->getMessage());
            if ($conn->errorInfo()) {
                error_log("PDO Error Info: " . print_r($conn->errorInfo(), true));
            }
            throw $e;
        }
    }

    private function updateRelatedData(int $productId, array $productData): void {
        $conn = $this->getConnection();
        
        try {
            error_log("Updating related data for product ID: $productId");
            
            // Delete old related data
            $conn->prepare("DELETE FROM productimages WHERE product_id = ?")->execute([$productId]);
            $conn->prepare("DELETE FROM productsizes WHERE product_id = ?")->execute([$productId]);
            $conn->prepare("DELETE FROM productcolors WHERE product_id = ?")->execute([$productId]);
            
            // Insert new related data
            $this->insertImages($productId, $productData['images']);
            $this->insertSizes($productId, $productData['sizes']);
            $this->insertColors($productId, $productData['colors']);
            
            error_log("Related data updated successfully");
        } catch (\Exception $e) {
            error_log("Error updating related data: " . $e->getMessage());
            throw $e;
        }
    }

    public function deleteProduct(int $productId): bool {
        $conn = $this->getConnection();
        $conn->beginTransaction();
        
        try {
            error_log("Starting deleteProduct for ID: $productId");
            
            // First delete related data
            $conn->prepare("DELETE FROM productimages WHERE product_id = ?")->execute([$productId]);
            $conn->prepare("DELETE FROM productsizes WHERE product_id = ?")->execute([$productId]);
            $conn->prepare("DELETE FROM productcolors WHERE product_id = ?")->execute([$productId]);
            
            // Then delete the product
            $stmt = $conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
            $stmt->execute([$productId]);
            
            $rowCount = $stmt->rowCount();
            $conn->commit();
            
            error_log("Delete successful, rows affected: $rowCount");
            return $rowCount > 0;
        } catch (\Exception $e) {
            $conn->rollBack();
            error_log("DELETE PRODUCT ERROR: " . $e->getMessage());
            if ($conn->errorInfo()) {
                error_log("PDO Error Info: " . print_r($conn->errorInfo(), true));
            }
            throw $e;
        }
    }

    private function validateProductData(array $productData): void {
        error_log("Validating product data");
        
        if (empty($productData['name'])) {
            throw new \InvalidArgumentException("Product name is required.");
        }
        if (empty($productData['description'])) {
            throw new \InvalidArgumentException("Product description is required.");
        }
        if (!is_numeric($productData['price']) || $productData['price'] <= 0) {
            throw new \InvalidArgumentException("Valid price is required.");
        }
        if (empty($productData['category_id']) || $productData['category_id'] <= 0) {
            throw new \InvalidArgumentException("Valid category is required.");
        }
        if (empty($productData['product_type_id']) || $productData['product_type_id'] <= 0) {
            throw new \InvalidArgumentException("Valid product type is required.");
        }
        if (empty($productData['images'])) {
            throw new \InvalidArgumentException("At least one image is required.");
        }
        if (empty($productData['sizes'])) {
            throw new \InvalidArgumentException("At least one size is required.");
        }
        if (empty($productData['colors'])) {
            throw new \InvalidArgumentException("At least one color is required.");
        }
        
        error_log("Product data validation passed");
    }

    private function insertImages(int $productId, array $images): void {
        $conn = $this->getConnection();
        $stmt = $conn->prepare("INSERT INTO productimages (product_id, image_url) VALUES (?, ?)");
        
        foreach ($images as $image) {
            $trimmedImage = trim($image);
            if (!empty($trimmedImage)) {
                error_log("Inserting image: $trimmedImage for product ID: $productId");
                $stmt->execute([$productId, $trimmedImage]);
            }
        }
    }

    private function insertSizes(int $productId, array $sizes): void {
        $conn = $this->getConnection();
        $stmt = $conn->prepare("INSERT INTO productsizes (product_id, size) VALUES (?, ?)");
        
        foreach ($sizes as $size) {
            $trimmedSize = trim($size);
            if (!empty($trimmedSize)) {
                error_log("Inserting size: $trimmedSize for product ID: $productId");
                $stmt->execute([$productId, $trimmedSize]);
            }
        }
    }

    private function insertColors(int $productId, array $colors): void {
        $conn = $this->getConnection();
        $stmt = $conn->prepare("INSERT INTO productcolors (product_id, color) VALUES (?, ?)");
        
        foreach ($colors as $color) {
            $trimmedColor = trim($color);
            if (!empty($trimmedColor)) {
                error_log("Inserting color: $trimmedColor for product ID: $productId");
                $stmt->execute([$productId, $trimmedColor]);
            }
        }
    }
}