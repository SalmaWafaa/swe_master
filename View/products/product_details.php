<!-- View/products/product_details.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Product Details</title>
    <link rel="stylesheet" href="assets/css/product.css">
</head>
<body>

<div class="product-container">
    <h1><?php echo htmlspecialchars($product['name']); ?></h1>
    <p class="description"><?php echo htmlspecialchars($product['description']); ?></p>
    <p class="price"><strong>Price:</strong> $<?php echo number_format($product['price'], 2); ?></p>

    <!-- Product Images -->
    <div class="product-images">
        <?php foreach ($images as $image): ?>
            <img src="<?php echo htmlspecialchars($image['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image" />
        <?php endforeach; ?>
    </div>

    <!-- Sizes and Colors -->
    <form action="Controller/Cart/AddtoCart.php" method="GET" class="product-form">
        <label for="size">Size:</label>
        <select id="size" name="size">
            <?php foreach ($sizes as $size): ?>
                <option value="<?php echo htmlspecialchars($size['size']); ?>"><?php echo htmlspecialchars($size['size']); ?></option>
            <?php endforeach; ?>
        </select>

        <label for="color">Color:</label>
        <select id="color" name="color">
            <?php foreach ($colors as $color): ?>
                <option value="<?php echo htmlspecialchars($color['color']); ?>"><?php echo htmlspecialchars($color['color']); ?></option>
            <?php endforeach; ?>
        </select>

        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
        <button type="submit">Add to Cart</button>
    </form>
</div>

<!-- Image Modal -->
<div id="imageModal" class="modal">
    <span class="close">&times;</span>
    <img class="modal-content" id="modalImage">
</div>

<script src="assets/js/product.js"></script>

</body>
</html>
