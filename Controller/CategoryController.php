<?php
// Ensure session is started consistently
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Model/Category/CategoryComposite.php';
require_once __DIR__ . '/../Model/products/ProductModel.php'; // If needed
require_once __DIR__ . '/UserController.php'; // Include UserController

class CategoryController {

    // Helper method to get user controller instance
    private function getUserController(): UserController {
         // In a real application, dependency injection might be better
         return new UserController();
    }

    // Helper method to check if the current user is an admin
    private function isAdmin(): bool {
        $userController = $this->getUserController();
        return $userController->isAdmin();
    }
    // Helper method to check if logged in (optional, can derive from isAdmin or use UserController directly)
    private function isLoggedIn(): bool {
         $userController = $this->getUserController();
        return $userController->isLoggedIn();
    }

    /**
     * List main categories and their subcategories.
     */
    public function listCategories() {
        $categoryModel = new CategoryComposite();
        $mainCategories = $categoryModel->getMainCategories();

        foreach ($mainCategories as $mainCategory) {
            $mainCategory->subcategories = $categoryModel->getSubcategories($mainCategory->getId());
        }

        // Get user status to pass to the view
        $isLoggedIn = $this->isLoggedIn(); // Check if any user is logged in
        $isAdmin = $this->isAdmin();       // Check if the logged-in user is an admin

        include __DIR__ . '/../View/categories/category_list.php'; // Pass $isLoggedIn and $isAdmin
    }

    /**
     * View products belonging to a specific subcategory.
     * (No admin check needed to view products usually)
     */
    public function viewSubcategoryProducts(int $subcategoryId) {
        $categoryModel = new CategoryComposite();
        $subcategory = $categoryModel->getSubcategoryById($subcategoryId);

        if ($subcategory) {
            $products = $subcategory->getProducts();
            $isLoggedIn = $this->isLoggedIn(); // Pass login status if view needs it
            $isAdmin = $this->isAdmin();       // Pass admin status if view needs it
            include __DIR__ . '/../View/categories/subcategory_products.php';
        } else {
            echo "Subcategory not found.";
            // Consider redirecting with error message
            // header("Location: index.php?controller=Category&action=listCategories&error=SubcategoryNotFound");
            // exit();
        }
    }

    // --- Admin-Only Actions ---

    /**
     * Display the form to add a new category. (Admin Only)
     */
    public function addCategoryForm() {
        // ** SECURITY CHECK **
        if (!$this->isAdmin()) {
             $this->redirectToLogin("Admin privileges required to add categories.");
        }

        $categoryModel = new CategoryComposite();
        $parentCategories = $categoryModel->getMainCategories();

        $isLoggedIn = true; // Must be logged in if admin
        $isAdmin = true;    // Must be admin to reach here
        include __DIR__ . '/../View/categories/add_category.php';
    }

    /**
     * Handle the creation of a new category. (Admin Only)
     */
    public function createCategory() {
         // ** SECURITY CHECK **
        if (!$this->isAdmin()) {
             $this->redirectToLogin("Admin privileges required to create categories.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category = new CategoryComposite();
            $category->name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $category->image = filter_input(INPUT_POST, 'image', FILTER_SANITIZE_URL);
            $parentIdInput = filter_input(INPUT_POST, 'parent_id', FILTER_VALIDATE_INT);
            $category->parent_id = ($parentIdInput && $parentIdInput > 0) ? $parentIdInput : null;

            if (empty($category->name)) {
                 // Handle error - maybe redirect back to form with message
                 header("Location: index.php?controller=Category&action=addCategoryForm&error=NameRequired");
                 exit();
            }

            if ($category->save()) {
                header("Location: index.php?controller=Category&action=listCategories&message=CategoryAdded");
                exit();
            } else {
                header("Location: index.php?controller=Category&action=addCategoryForm&error=CreationFailed");
                exit();
            }
        } else {
             header("Location: index.php?controller=Category&action=addCategoryForm");
             exit();
        }
    }

    /**
     * Display the form to edit an existing category. (Admin Only)
     */
    public function editCategoryForm(int $id) {
        // Fetch category by ID
        $categoryModel = new CategoryComposite();
        $categoryData = $categoryModel->getCategoryById($id);
    
        //var_dump($categoryData); // Debugging output
    
        if ($categoryData) {
            $allMainCategories = $categoryModel->getMainCategories();
            $parentCategories = array_filter($allMainCategories, function($cat) use ($id) {
                return $cat->getId() !== $id;
            });
    
            // Pass the data to the view
            include __DIR__ . '/../View/categories/edit_category.php'; // Pass categoryData to the view
        } else {
            // Redirect if category not found
            header("Location: index.php?controller=Category&action=listCategories&error=CategoryNotFound");
            exit();
        }
    }
    
    

    public function updateCategory(int $id) {
        // ** SECURITY CHECK ** 
        if (!$this->isAdmin()) {
            $this->redirectToLogin("Admin privileges required to update categories.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize and validate inputs
            $category = new CategoryComposite();
            $category->id = $id;
            $category->name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $category->image = filter_input(INPUT_POST, 'image', FILTER_SANITIZE_URL);
            $parentIdInput = filter_input(INPUT_POST, 'parent_id', FILTER_VALIDATE_INT);

            // Ensure that the parent_id is not the same as the current category id
            if ($parentIdInput === $id) {
                header("Location: index.php?controller=Category&action=editCategoryForm&id=" . $id . "&error=ParentSelf");
                exit();
            }

            $category->parent_id = ($parentIdInput && $parentIdInput > 0) ? $parentIdInput : null;

            if (empty($category->name)) {
                header("Location: index.php?controller=Category&action=editCategoryForm&id=" . $id . "&error=NameRequired");
                exit();
            }

            // Update category
            if ($category->update()) {
                // Redirect to the category list page after successful update
                header("Location: index.php?controller=Category&action=listCategories&message=CategoryUpdated");
                exit();
            } else {
                // If update failed, show error
                header("Location: index.php?controller=Category&action=editCategoryForm&id=" . $id . "&error=UpdateFailed");
                exit();
            }
        } else {
            // Redirect to form if method is not POST
            header("Location: index.php?controller=Category&action=editCategoryForm&id=" . $id);
            exit();
        }
    }
     /**
      * Display the delete category confirmation page. (Admin Only)
      */
    public function deleteCategoryForm(int $id) {
         // ** SECURITY CHECK **
        if (!$this->isAdmin()) {
             $this->redirectToLogin("Admin privileges required to delete categories.");
        }

        $categoryModel = new CategoryComposite();
        $categoryData = $categoryModel->getCategoryById($id);

        if ($categoryData) {
             $isLoggedIn = true; // Must be logged in if admin
             $isAdmin = true;    // Must be admin
            include __DIR__ . '/../View/categories/delete_category.php';
        } else {
             header("Location: index.php?controller=Category&action=listCategories&error=CategoryNotFound");
             exit();
        }
    }

    /**
     * Handle the deletion of a category. (Admin Only)
     */
    public function deleteCategory() {
        // ** SECURITY CHECK **: Make sure only admins can delete categories
        if (!$this->isAdmin()) {
            // Redirect to login or show a message
            header("Location: index.php?controller=User&action=loginForm");
            exit();
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get the category ID from the POST request
            $categoryId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    
            if ($categoryId) {
                // Create a new CategoryComposite instance
                $category = new CategoryComposite();
                $category->id = $categoryId;
    
                // Attempt to get the category by ID to ensure it exists
                $categoryData = $category->getCategoryById($categoryId);
    
                if ($categoryData) {
                    // Attempt to delete the category
                    if ($category->deleteById($categoryId)) {
                        header("Location: index.php?controller=Category&action=listCategories&message=CategoryDeleted");
                        exit();
                    } else {
                        // Handle any errors, such as failure to delete
                        header("Location: index.php?controller=Category&action=listCategories&error=DeleteFailed");
                        exit();
                    }
                } else {
                    // Category not found
                    header("Location: index.php?controller=Category&action=listCategories&error=CategoryNotFound");
                    exit();
                }
            } else {
                // Invalid category ID
                header("Location: index.php?controller=Category&action=listCategories&error=InvalidCategoryId");
                exit();
            }
        }
    }
    
    /**
     * Helper function to redirect non-admins to login page.
     */
     private function redirectToLogin(string $message = "Access Denied.") {
         // Store message in session flash data if needed
         $_SESSION['login_error'] = $message;
         header("Location: index.php?controller=User&action=loginForm");
         exit();
     }

} // End CategoryController Class
?>