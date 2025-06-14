<!-- C:\xampp\htdocs\ecommerce_master\View\categories\category_details.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Details</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

    <h1>Subcategories</h1>

    <div class="container">
        <a href="index.php?controller=Category&action=listCategories">
            <button class="view-button">Back to Main Categories</button>
        </a>

        <div class="subcategory-grid">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $subcategory): ?>
                    <div class="subcategory-box">
                        <h3><?php echo htmlspecialchars($subcategory['name']); ?></h3>
                        <div class="actions">
                            <a href="index.php?controller=Category&action=viewSubcategoryProducts&subcategory_id=<?php echo $subcategory['id']; ?>">
                                <button class="view-button">View Products</button>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No subcategories found.</p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
