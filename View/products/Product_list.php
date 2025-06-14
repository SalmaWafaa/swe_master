<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="assets/css/product.css">
</head>
<body>
    <div class="product-list-container">
        <h1>Product List</h1>
        
        <?php if (!empty($message)): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <div class="product-actions">
            <a href="index.php?controller=Product&action=showAddProductForm" class="btn">Add New Product</a>
        </div>
        
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <?php 
                    $iterator = $productModel->getProductIterator(['id' => $product['id']]);
                    $images = $iterator->getRelatedData($product['id'], 'productimages');
                    ?>
                    
                    <?php if (!empty($images)): ?>
                        <img src="<?= htmlspecialchars($images[0]['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    <?php endif; ?>
                    
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p>$<?= number_format($product['price'], 2) ?></p>
                    
                    <div class="product-actions">
                        <a href="index.php?controller=Product&action=viewProductDetails&id=<?= $product['id'] ?>" class="btn">View</a>
                        <a href="index.php?controller=Product&action=showEditProductForm&id=<?= $product['id'] ?>" class="btn">Edit</a>
                        <a href="index.php?controller=Product&action=handleDeleteProduct&id=<?= $product['id'] ?>" 
                           class="btn danger" 
                           onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>