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
    <title>Products in <?php echo isset($subcategory) ? htmlspecialchars($subcategory->getName()) : 'Subcategory'; ?></title>
    <style>
        /* Product Grid Layout (keep your existing styles) */
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { width: 80%; max-width: 1200px; margin: auto; padding: 30px 0; }
        h1 { color: #333; margin-bottom: 20px; }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; padding: 20px; }
        .product-box { background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); transition: transform 0.3s, box-shadow 0.3s; text-align: center; /* Center content */ }
        .product-box:hover { transform: scale(1.05); box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15); }
        .product-image { max-width: 100%; height: 150px; /* Fixed height example */ object-fit: contain; /* Or cover */ border-radius: 4px; margin-bottom: 10px; }
        .product-box h3 { color: #333; font-size: 18px; margin: 10px 0 5px 0; height: 40px; overflow: hidden; } /* Limit title height */
        .product-box p { color: #555; font-size: 14px; margin: 5px 0; }
        .product-box p.price { font-weight: bold; color: #007bff; font-size: 16px; margin-bottom: 15px; }
        .product-actions a, .admin-actions a { text-decoration: none; margin: 5px; display: inline-block; }
        .product-actions button, .admin-actions button { background-color: #007bff; color: white; padding: 8px 15px; font-size: 14px; border: none; border-radius: 5px; cursor: pointer; transition: background-color 0.2s; }
        .product-actions button.add-cart { background-color: #28a745; }
        .admin-actions button.edit { background-color: #ffc107; color: #333; }
        .admin-actions button.delete { background-color: #dc3545; }
        .product-actions button:hover, .admin-actions button:hover { opacity: 0.9; }
        .admin-actions { margin-top: 10px; }
        .add-product-button { margin-top: 30px; text-align: center; }
         .add-product-button button { /* Style for Add Product button */
             background-color: #17a2b8; color: white; padding: 12px 25px; font-size: 16px; border: none; border-radius: 5px; cursor: pointer; }
         .add-product-button button:hover { background-color: #138496; }
         .message, .error { padding: 10px; margin-bottom: 15px; border-radius: 4px; text-align: center; }
         .message { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
         .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Products in <?php echo isset($subcategory) ? htmlspecialchars($subcategory->getName()) : 'Products'; ?></h1>

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
                        <?php if (!empty($product['image_url'])): ?>
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                        <?php else: ?>
                             <img src="/path/to/placeholder-image.png" alt="No Image Available" class="product-image"> <?php endif; ?>

                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="price">Price: $<?php echo number_format($product['price'], 2); ?></p>

                        <div class="product-actions">
                             <a href="index.php?controller=Product&action=viewProductDetails&product_id=<?php echo $product['id']; ?>">
                                <button>View Details</button>
                            </a>
                             <a href="Controller/Cart/AddtoCart.php?product_id=<?php echo $product['id']; ?>">
                                <button class="add-cart">Add to Cart</button>
                            </a>
                        </div>

                        <?php if ($isAdmin): ?>
                            <div class="admin-actions">
                                <a href="index.php?controller=Product&action=editProduct&product_id=<?php echo $product['id']; ?>">
                                    <button class="edit">Edit</button>
                                </a>
                                <a href="index.php?controller=Product&action=deleteProduct&product_id=<?php echo $product['id']; ?>" onclick="return confirm('Are you sure you want to delete this product? This cannot be undone.');">
                                    <button class="delete">Delete</button>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No products found in this subcategory.</p>
            <?php endif; ?>
        </div>

         <?php if ($isAdmin): ?>
             <div class="add-product-button">
                 <a href="index.php?controller=Product&action=showAddProductForm&subcategory_id=<?php echo isset($subcategory) ? $subcategory->getId() : ''; ?>">
                     <button>Add New Product</button>
                 </a>
             </div>
         <?php endif; ?>

         </div> </body>
</html>