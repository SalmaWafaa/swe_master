<!-- C:\xampp\htdocs\ecommerce_master\View\products\delete_product_form.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Product</title>
</head>
<body>
    <h1>Delete Product</h1>
    <p>Are you sure you want to delete the product "<?php echo $product->name; ?>"?</p>
<form action="index.php?controller=Product&action=deleteProduct&id=<?php echo $product->id; ?>" method="POST">
    <button type="submit">Yes, Delete</button>
    <a href="index.php?controller=Category&action=viewSubcategoryProducts&category_id=<?php echo $product->category_id; ?>">Cancel</a>
</form>

</body>
</html>