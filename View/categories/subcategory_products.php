<!-- C:\xampp\htdocs\ecommerce_master\View\categories\subcategory_products.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subcategory Products</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

    <h1>Products</h1>

    <div class="container">
        <a href="index.php?controller=Category&action=listCategories">
            <button class="view-button">Back to Categories</button>
        </a>

        <div class="actions">
            <a href="index.php?controller=Product&action=addProductForm&category_id=<?php echo $_GET['subcategory_id']; ?>">
                <button class="add-button">Add Product</button>
            </a>
        </div>

        <div class="product-grid">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-box">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p><?php echo htmlspecialchars($product['description']); ?></p>
                        <p><strong>Price:</strong> $<?php echo number_format($product['price'], 2); ?></p>
                        <p><strong>Quantity:</strong> <?php echo $product['quantity']; ?></p>

                        <div class="actions">
                            <a href="index.php?controller=Product&action=editProductForm&id=<?php echo $product['id']; ?>">
                                <button class="edit-button">Edit</button>
                            </a>
                            <a href="index.php?controller=Product&action=deleteProduct&id=<?php echo $product['id']; ?>">
                                <button class="delete-button">Delete</button>
                            </a>
                            <!-- <a href="addtocart.php?product_id=<?php echo $product['id']; ?>" class="btn btn-secondary">Add to Cart</a> -->
                            <a href="cartorder/addtocart.php?product_id=<?= $product['id']; ?>" class="add-to-cart-link">Add to Cart</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No products found in this subcategory.</p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
