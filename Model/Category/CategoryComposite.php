<?php

// Use relative paths based on the current file's directory
require_once __DIR__ . '/../../config/dbConnectionSingelton.php'; // Adjusted path
require_once __DIR__ . '/ICategory.php'; // Adjusted path assuming ICategory is in the same folder

// You might need to include the Product model if methods require it,
// but getProducts currently returns raw data.
// require_once __DIR__ . '/../products/Product.php';

abstract class CategoryComponent {
    abstract public function getId();
    abstract public function getName();
    abstract public function getProducts();
}

class Category extends CategoryComponent {
    private $id;
    private $name;
    private $image;
    private $parentId;
    private $children = [];

    public function __construct($id, $name, $image = null, $parentId = null) {
        $this->id = $id;
        $this->name = $name;
        $this->image = $image;
        $this->parentId = $parentId;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getProducts() {
        $products = [];
        foreach ($this->children as $child) {
            $products = array_merge($products, $child->getProducts());
        }
        return $products;
    }

    public function addChild(CategoryComponent $child) {
        $this->children[] = $child;
    }

    public function getChildren() {
        return $this->children;
    }
}

class CategoryComposite implements ICategory {
    protected $conn;
    protected $table = 'categories';

    // Properties representing the category's data
    public ?int $id = null; // Use nullable types for clarity
    public ?string $name = null;
    public ?string $image = null;
    public ?int $parent_id = null;

    // To hold child categories when loaded (part of Composite pattern)
    public array $subcategories = [];
    // Products could also be loaded into an array of Product objects if needed
    // public array $products = [];

    public function __construct() {
        $dbConnection = Database::getInstance();
        $this->conn = $dbConnection->getConnection();
        if (!$this->conn) {
            // Handle connection error appropriately
            throw new \Exception("Database connection failed.");
        }
    }
    public function getAllCategories() {
        $query = "SELECT id, name FROM categories"; // Adjust this to match your DB structure
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return all categories
    }
    

    // --- Getters ---
    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getImage(): string {
        return $this->image;
    }

    public function getParentId(): int {
        return $this->parent_id;
    }

    // --- Data Loading Methods ---

    /**
     * Populates the current object's properties from an associative array.
     * Helper method for creating objects from DB results.
     */
    protected function hydrate(array $data): void {
        $this->id = (int)$data['id'];
        $this->name = $data['name'];
        $this->image = $data['image'];
        $this->parent_id = isset($data['parent_id']) ? (int)$data['parent_id'] : null;
        // Reset subcategories when hydrating a specific category
        $this->subcategories = [];
    }

    /**
     * Fetch all main categories (those without a parent).
     * Returns an array of CategoryComposite objects.
     */
    public function getMainCategories(): array {
        // Correctly fetch categories where parent_id is NULL
        $query = "SELECT * FROM {$this->table} WHERE parent_id IS NULL";
        $stmt = $this->conn->query($query);
        $mainCategoriesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $mainCategories = [];
        foreach ($mainCategoriesData as $categoryData) {
            $category = new CategoryComposite();
            $category->hydrate($categoryData); // Use hydrate method
            $mainCategories[] = $category; // Add the populated object
        }

        return $mainCategories;
    }

    /**
     * Fetch subcategories for a specific parent category ID.
     * Returns an array of CategoryComposite objects.
     */
    public function getSubcategories(int $parent_id): array {
        $query = "SELECT * FROM {$this->table} WHERE parent_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$parent_id]);
        $subcategoriesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $subcategories = [];
        foreach ($subcategoriesData as $subcategoryData) {
            $subcategory = new CategoryComposite();
            $subcategory->hydrate($subcategoryData); // Use hydrate method
            $subcategories[] = $subcategory; // Add the populated object
        }

        // Optionally, assign to the current object if it matches the parent_id
        // if ($this->id === $parent_id) {
        //     $this->subcategories = $subcategories;
        // }

        return $subcategories;
    }

    /**
     * Fetch a single category by ID and return it as an object.
     * Returns a CategoryComposite object or null if not found.
     */
    public function getCategoryById(int $id): ?CategoryComposite { // Return type hint fixed
        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        $categoryData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($categoryData) {
            $category = new CategoryComposite();
            $category->hydrate($categoryData); // Use hydrate method
            return $category;
        }
        return null;
    }

    /**
     * Fetch a single subcategory by ID (essentially the same as getCategoryById).
     * Returns a CategoryComposite object or null if not found.
     */
    public function getSubcategoryById(int $subcategory_id): ?CategoryComposite { // Return type hint fixed
        // This method is functionally identical to getCategoryById
        return $this->getCategoryById($subcategory_id);
    }

    /**
     * Fetch products associated with the current category object's ID.
     * Returns an array of associative arrays (can be enhanced to return Product objects).
     */
    public function getProducts(): array {
        if ($this->id === null) {
            return []; // Cannot fetch products without an ID
        }
        // Assuming a 'products' table with a 'category_id' column
        $query = "SELECT * FROM products WHERE category_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->id]);
        // For consistency, this could also return Product objects if a Product class exists and has a hydrate method
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- Data Modification Methods ---

    /**
     * Save a new category to the database using the object's properties.
     */
    public function save(): bool {
        $query = "INSERT INTO {$this->table} (name, image, parent_id) VALUES (:name, :image, :parent_id)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':name' => $this->name,
            ':image' => $this->image,
            ':parent_id' => $this->parent_id
        ]);
    }

    /**
     * Update the category in the database using the object's properties.
     */
    public function update(): bool {
        if ($this->id === null) {
            return false; // Cannot update without an ID
        }
        $query = "UPDATE {$this->table} SET name = :name, image = :image, parent_id = :parent_id WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':name' => $this->name,
            ':image' => $this->image,
            ':parent_id' => $this->parent_id,
            ':id' => $this->id
        ]);
    }

    /**
     * Delete the category represented by the current object from the database.
     */
    public function delete(): bool {
        if ($this->id === null) {
            return false; // Cannot delete without an ID
        }
        // Consider adding logic here to delete subcategories or handle constraints
        return $this->deleteById($this->id);
    }

    /**
     * Delete category by a specific ID.
     * Note: This is a static-like operation but kept instance for PDO connection access.
     * Consider implications for deleting related data (subcategories, products).
     */
    public function deleteById($categoryId) {
        // Check if the category exists
        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$categoryId]);
        $categoryData = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($categoryData) {
            // Perform deletion if category exists
            $deleteQuery = "DELETE FROM {$this->table} WHERE id = ?";
            $deleteStmt = $this->conn->prepare($deleteQuery);
            return $deleteStmt->execute([$categoryId]);
        }
        
        return false; // Return false if category doesn't exist
    }
    

    // --- ICategory Interface Methods (Ensure they match the interface) ---
    // Add/Remove methods typical for Composite Pattern if needed, e.g.:
    // public function add(ICategory $category): void {
    //     if ($category->getParentId() === $this->id) {
    //         $this->subcategories[] = $category;
    //     } else {
    //         // Handle error or different logic
    //     }
    // }
    // public function remove(ICategory $category): void {
    //     // Logic to remove category from $this->subcategories
    // }

}
?>