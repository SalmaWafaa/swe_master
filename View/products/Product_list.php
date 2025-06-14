<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .product { margin-bottom: 20px; padding: 10px; border: 1px solid #ccc; }
        .product h3 { margin: 0; }
    </style>
</head>
<body>
    <h1>Products</h1>
    <button onclick="window.location.href='index.php?controller=Product&action=addProductForm'">
        Add Product
    </button>
    <br><br>
    <?php foreach ($products as $product): ?>
        <div class="product">
            <h3><?= $product['name'] ?></h3>
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
        </div>
    <?php endforeach; ?>
</body>
</html>