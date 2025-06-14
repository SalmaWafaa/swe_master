<!-- C:\xampp\htdocs\ecommerce_master\View\products\add_product_form.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
</head>
<body>
    <h1>Add Product</h1>
    <form action="index.php?controller=Product&action=createProduct" method="POST">
        <input type="hidden" name="category_id" value="<?php echo $_GET['category_id']; ?>">

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>

        <label for="product_type_id">Product Type ID:</label>
        <input type="number" id="product_type_id" name="product_type_id" required><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" required><br>

        <label for="on_sale">On Sale (%):</label>
        <input type="number" id="on_sale" name="on_sale" step="0.01" min="0" max="100" value="0.00"><br>

        <label for="rate">Rate:</label>
        <input type="number" id="rate" name="rate" step="0.01" min="0" max="5" value="0.00"><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" min="0" required><br>

        <button type="submit">Add Product</button>
    </form>
</body>
</html>