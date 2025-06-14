<?php
// Assuming you are passing the $categories array from the controller
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="assets/css/addproduct.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="form-container">
        <h2>Add New Product</h2>

        <form action="index.php?controller=Product&action=handleAddProduct" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add">

            <!-- Product Name -->
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" id="name" name="name" placeholder="Product Name" required>
            </div>

            <!-- Product Description -->
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Product Description" required></textarea>
            </div>

            <!-- Product Price -->
            <div class="form-group">
                <label for="price">Price ($)</label>
                <input type="number" id="price" name="price" placeholder="Product Price" step="0.01" required>
            </div>

            <!-- Product Quantity -->
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" id="quantity" name="quantity" placeholder="Product Quantity" required>
            </div>

            <!-- Product Discount -->
            <!-- Product Category -->
    <select name="category_id" required>
    <option value="">Select Category</option>
    <?php if (!empty($categories)): ?>
        <?php foreach ($categories as $category): ?>
            <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
        <?php endforeach; ?>
    <?php else: ?>
        <option disabled>No categories found</option>
    <?php endif; ?>
</select>


<div class="form-group">
     <label for="product_type_id">Product Type</label>
     <select id="product_type_id" name="product_type_id" required>
         <option value="">Select Product Type</option>
         <?php if (!empty($productTypes)): ?>
             <?php foreach ($productTypes as $productType): ?>
                 <option value="<?= htmlspecialchars($productType['id']) // Assuming array access ?>"><?= htmlspecialchars($productType['name']) ?></option>
             <?php endforeach; ?>
         <?php else: ?>
             <option disabled>No product types found</option>
         <?php endif; ?>
     </select>
 </div>

 <div class="form-group">
      <label for="on_sale">On Sale?</label>
      <select id="on_sale" name="on_sale">
          <option value="0">No</option>
          <option value="1">Yes</option>
      </select>
  </div>
            <!-- Product Images -->
            <h3>Product Images (URLs)</h3>
    <div id="image-fields">
        <input type="text" name="images[]" placeholder="Image URL" required>
    </div>
    <button type="button" onclick="addImageField()">+ Add Image</button>

    <h3>Product Colors</h3>
    <div id="color-fields">
        <input type="text" name="colors[]" placeholder="Color Name" required>
    </div>
    <button type="button" onclick="addColorField()">+ Add Color</button>

    <h3>Product Sizes</h3>
    <div id="size-fields">
        <input type="text" name="sizes[]" placeholder="Size Name" required>
    </div>
    <button type="button" onclick="addSizeField()">+ Add Size</button>

    <button type="submit">Add Product</button>
</form>

<script>
    function addImageField() {
        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'images[]';
        input.placeholder = 'Image URL';
        document.getElementById('image-fields').appendChild(input);
    }

    function addColorField() {
        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'colors[]';
        input.placeholder = 'Color Name';
        document.getElementById('color-fields').appendChild(input);
    }

    function addSizeField() {
        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'sizes[]';
        input.placeholder = 'Size Name';
        document.getElementById('size-fields').appendChild(input);
    }
</script>

</body>
</html>
