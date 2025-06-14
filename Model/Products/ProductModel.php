<?php
// No need to include ProductModel.php within itself
// include_once __DIR__ . '/../../Model/Products/ProductModel.php';
include_once __DIR__ . '/../../config/dbConnectionSingelton.php'; // Include the database connection singleton

class ProductModel {

    // Use $conn consistently as assigned in the constructor
    private PDO $conn;
    private string $table = 'products';

    public function __construct() {
        try {
            $db = Database::getInstance(); // Get the single instance of the Database
            $this->conn = $db->getConnection(); // Assign the PDO connection to $this->conn
        } catch (\Exception $e) {
             // Log the error during construction if needed
             error_log("Failed to establish database connection in ProductModel: " . $e->getMessage());
             // Rethrow or handle as appropriate for your application structure
             throw new \RuntimeException("ProductModel requires a valid database connection.", 0, $e);
        }
    }

    // --- Add a new product (Corrected to use $this->conn) ---
    public function addProduct($name, $description, $price, $categoryId, $images, $sizes, $colors, $productTypeId, $onSale): int {
        $this->conn->beginTransaction();
        try {
            // Insert the product into the products table
            $stmt = $this->conn->prepare("INSERT INTO {$this->table} (name, description, price, category_id, product_type_id, on_sale) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $description, $price, $categoryId, $productTypeId, $onSale]);
    
            $productId = $this->conn->lastInsertId(); // Get the last inserted product ID
    
            if (!$productId) {
                throw new \Exception("Failed to retrieve last inserted product ID.");
            }
    
            // Insert images
            if (is_array($images)) {
                $imgStmt = $this->conn->prepare("INSERT INTO productimages (product_id, image_url) VALUES (?, ?)");
                foreach ($images as $image) {
                    if (!empty(trim($image))) { // Avoid inserting empty URLs
                        $imgStmt->execute([$productId, trim($image)]);
                    }
                }
            }
    
            // Insert sizes
            if (is_array($sizes)) {
                $sizeStmt = $this->conn->prepare("INSERT INTO productsizes (product_id, size) VALUES (?, ?)");
                foreach ($sizes as $size) {
                    if (!empty(trim($size))) { // Avoid inserting empty sizes
                        $sizeStmt->execute([$productId, trim($size)]);
                    }
                }
            }
    
            // Insert colors
            if (is_array($colors)) {
                $colorStmt = $this->conn->prepare("INSERT INTO productcolors (product_id, color) VALUES (?, ?)");
                foreach ($colors as $color) {
                    if (!empty(trim($color))) { // Avoid inserting empty colors
                        $colorStmt->execute([$productId, trim($color)]);
                    }
                }
            }
    
            $this->conn->commit();
            return (int)$productId; // Return the new product ID
    
        } catch (\Exception $e) { // Catch any exceptions during the insertion
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            throw $e; // Rethrow exception for higher-level handling
        }
    }
    

    public function updateProduct($id, $name, $description, $price, $categoryId, $images, $sizes, $colors, $productTypeId, $onSale): void
    {
        // Basic ID validation
        if (empty($id) || !filter_var($id, FILTER_VALIDATE_INT) || $id <= 0) {
            throw new \InvalidArgumentException('Invalid Product ID provided for update.');
        }

        // Input validation (basic example, expand as needed)
        if (empty(trim($name))) {
            throw new \InvalidArgumentException('Product name cannot be empty.');
        }
        if (!is_numeric($price) || $price < 0) {
             throw new \InvalidArgumentException('Invalid price provided.');
        }
        if (!filter_var($categoryId, FILTER_VALIDATE_INT) || $categoryId <= 0) {
             throw new \InvalidArgumentException('Invalid category ID provided.');
        }
         if (!filter_var($productTypeId, FILTER_VALIDATE_INT) || $productTypeId <= 0) {
             throw new \InvalidArgumentException('Invalid product type ID provided.');
        }
         if (!is_array($images)) $images = []; // Ensure array
         if (!is_array($sizes)) $sizes = [];   // Ensure array
         if (!is_array($colors)) $colors = []; // Ensure array


        $this->conn->beginTransaction();
        try {
            // Prepare the SQL statement for updating the product
            $stmt = $this->conn->prepare(
                "UPDATE {$this->table} SET name = ?, description = ?, price = ?, category_id = ?, product_type_id = ?, on_sale = ? WHERE id = ?"
            );
            // Ensure onSale is treated as integer (0 or 1)
            $onSaleValue = filter_var($onSale, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
            $stmt->execute([trim($name), trim($description), $price, $categoryId, $productTypeId, $onSaleValue, $id]);

            if ($stmt->rowCount() === 0) {
                 error_log("Product update for ID {$id} affected 0 rows. Data might be identical or product ID doesn't exist (though related data will still be processed).");
                 // Optional: Add a check here if you *require* the product to exist first
                 // $checkStmt = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table} WHERE id = ?");
                 // $checkStmt->execute([$id]);
                 // if ($checkStmt->fetchColumn() == 0) {
                 //    throw new \RuntimeException("Product with ID {$id} not found for update.");
                 // }
            }

            // Delete existing related data
            error_log("Deleting existing images, colors, sizes for product ID {$id} before update.");
            $this->conn->prepare("DELETE FROM productimages WHERE product_id = ?")->execute([$id]);
            $this->conn->prepare("DELETE FROM productcolors WHERE product_id = ?")->execute([$id]);
            $this->conn->prepare("DELETE FROM productsizes WHERE product_id = ?")->execute([$id]);

            // Insert new data for images, sizes, and colors
            error_log("Inserting new images, colors, sizes for product ID {$id}.");
            // Insert images
            $imgStmt = $this->conn->prepare("INSERT INTO productimages (product_id, image_url) VALUES (?, ?)");
            foreach ($images as $image) {
                 $trimmedImage = trim($image);
                if (!empty($trimmedImage)) {
                    // Basic URL validation example (can be more sophisticated)
                    if (filter_var($trimmedImage, FILTER_VALIDATE_URL)) {
                        $imgStmt->execute([$id, $trimmedImage]);
                    } else {
                         error_log("Skipping invalid image URL for product ID {$id}: " . $trimmedImage);
                    }
                }
            }
            // Insert sizes
            $sizeStmt = $this->conn->prepare("INSERT INTO productsizes (product_id, size) VALUES (?, ?)");
            foreach ($sizes as $size) {
                 $trimmedSize = trim($size);
                if (!empty($trimmedSize)) {
                    $sizeStmt->execute([$id, $trimmedSize]);
                }
            }
             // Insert colors
            $colorStmt = $this->conn->prepare("INSERT INTO productcolors (product_id, color) VALUES (?, ?)");
            foreach ($colors as $color) {
                 $trimmedColor = trim($color);
                if (!empty($trimmedColor)) {
                    $colorStmt->execute([$id, $trimmedColor]);
                }
            }

            $this->conn->commit();
            error_log("Successfully updated product ID {$id}. Transaction committed.");

        } catch (\Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
                 error_log("Transaction rolled back for product ID {$id} due to error.");
            }
            error_log("Error updating product ID {$id}: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
            // Re-throw the specific exception type if needed, or a generic one
            throw new \RuntimeException("Failed to update product. Error: " . $e->getMessage(), 0, $e);
        }
    }
    // // --- Update product details (Corrected to use $this->conn, refined rowCount) ---
    // public function updateProduct($id, $name, $description, $price, $categoryId, $images, $sizes, $colors, $productTypeId, $onSale): void
    // {
    //     // Basic ID validation
    //     if (empty($id) || !filter_var($id, FILTER_VALIDATE_INT) || $id <= 0) {
    //         throw new \InvalidArgumentException('Invalid Product ID provided for update.');
    //     }

    //     $this->conn->beginTransaction();
    //     try {
    //         // Prepare the SQL statement for updating the product
    //         $stmt = $this->conn->prepare(
    //             "UPDATE {$this->table} SET name = ?, description = ?, price = ?, category_id = ?, product_type_id = ?, on_sale = ? WHERE id = ?"
    //         );
    //         $stmt->execute([$name, $description, $price, $categoryId, $productTypeId, $onSale, $id]);

    //         // Optional: Log if the update didn't change anything, but don't throw error
    //         if ($stmt->rowCount() === 0) {
    //              error_log("Product update for ID {$id} affected 0 rows. Data might be identical or product ID doesn't exist (though related data will still be processed).");
    //              // NOTE: We proceed because related data (images etc.) might still need updating.
    //              // If you need to ensure the product *exists* before updating, add a SELECT check first.
    //         }

    //         // --- Update Related Data (Delete and Re-insert approach) ---
    //         // Note: For high-performance scenarios, consider comparing and updating/inserting/deleting only changed items.

    //         // Delete existing related data
    //         error_log("Deleting existing images, colors, sizes for product ID {$id} before update.");
    //         $this->conn->prepare("DELETE FROM productimages WHERE product_id = ?")->execute([$id]);
    //         $this->conn->prepare("DELETE FROM productcolors WHERE product_id = ?")->execute([$id]);
    //         $this->conn->prepare("DELETE FROM productsizes WHERE product_id = ?")->execute([$id]);
    //         // IMPORTANT: We don't delete from `cartitem` here as it would remove items from active user carts.
    //         // Updating product details generally shouldn't invalidate cart items unless the product becomes unavailable.
    //         // If specific updates *should* affect carts, handle that logic separately (e.g., check stock, availability).

    //         // Insert new data for images, sizes, and colors
    //          error_log("Inserting new images, colors, sizes for product ID {$id}.");
    //          // Insert images (Ensure $images is an array)
    //         if (is_array($images)) {
    //             $imgStmt = $this->conn->prepare("INSERT INTO productimages (product_id, image_url) VALUES (?, ?)");
    //             foreach ($images as $image) {
    //                 if (!empty(trim($image))) {
    //                     $imgStmt->execute([$id, trim($image)]);
    //                 }
    //             }
    //         }
    //          // Insert sizes (Ensure $sizes is an array)
    //         if (is_array($sizes)) {
    //             $sizeStmt = $this->conn->prepare("INSERT INTO productsizes (product_id, size) VALUES (?, ?)");
    //             foreach ($sizes as $size) {
    //                  if (!empty(trim($size))) {
    //                     $sizeStmt->execute([$id, trim($size)]);
    //                  }
    //             }
    //         }
    //          // Insert colors (Ensure $colors is an array)
    //         if (is_array($colors)) {
    //             $colorStmt = $this->conn->prepare("INSERT INTO productcolors (product_id, color) VALUES (?, ?)");
    //             foreach ($colors as $color) {
    //                  if (!empty(trim($color))) {
    //                     $colorStmt->execute([$id, trim($color)]);
    //                  }
    //             }
    //         }

    //         // Commit the transaction
    //         $this->conn->commit();
    //         error_log("Successfully updated product ID {$id}. Transaction committed.");

    //     } catch (\Exception $e) { // Catch PDOException or manually thrown Exception
    //         if ($this->conn->inTransaction()) {
    //             $this->conn->rollBack();
    //         }
    //          error_log("Error updating product ID {$id}: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
    //         // Re-throw the exception to be handled by the controller
    //         throw $e;
    //     }
    // }


    // --- Delete Product (Robust Transactional Version, using $this->conn) ---
    /**
     * Deletes a product and its related data within a transaction.
     * IMPORTANT: Assumes the PDO connection ($this->conn) uses PDO::ERRMODE_EXCEPTION.
     */
    public function deleteProduct($productId): void
    {
        // Basic Input Validation
        if (empty($productId) || !filter_var($productId, FILTER_VALIDATE_INT) || $productId <= 0) {
            throw new \InvalidArgumentException('Invalid Product ID provided for deletion: ' . print_r($productId, true));
        }

        error_log("Attempting to delete product ID: {$productId}");

        try {
            $this->conn->beginTransaction();

            // Delete related cart items first (Foreign Key Constraint)
            error_log("Deleting cart items for product ID: {$productId}");
            $stmtCart = $this->conn->prepare("DELETE FROM cartitem WHERE product_id = ?");
            $stmtCart->execute([$productId]);
            error_log("Deleted " . $stmtCart->rowCount() . " cart items for product ID {$productId}.");

            // Delete related product images
            error_log("Deleting product images for product ID: {$productId}");
            $stmtImages = $this->conn->prepare("DELETE FROM productimages WHERE product_id = ?");
            $stmtImages->execute([$productId]);
            error_log("Deleted " . $stmtImages->rowCount() . " images for product ID {$productId}.");

            // Delete related product colors
            error_log("Deleting product colors for product ID: {$productId}");
            $stmtColors = $this->conn->prepare("DELETE FROM productcolors WHERE product_id = ?");
            $stmtColors->execute([$productId]);
            error_log("Deleted " . $stmtColors->rowCount() . " colors for product ID {$productId}.");

            // Delete related product sizes
            error_log("Deleting product sizes for product ID: {$productId}");
            $stmtSizes = $this->conn->prepare("DELETE FROM productsizes WHERE product_id = ?");
            $stmtSizes->execute([$productId]);
            error_log("Deleted " . $stmtSizes->rowCount() . " sizes for product ID {$productId}.");

            // Now Delete the Product Itself
            error_log("Deleting product main record for ID: {$productId}");
            $stmtProduct = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
            $stmtProduct->execute([$productId]);
            $productRowCount = $stmtProduct->rowCount();
            error_log("Product delete statement affected {$productRowCount} rows for ID {$productId}.");

            // Check if the main product was actually deleted
            if ($productRowCount === 0) {
                 // Product might not have existed. Rollback is appropriate here.
                throw new \Exception("Product deletion failed. Product with ID {$productId} not found or could not be deleted (0 rows affected). Transaction rolled back.");
            }

            $this->conn->commit();
            error_log("Successfully deleted product ID {$productId} and related data. Transaction committed.");

        } catch (\Exception $e) { // Catch PDOException or manually thrown Exception
            $errorMessage = sprintf(
                "Error during product deletion (ID: %s): %s\nFile: %s\nLine: %d",
                $productId, $e->getMessage(), $e->getFile(), $e->getLine()
            );
             error_log("ERROR: " . $errorMessage);

            if ($this->conn->inTransaction()) {
                try {
                    error_log("Attempting transaction rollback for product ID {$productId} due to error.");
                    $this->conn->rollBack();
                    error_log("Transaction rolled back for product ID {$productId}.");
                } catch (\PDOException $rollbackEx) {
                    error_log("CRITICAL: Rollback failed for product ID {$productId} after an error. Rollback Error: " . $rollbackEx->getMessage() . " | Original Error: " . $e->getMessage());
                }
            }
            // Re-throw the original exception
            throw $e;
        }
    }


    // --- Get product by ID (Corrected to use $this->conn) ---
    public function getProductById($productId): ?array
    {
        // Basic ID validation
        if (empty($productId) || !filter_var($productId, FILTER_VALIDATE_INT) || $productId <= 0) {
            error_log("Invalid Product ID requested: " . print_r($productId, true));
            return null; // Or throw InvalidArgumentException
        }
        try {
            $query = "SELECT * FROM {$this->table} WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$productId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null; // Return null if not found
        } catch (\PDOException $e) {
             error_log("Database error fetching product ID {$productId}: " . $e->getMessage());
             return null; // Or re-throw
        }
    }

    // --- Get products by subcategory (Corrected to use $this->conn) ---
     public function getProductsBySubcategory($subcategoryId): array
     {
         if (empty($subcategoryId) || !filter_var($subcategoryId, FILTER_VALIDATE_INT) || $subcategoryId <= 0) {
             error_log("Invalid Subcategory ID requested: " . print_r($subcategoryId, true));
             return []; // Or throw InvalidArgumentException
         }
        try {
            // Assuming 'subcategory_id' is the correct column name in 'products' table
            $query = "SELECT p.*, pi.image_url FROM {$this->table} p
                      LEFT JOIN (SELECT product_id, MIN(image_url) as image_url FROM productimages GROUP BY product_id) pi
                      ON p.id = pi.product_id
                      WHERE p.category_id = ?"; // Adjust column if different
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$subcategoryId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
             error_log("Database error fetching products for subcategory ID {$subcategoryId}: " . $e->getMessage());
             return []; // Or re-throw
        }
     }

    // --- Get product images (Corrected to use $this->conn) ---
    public function getProductImages($productId) {
        $query = "SELECT * FROM productimages WHERE product_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$productId]);
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Log the output for debugging purposes
        error_log("Images for product $productId: " . print_r($images, true));
        return $images;
    }
    

    // --- Get product sizes (Corrected to use $this->conn) ---
    public function getProductSizes($productId) {
        $query = "SELECT * FROM productsizes WHERE product_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$productId]);
        $sizes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Log the output for debugging purposes
        error_log("Sizes for product $productId: " . print_r($sizes, true));
        return $sizes;
    }
    

    // --- Get product colors (Corrected to use $this->conn) ---
    public function getProductColors($productId) {
        $query = "SELECT * FROM productcolors WHERE product_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$productId]);
        $colors = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Log the output for debugging purposes
        error_log("Colors for product $productId: " . print_r($colors, true));
        return $colors;
    }
    
}