<?php
require_once __DIR__ . '/../Model/Products/AbstractProduct.php';
require_once __DIR__ . '/../Model/Products/ProductModel.php';
require_once __DIR__ . '/../Model/Products/ProductIterator.php';
require_once __DIR__ . '/../Model/Products/ProductFactory.php';
require_once __DIR__ . '/../Model/Category/CategoryComposite.php';

class ProductController {
    private ProductModel $productModel;
    private CategoryComposite $categoryModel;
    private ProductTypeModel $productTypeModel;
    private ConcreteProductFactory $productFactory;

    public function __construct() {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryComposite();
        $this->productTypeModel = new ProductTypeModel();
        $this->productFactory = new ConcreteProductFactory();
    }

    private function getProductIdFromRequest(): ?int {
        $id = $_GET['id'] ?? $_GET['product_id'] ?? null;
        return ($id !== null && filter_var($id, FILTER_VALIDATE_INT) && $id > 0) ? (int)$id : null;
    }

    private function redirect(string $location): void {
        header("Location: $location");
        exit();
    }

    public function viewProductDetails(): void {
        error_log("Starting viewProductDetails");
        $productId = $this->getProductIdFromRequest();
        
        if (!$productId) {
            error_log("Invalid product ID");
            $this->redirect('/swe_master/index.php?error=InvalidProductID');
        }

        try {
            $iterator = $this->productModel->getProductIterator(['id' => $productId]);
            $iterator->rewind();
            $product = $iterator->current();

            if ($product) {
                error_log("Product found: " . print_r($product, true));
                $images = $iterator->getRelatedData($productId, 'productimages');
                $sizes = $iterator->getRelatedData($productId, 'productsizes');
                $colors = $iterator->getRelatedData($productId, 'productcolors');
                
                error_log("Images: " . print_r($images, true));
                error_log("Sizes: " . print_r($sizes, true));
                error_log("Colors: " . print_r($colors, true));
                
                include __DIR__ . '/../View/products/product_details.php';
            } else {
                error_log("Product not found");
                $this->redirect('/swe_master/index.php?error=ProductNotFound');
            }
        } catch (\Exception $e) {
            error_log("Error in viewProductDetails: " . $e->getMessage());
            $this->redirect('/swe_master/index.php?error=ServerError');
        }
    }

    public function editProduct(): void {
        error_log("Starting editProduct");
        $productId = $this->getProductIdFromRequest();

        if (!$productId) {
            error_log("Invalid product ID");
            $this->redirect('/swe_master/index.php?error=InvalidProductID');
        }

        try {
            $iterator = $this->productModel->getProductIterator(['id' => $productId]);
            $iterator->rewind();
            $product = $iterator->current();
            
            if ($product) {
                error_log("Product found for editing: " . print_r($product, true));
                $categories = $this->categoryModel->getAllCategories();
                $productTypes = $this->productTypeModel->getAllProductTypes();
                $productImages = $iterator->getRelatedData($productId, 'productimages');
                $productColors = $iterator->getRelatedData($productId, 'productcolors');
                $productSizes = $iterator->getRelatedData($productId, 'productsizes');

                include __DIR__ . '/../View/products/edit_product_form.php';
            } else {
                error_log("Product not found for editing");
                $this->redirect('/swe_master/index.php?error=ProductNotFound');
            }
        } catch (\Exception $e) {
            error_log("Error in editProduct: " . $e->getMessage());
            $this->redirect('/swe_master/index.php?error=ServerError');
        }
    }

    public function updateProduct(): void {
        error_log("Starting updateProduct");
        error_log("POST data: " . print_r($_POST, true));

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            error_log("Invalid request method");
            $this->redirect('/swe_master/index.php?error=InvalidRequestMethod');
        }

        try {
            $productData = $this->validateAndSanitizeProductData($_POST);
            
            if (!empty($productData['errors'])) {
                error_log("Validation errors: " . print_r($productData['errors'], true));
                $_SESSION['form_errors'] = $productData['errors'];
                $_SESSION['old_input'] = $_POST;
                $this->redirect("/swe_master/index.php?controller=Product&action=editProduct&product_id=" . $productData['id']);
            }

            error_log("Attempting to update product with data: " . print_r($productData, true));
            $this->productModel->updateProduct($productData['id'], $productData);
            
            error_log("Product updated successfully");
            $this->redirect("/swe_master/index.php?controller=Product&action=viewProductDetails&id=" . $productData['id'] . "&message=ProductUpdatedSuccessfully");
        } catch (\Exception $e) {
            error_log("Error updating product: " . $e->getMessage());
            $_SESSION['error'] = "Failed to update product: " . $e->getMessage();
            $_SESSION['old_input'] = $_POST;
            $this->redirect("/swe_master/index.php?controller=Product&action=editProduct&product_id=" . ($_POST['product_id'] ?? ''));
        }
    }

    public function deleteProduct(): void {
        error_log("Starting deleteProduct");
        $productId = $this->getProductIdFromRequest();

        if (!$productId) {
            error_log("Invalid product ID");
            $this->redirect('/swe_master/index.php?error=InvalidProductID');
        }

        try {
            error_log("Attempting to delete product ID: $productId");
            $deleted = $this->productModel->deleteProduct($productId);

            if ($deleted) {
                error_log("Product deleted successfully");
                $this->redirect("/index.php?controller=Product&action=listProducts&message=ProductDeletedSuccessfully");
            } else {
                error_log("Product not found for deletion");
                $this->redirect('/swe_master/index.php?error=ProductNotFound');
            }
        } catch (\Exception $e) {
            error_log("Error deleting product: " . $e->getMessage());
            $this->redirect('/swe_master/index.php?error=DeleteFailed');
        }
    }

    public function showAddProductForm(): void {
        error_log("Starting showAddProductForm");
        try {
            $categories = $this->categoryModel->getAllCategories();
            $productTypes = $this->productTypeModel->getAllProductTypes();
            
            error_log("Categories: " . print_r($categories, true));
            error_log("Product Types: " . print_r($productTypes, true));
            
            include __DIR__ . '/../View/products/add_product_form.php';
        } catch (\Exception $e) {
            error_log("Error loading add product form: " . $e->getMessage());
            $this->redirect('/swe_master/index.php?error=FormLoadError');
        }
    }

    public function handleAddProduct(): void {
        error_log("Starting handleAddProduct");
        error_log("POST data: " . print_r($_POST, true));

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            error_log("Invalid request method");
            $this->redirect('/swe_master/index.php?error=InvalidRequestMethod');
        }

        try {
            $productData = $this->validateAndSanitizeProductData($_POST);
            
            if (!empty($productData['errors'])) {
                error_log("Validation errors: " . print_r($productData['errors'], true));
                $_SESSION['form_errors'] = $productData['errors'];
                $_SESSION['old_input'] = $_POST;
                $this->redirect("/swe_master/index.php?controller=Product&action=showAddProductForm");
            }

            error_log("Attempting to add product with data: " . print_r($productData, true));
            $productId = $this->productModel->addProduct($productData);
            
            error_log("Product added successfully with ID: $productId");
            $this->redirect("/swe_master/index.php?controller=Product&action=viewProductDetails&id=$productId&message=ProductAddedSuccessfully");
        } catch (\Exception $e) {
            error_log("Error adding product: " . $e->getMessage());
            $_SESSION['error'] = "Failed to add product: " . $e->getMessage();
            $_SESSION['old_input'] = $_POST;
            $this->redirect("/swe_master/index.php?controller=Product&action=showAddProductForm");
        }
    }

    private function validateAndSanitizeProductData(array $postData): array {
        $data = [
            'id' => filter_var($postData['product_id'] ?? 0, FILTER_VALIDATE_INT),
            'name' => trim($postData['name'] ?? ''),
            'description' => trim($postData['description'] ?? ''),
            'price' => filter_var($postData['price'] ?? 0, FILTER_VALIDATE_FLOAT),
            'category_id' => filter_var($postData['category_id'] ?? 0, FILTER_VALIDATE_INT),
            'product_type_id' => filter_var($postData['product_type_id'] ?? 0, FILTER_VALIDATE_INT),
            'on_sale' => isset($postData['on_sale']) ? 1 : 0,
            'quantity' => filter_var($postData['quantity'] ?? 0, FILTER_VALIDATE_INT),
            'sizes' => $this->processArrayInput($postData['sizes'] ?? ''),
            'colors' => $this->processArrayInput($postData['colors'] ?? ''),
            'images' => $this->processArrayInput($postData['images'] ?? ''),
            'errors' => []
        ];

        if (empty($data['name'])) $data['errors'][] = "Product name is required.";
        if (empty($data['description'])) $data['errors'][] = "Description is required.";
        if ($data['price'] === false || $data['price'] < 0) $data['errors'][] = "Valid price is required.";
        if ($data['category_id'] <= 0) $data['errors'][] = "Valid category is required.";
        if ($data['product_type_id'] <= 0) $data['errors'][] = "Valid product type is required.";
        if (empty($data['sizes'])) $data['errors'][] = "At least one size is required.";
        if (empty($data['colors'])) $data['errors'][] = "At least one color is required.";
        if (empty($data['images'])) $data['errors'][] = "At least one image URL is required.";

        return $data;
    }

    private function processArrayInput($input): array {
        if (is_array($input)) {
            return array_map('trim', array_filter($input));
        }
        return !empty($input) ? array_filter(array_map('trim', explode(',', $input))) : [];
    }
}