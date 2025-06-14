<!-- C:\xampp\htdocs\ecommerce_master\View\products\edit_product_form.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
</head>
<body>
    <h1>Edit Product</h1>
    <form action="index.php?controller=Product&action=updateProduct&id=<?php echo $product['id']; ?>" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $product['name']; ?>" required><br>

        <label for="category_id">Category ID:</label>
        <input type="number" id="category_id" name="category_id" value="<?php echo $product['category_id']; ?>" required><br>

        <label for="product_type_id">Product Type ID:</label>
        <input type="number" id="product_type_id" name="product_type_id" value="<?php echo $product['product_type_id']; ?>" required><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo $product['description']; ?></textarea><br>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" value="<?php echo $product['price']; ?>" required><br>

        <label for="on_sale">On Sale (%):</label>
        <input type="number" id="on_sale" name="on_sale" step="0.01" min="0" max="100" value="<?php echo $product['on_sale']; ?>"><br>

        <label for="rate">Rate:</label>
        <input type="number" id="rate" name="rate" step="0.01" min="0" max="5" value="<?php echo $product['rate']; ?>"><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" min="0" value="<?php echo $product['quantity']; ?>" required><br>

        <button type="submit">Update Product</button>
    </form>
</body>
</html>