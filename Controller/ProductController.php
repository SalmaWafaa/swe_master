<?php
include_once __DIR__ . '/../Model/Products/ProductModel.php';
include_once __DIR__ . '/../Model/Category/CategoryComposite.php'; // Assuming this exists and works
include_once __DIR__ . '/UserController.php'; // Include UserController to check admin status

include_once __DIR__ . '/../Model/Products/ProductTypeModel.php';  // Include the ProductTypeModel class

class ProductController {

    // Helper to get product ID from request (handles 'id' or 'product_id')
    private function getProductIdFromRequest(): ?int
    {
        $id = $_GET['id'] ?? $_GET['product_id'] ?? null;
        if ($id !== null && filter_var($id, FILTER_VALIDATE_INT) && $id > 0) {
            return (int)$id;
        }
        return null;
    }

    // --- View Product Details ---
    public function viewProductDetails() {
        $productId = $this->getProductIdFromRequest();
        if (!$productId) {
            // Handle invalid/missing ID - redirect or show error
            header('Location: /ecommerce_master/index.php?error=InvalidProductID');
            exit();
        }
    
        try {
            $productModel = new ProductModel();
            $product = $productModel->getProductById($productId);
    
            if ($product) {
                $images = $productModel->getProductImages($productId);
                $sizes = $productModel->getProductSizes($productId);
                $colors = $productModel->getProductColors($productId);
    
                // Check if the data is correctly populated before including the view
                error_log("Product data: " . print_r($product, true));
                error_log("Product images: " . print_r($images, true));
                error_log("Product sizes: " . print_r($sizes, true));
                error_log("Product colors: " . print_r($colors, true));
    
                // Include the product details page
                include __DIR__ . '/../View/products/product_details.php';
            } else {
                header('Location: /ecommerce_master/index.php?error=ProductNotFound');
                exit();
            }
        } catch (\Exception $e) {
            error_log("Error in viewProductDetails (ID: $productId): " . $e->getMessage());
            header('Location: /ecommerce_master/index.php?error=ServerError');
            exit();
        }
    }
    
    public function showAddProductForm() {
        // Add authentication/authorization check here if needed
    
        try {
            // Fetch all categories
            $categoryModel = new CategoryComposite(); // Assuming this fetches categories correctly
            $categories = $categoryModel->getAllCategories(); // Get all categories from the database
    
            // Fetch all product types (new addition)
            $productTypeModel = new ProductTypeModel(); // Assuming you have a ProductTypeModel
            $productTypes = $productTypeModel->getAllProductTypes(); // Get all product types from the database
    
            // Check if categories and product types are available
            if (empty($categories)) {
                throw new Exception("No categories found in the database.");
            }
    
            if (empty($productTypes)) {
                throw new Exception("No product types found in the database.");
            }
    
            // Include the add product form view
            include __DIR__ . '/../View/products/add_product_form.php';
        } catch (Exception $e) {
            error_log("Error loading data for add product form: " . $e->getMessage());
            echo "Error loading form dependencies."; // Simple error display
        }
    }
    
    

    // --- Handle Add Product Submission (POST Request) ---
    public function handleAddProduct() {
        // Ensure the form was submitted via POST method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=Product&action=showAddProductForm');
            exit();
        }
    
        // --- Data Retrieval & Basic Validation ---
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
        $categoryId = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
        $productTypeId = filter_input(INPUT_POST, 'product_type_id', FILTER_VALIDATE_INT); // Make sure this is in your form
        $onSale = isset($_POST['on_sale']) ? filter_var($_POST['on_sale'], FILTER_VALIDATE_INT) : 0; // Make sure this is in your form
        
        // **** CORRECTED HANDLING FOR ARRAY INPUTS ****
        $images = isset($_POST['images']) && is_array($_POST['images'])
                    ? array_map('trim', array_filter($_POST['images'])) // Trim and remove empty entries
                    : [];
        $sizes = isset($_POST['sizes']) && is_array($_POST['sizes'])
                    ? array_map('trim', array_filter($_POST['sizes'])) // Trim and remove empty entries
                    : [];
        $colors = isset($_POST['colors']) && is_array($_POST['colors'])
                    ? array_map('trim', array_filter($_POST['colors'])) // Trim and remove empty entries
                    : [];
        // **********************************************
        
        // More robust validation
        $errors = [];
        if (empty($name)) $errors[] = "Product name is required.";
        if (empty($description)) $errors[] = "Description is required.";
        if ($price === false || $price < 0) $errors[] = "Valid price is required.";
        if ($categoryId === false || $categoryId <= 0) $errors[] = "Valid category is required.";
        if ($productTypeId === false || $productTypeId <= 0) $errors[] = "Valid product type is required."; // Add validation if required
        
        // Check for empty arrays if at least one is required
        if (empty($images)) $errors[] = "At least one image URL is required.";
        if (empty($colors)) $errors[] = "At least one color is required.";
        if (empty($sizes)) $errors[] = "At least one size is required.";
        
        
        // Check for any validation errors
        if (!empty($errors)) {
            // Build query string carefully
            $queryString = http_build_query(['error' => implode(', ', $errors)]);
            // It's often better to store errors in session flash messages
            header("Location: index.php?controller=Product&action=showAddProductForm&" . $queryString);
            exit();
        }
        
        // --- Call Model ---
        try {
            $productModel = new ProductModel();
            // Pass the correctly processed arrays to the model
            $productId = $productModel->addProduct($name, $description, $price, $categoryId, $images, $sizes, $colors, $productTypeId, $onSale);
            error_log("Product added successfully with ID: " . $productId);
            header("Location: index.php?message=ProductAddedSuccessfully"); // Or redirect to product page
            exit();
        } catch (\Exception $e) {
            error_log("Failed to add product: " . $e->getMessage());
            // Redirect back to form with a generic error
            header("Location: index.php?controller=Product&action=showAddProductForm&error=AddProductFailed");
            exit();
        }
    }
    
    public function editProduct(): void {
        // if (!isAdmin()) { header('Location: login.php'); exit(); }

        $productId = $this->getProductIdFromRequest();

        if ($productId === null) {
             // Redirect or show error if ID is invalid/missing
             header('Location: index.php?error=InvalidProductID');
             exit();
        }

        // Instantiate models
        $productModel = new ProductModel();
        $categoryModel = new CategoryComposite();
        $productTypeModel = new ProductTypeModel();

        // Fetch data
        $product = $productModel->getProductById($productId);

        // Check if product exists
        if (!$product) {
            error_log("Edit Product: Product not found with ID: " . $productId);
            header('Location: index.php?error=ProductNotFound');
            exit();
        }

        // Fetch related data *even if product fetch was successful*
        try {
            $categories = $categoryModel->getAllCategories();
            $productTypes = $productTypeModel->getAllProductTypes();
            $productImages = $productModel->getProductImages($productId);
            $productColors = $productModel->getProductColors($productId);
            $productSizes = $productModel->getProductSizes($productId);
        } catch (\Exception $e) {
             error_log("Error fetching related data for product ID {$productId}: " . $e->getMessage());
             // Decide how to handle: show form with partial data and error, or redirect?
             // For now, let's log and continue, the view will show empty dropdowns/lists
             $categories = [];
             $productTypes = [];
             $productImages = [];
             $productColors = [];
             $productSizes = [];
             // Optionally set an error message to display in the view
             $_GET['error'] = 'Could not load all product options. Please try again later.';
        }


        // Make data available to the view
        // Option 1: Extract (simpler in view, less explicit)
        // extract(compact('product', 'categories', 'productTypes', 'productImages', 'productColors', 'productSizes'));

        // Option 2: Pass as an array (more explicit)
        $viewData = [
            'product' => $product,
            'categories' => $categories,
            'productTypes' => $productTypes,
            'productImages' => $productImages,
            'productColors' => $productColors,
            'productSizes' => $productSizes,
            // Pass any messages from redirects too
            'message' => $_GET['message'] ?? null,
            'error' => $_GET['error'] ?? null
        ];


        // Load the view file (adjust path as needed)
        // If using extract: require 'views/products/editproduct.php';
        // If using $viewData:
        require 'View/products/edit_product_form.php'; // Pass $viewData to the scope
    }

    public function update(): void {
        // Authentication/Authorization check
        // if (!isAdmin()) { header('Location: login.php'); exit(); }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php'); // Redirect if not POST
            exit();
        }

        // Use the helper function to get ID reliably (might be in URL for RESTful or hidden field)
        // The form action URL should contain the product_id:
        // action="index.php?controller=Product&action=update&product_id=<?php echo $id; 
        $id = $this->getProductIdFromRequest();

        if (!$id) {
             // Can't proceed without an ID
             error_log("Update Product: Missing or invalid product ID in POST request.");
              // Redirect back, maybe to a general error page or product list
             header("Location: index.php?controller=Admin&action=listProducts&error=InvalidData");
             exit();
        }

        // --- Data Retrieval & Sanitization/Validation ---
        // Use filter_input for better security and validation
        $name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
        $description = trim(filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
        $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT); // Allows decimals
        $categoryId = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
        $productTypeId = filter_input(INPUT_POST, 'product_type_id', FILTER_VALIDATE_INT);
        // Use FILTER_VALIDATE_BOOLEAN for the on_sale checkbox/select
        $onSale = filter_input(INPUT_POST, 'on_sale', FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE); // Returns true, false, or null

        // Handle comma-separated inputs safely
        $imagesInput = trim($_POST['images'] ?? ''); // Still need raw post for comma separated
        $sizesInput = trim($_POST['sizes'] ?? '');
        $colorsInput = trim($_POST['colors'] ?? '');

        // Process comma-separated strings into arrays of trimmed, non-empty values
        $images = !empty($imagesInput) ? array_filter(array_map('trim', explode(',', $imagesInput))) : [];
        $sizes = !empty($sizesInput) ? array_filter(array_map('trim', explode(',', $sizesInput))) : [];
        $colors = !empty($colorsInput) ? array_filter(array_map('trim', explode(',', $colorsInput))) : [];

        // --- Validation ---
        $errors = [];
        if (empty($name)) $errors[] = "Product name is required.";
        if (empty($description)) $errors[] = "Product description is required."; // Added validation
        if ($price === false || $price < 0) $errors[] = "Invalid or negative price provided."; // Check for false from filter_input
        if ($categoryId === false || $categoryId <= 0) $errors[] = "Invalid category selected.";
        if ($productTypeId === false || $productTypeId <= 0) $errors[] = "Invalid product type selected.";
        if ($onSale === null) $errors[] = "Invalid value for 'On Sale'."; // Check for null from filter_input

        // Optional: Validate image URLs (basic example)
        foreach ($images as $imgUrl) {
            if (!filter_var($imgUrl, FILTER_VALIDATE_URL)) {
                 $errors[] = "Invalid image URL provided: " . htmlspecialchars($imgUrl);
            }
        }
        // Add more validation for sizes, colors if needed (e.g., allowed values)


        if (!empty($errors)) {
            // Redisplay form with errors, passing ID back
            $errorString = urlencode(implode('|', $errors)); // Use a different separator if comma is in errors
            error_log("Update product validation errors for ID $id: " . implode(', ', $errors));
             // Redirect back to the edit form action
            header("Location: index.php?controller=Product&action=editProduct&product_id={$id}&error=" . $errorString);
            exit();
        }

        // --- Call Model ---
        try {
            $productModel = new ProductModel();
            // Pass boolean true/false for onSale to the model if it expects boolean,
            // or let the model handle the 0/1 conversion as shown in the updated ProductModel.
            $productModel->updateProduct($id, $name, $description, $price, $categoryId, $images, $sizes, $colors, $productTypeId, $onSale); // Pass $onSale directly

            error_log("Product updated successfully for ID: " . $id);
            // Redirect to product details or list with success message
            header("Location: index.php?controller=Product&action=editProduct&product_id={$id}&message=ProductUpdatedSuccessfully"); // Redirect back to edit form with success
            // Or redirect to a product list:
            // header("Location: index.php?controller=Admin&action=listProducts&message=ProductUpdated");
            exit();
        } catch (\InvalidArgumentException $e) {
            error_log("Validation error during product update ID {$id}: " . $e->getMessage());
            header("Location: index.php?controller=Product&action=editProduct&product_id={$id}&error=" . urlencode($e->getMessage()));
            exit();
        } catch (\Exception $e) { // Catch broader exceptions from the model
            error_log("Failed to update product ID {$id}: " . $e->getMessage());
            // Redirect back to edit form with generic error
             header("Location: index.php?controller=Product&action=editProduct&product_id={$id}&error=" . urlencode("UpdateProductFailed: " . $e->getMessage()));
            exit();
        }
    }
    // --- Delete Product ---
    public function deleteProduct() {
         // Add authentication/authorization check here
        // e.g., if (!$this->isAdmin()) { /* redirect */ }

        $id = $this->getProductIdFromRequest(); // Use helper to get ID
        if (!$id) {
            header("Location: index.php?error=InvalidProductID");
            exit();
        }

        try {
            $productModel = new ProductModel();
            $productModel->deleteProduct($id); // Model now throws exceptions on failure

            error_log("Product deleted successfully: ID " . $id);
            // Redirect to a relevant page, e.g., product list or homepage
            header("Location: index.php?message=ProductDeletedSuccessfully");
            exit();

        } catch (\InvalidArgumentException $e) { // Catch specific validation error from model
             error_log("Invalid argument deleting product: " . $e->getMessage());
             header("Location: index.php?error=InvalidData");
             exit();
        } catch (\Exception $e) { // Catch DB or other errors from model
             error_log("Failed to delete product ID {$id}: " . $e->getMessage());
             // Redirect with a generic error message
             header("Location: index.php?error=DeleteProductFailed");
             exit();
        }
    }


}