<?php

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/ICategory.php';

class CategoryComposite implements ICategory {
    protected $conn;
    protected $table = 'categories';

    public $id;
    public $name;
    public $image;
    public $parent_id;
    public $subcategories = [];
    public $products = [];

    public function __construct() {
        $dbConnection = Database::getInstance();
        $this->conn = $dbConnection->getConnection();
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getImage(): string {
        return $this->image;
    }

    public function getParentCategory(): ?ICategory {
        if ($this->parent_id) {
            $query = "SELECT * FROM {$this->table} WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $this->parent_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $parentData = $result->fetch_assoc();

            if ($parentData) {
                $parentCategory = new CategoryComposite();
                $parentCategory->id = $parentData['id'];
                $parentCategory->name = $parentData['name'];
                $parentCategory->image = $parentData['image'];
                $parentCategory->parent_id = $parentData['parent_id'];
                return $parentCategory;
            }
        }
        return null;
    }
    public function getMainCategories() {
        $query = "SELECT * FROM categories WHERE parent_id IS NULL";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getSubcategories($parent_id) {
        $query = "SELECT * FROM categories WHERE parent_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $parent_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getProducts(): array {
        $query = "SELECT * FROM products WHERE category_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addProduct($product): void {
        $this->products[] = $product;
    }

    public function removeProduct(int $productId): void {
        $this->products = array_filter($this->products, function ($product) use ($productId) {
            return $product['id'] !== $productId;
        });
    }

    public function getProductById(int $productId): ?array {
        foreach ($this->products as $product) {
            if ($product['id'] === $productId) {
                return $product;
            }
        }
        return null;
    }

    public function addSubcategory(ICategory $category): void {
        $this->subcategories[] = $category;
    }

    public function removeSubcategory(int $categoryId): void {
        $this->subcategories = array_filter($this->subcategories, function ($category) use ($categoryId) {
            return $category->getId() !== $categoryId;
        });
    }

    public function getSubcategoryById(int $categoryId): ?ICategory {
        foreach ($this->subcategories as $category) {
            if ($category->getId() === $categoryId) {
                return $category;
            }
        }
        return null;
    }

    public function save(): bool {
        $query = "INSERT INTO {$this->table} (name, image, parent_id) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssi", $this->name, $this->image, $this->parent_id);
        return $stmt->execute();
    }

    public function update(): bool {
        $query = "UPDATE {$this->table} SET name = ?, image = ?, parent_id = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssii", $this->name, $this->image, $this->parent_id, $this->id);
        return $stmt->execute();
    }

    public function delete(): bool {
        $query = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }
    public function getCategoryById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function deleteById($id): bool {
        // Delete all associated products first
        $deleteProductsQuery = "DELETE FROM products WHERE category_id = ?";
        $stmt = $this->conn->prepare($deleteProductsQuery);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    
        // Now delete the category
        $deleteQuery = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($deleteQuery);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
}