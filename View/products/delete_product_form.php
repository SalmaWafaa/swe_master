<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Product</title>
    <style>
        <?php include 'style.css'; ?>
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Are you sure you want to delete this product?</h2>
        <form action="../controllers/ProductController.php" method="POST">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
            <p><strong><?= $product['name'] ?></strong></p>
            <button type="submit">Delete</button>
            <a href="productList.php" class="cancel-btn">Cancel</a>
        </form>
    </div>
</body>
</html>
