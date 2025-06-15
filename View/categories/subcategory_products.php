<?php
// Assume $isAdmin boolean variable is passed to this view from the controller
// e.g., in the controller action that loads this view:
// $userController = new UserController();
// $isAdmin = $userController->isAdmin();
// include 'path/to/subcategory_products.php'; // Pass $isAdmin implicitly or explicitly

// We also need $userController if other methods are needed, pass it too if necessary
// Or better, just pass the boolean flag $isAdmin
$userController = new UserController(); // Keep temporarily if needed elsewhere, but ideally remove
$isAdmin = $userController->isAdmin(); // Get the flag

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - <?php echo htmlspecialchars($subcategory->getName()); ?></title>
    <link rel="stylesheet" href="/swe_master/assets/css/base.css">
    <link rel="stylesheet" href="/swe_master/assets/css/subcategory_products.css">
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($subcategory->getName()); ?> Products</h1>

        <?php if (isset($_GET['message'])): ?>
            <p class="message"><?php echo htmlspecialchars($_GET['message']); ?></p>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <p class="error"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>

        <div class="product-grid">
            <?php if (isset($products) && !empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-box">
                        <div class="product-image">
                            <?php
                            $iterator = $productModel->getProductIterator(['id' => $product['id']]);
                            $images = $iterator->getRelatedData($product['id'], 'productimages');
                            if (!empty($images)) {
                                echo '<img src="' . htmlspecialchars($images[0]['image_url']) . '" alt="' . htmlspecialchars($product['name']) . '">';
                            } else {
                                echo '<div class="no-image">No Image Available</div>';
                            }
                            ?>
                        </div>

                        <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
                        <div class="product-price">$<?php echo number_format($product['price'], 2); ?></div>

                        <div class="product-actions">
                            <button onclick="window.location.href='index.php?controller=Product&action=viewProductDetails&id=<?php echo $product['id']; ?>'">
                                View Details
                            </button>
                        </div>

                        <?php if ($isAdmin): ?>
                            <div class="admin-actions">
                                <a href="index.php?controller=Product&action=editProduct&id=<?php echo $product['id']; ?>" class="edit-btn">Edit</a>
                                <a href="index.php?controller=Product&action=deleteProduct&id=<?php echo $product['id']; ?>" 
                                   class="delete-btn" 
                                   onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-products">
                    <div class="no-products-content">
                        <i class="fas fa-box-open"></i>
                        <h2>No Products Found</h2>
                        <p>We couldn't find any products in this category.</p>
                        <a href="/swe_master/index.php" class="continue-shopping">Browse Other Categories</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($isAdmin): ?>
            <div class="add-product-button">
                <button onclick="window.location.href='index.php?controller=Product&action=showAddProductForm'">
                    Add New Product
                </button>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>