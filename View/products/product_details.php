<!DOCTYPE html>
<html>
<head>
    <title>Product Details</title>
</head>
<body>
    <h1>Product Details</h1>
    <?php if ($product): ?>
        <h2><?= $product['name'] ?></h2>
        <p>Description: <?= $product['description'] ?></p>
        <p>Price: $<?= $product['price'] ?></p>
        <p>Colors: <?= implode(", ", json_decode($product['colors'])) ?></p>
        <p>Sizes: <?= implode(", ", json_decode($product['sizes'])) ?></p>
        <p>Rating: <?= $product['rate'] ?> / 5</p>
        <button onclick="window.location.href='index.php?controller=Product&action=editProductForm&id=<?= $product['id'] ?>'">
            Edit
        </button>
        <button onclick="window.location.href='index.php?controller=Product&action=deleteProductForm&id=<?= $product['id'] ?>'">
            Delete
        </button>
    <?php else: ?>
        <p>Product not found.</p>
    <?php endif; ?>
</body>
</html>