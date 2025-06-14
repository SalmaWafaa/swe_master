<?php

require_once 'C:\xampp\htdocs\ecommerce_master\Model\Category\CategoryComposite.php';

class CategoryController {
    // Display the add category form
    public function addCategoryForm() {
        include 'C:\xampp\htdocs\ecommerce_master\View\categories\add_category.php';
    }

    // Handle the add category form submission
    public function createCategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category = new CategoryComposite();
            $category->name = $_POST['name'];
            $category->image = $_POST['image'];
            $category->parent_id = $_POST['parent_id'] ?? null;

            if ($category->save()) {
                header("Location: index.php?controller=Category&action=listCategories");
                exit();
            } else {
                echo "Failed to create category.";
            }
        }
    }
    public function viewSubcategoryProducts($subcategory_id) {
        $product = new Product();
        $products = $product->getProductsByCategory($subcategory_id);

        // Include the view file to display products
        include 'C:\xampp\htdocs\ecommerce_master\View\categories\subcategory_products.php';
    }
    // Display the edit category form
    public function editCategoryForm($id) {
        $category = new CategoryComposite();
        $category->id = $id;
        $categoryData = $category->getSubcategoryById($id);
        include 'C:\xampp\htdocs\ecommerce_master\View\categories\edit_category.php';
    }

    // Handle the edit category form submission
    public function updateCategory($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category = new CategoryComposite();
            $category->id = $id;
            $category->name = $_POST['name'];
            $category->image = $_POST['image'];
            $category->parent_id = $_POST['parent_id'] ?? null;

            if ($category->update()) {
                header("Location: index.php?controller=Category&action=listCategories");
                exit();
            } else {
                echo "Failed to update category.";
            }
        }
    }

    // Display the delete category confirmation page
    public function deleteCategoryForm($id) {
        $category = new CategoryComposite();
        $categoryData = $category->getCategoryById($id); // Correct method
    
        include 'C:\xampp\htdocs\ecommerce_master\View\categories\delete_category.php';
    }
    

    // Handle the delete category action
    public function deleteCategory($id) {
        $category = new CategoryComposite();
    
        // Check if category exists
        $categoryData = $category->getCategoryById($id);
        if (!$categoryData) {
            die("Error: Category not found.");
        }
    
        // Delete all subcategories first
        $subcategories = $category->getSubcategories($id);
        foreach ($subcategories as $subcategory) {
            $category->deleteById($subcategory['id']);
        }
    
        // Delete category itself
        if ($category->deleteById($id)) {
            header("Location: index.php?controller=Category&action=listCategories&message=CategoryDeleted");
            exit();
        } else {
            die("Error: Failed to delete category.");
        }
    }
    

    public function listCategories() {
        $category = new CategoryComposite();
    
        // Fetch main categories (Male and Female)
        $mainCategories = $category->getMainCategories();
    
        // Debugging: Print fetched main categories
        echo "<pre>";
        print_r($mainCategories);
        echo "</pre>";
    
        // Fetch subcategories for each main category
        foreach ($mainCategories as &$mainCategory) {
            $mainCategory['subcategories'] = $category->getSubcategories($mainCategory['id']);
        }
    
        // Include the view file to display categories
        include 'C:\xampp\htdocs\ecommerce_master\View\categories\category_list.php';
    }

    // public function listMaleCategories() {
    //     $category = new CategoryComposite();
    //     $category->id = 1; // Assuming Male category has id 1
    //     $categories = $category->getSubcategories(1);
    //     include 'C:\xampp\htdocs\ecommerce_master\View\categories\category_details.php';
    // }

    // public function listFemaleCategories() {
    //     $category = new CategoryComposite();
    //     $category->id = 2; // Assuming Female category has id 2
    //     $categories = $category->getSubcategories(2);
    //     include 'C:\xampp\htdocs\ecommerce_master\View\categories\category_details.php';
    // }
}