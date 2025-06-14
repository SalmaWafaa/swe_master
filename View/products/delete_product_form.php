<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Product</title>
    <link rel="stylesheet" href="assets/css/product.css"> <!-- Adjust path as needed -->
</head>
<body>
    <div class="form-container">
        <h2>Are you sure you want to delete this product?</h2>

        <!-- Form for deleting the product -->
        <form action="index.php" method="GET">
            <input type="hidden" name="controller" value="Product">
            <input type="hidden" name="action" value="deleteProduct">
            <input type="hidden" name="id" value="<?= htmlspecialchars($product['id'] ?? '') ?>">

            <!-- Show product name for confirmation -->
            <p><strong><?= htmlspecialchars($product['name'] ?? 'Unknown Product') ?></strong></p>

            <!-- Submit button to confirm deletion -->
            <button type="submit">Delete</button>
            
            <!-- Link to cancel and go back to the product list -->
            <a href="index.php?controller=Product&action=listProducts" class="cancel-btn">Cancel</a>
        </form>
    </div>
</body>
</html>
