<?php
// Helper function for selecting dropdown options
function selected($value1, $value2): string {
    // Use loose comparison (==) for flexibility (e.g., string '1' vs int 1)
    // but strict comparison (===) might be safer if types are guaranteed.
    return ($value1 == $value2) ? 'selected' : '';
}

// --- Data passed from controller should be in $viewData array ---
// Extract for ease of use in the view (or access as $viewData['product'], etc.)
if (isset($viewData) && is_array($viewData)) {
    extract($viewData);
}

// Set default empty values if variables weren't extracted or are missing
$product = $product ?? null;
$categories = $categories ?? [];
$productTypes = $productTypes ?? [];
$productImages = $productImages ?? [];
$productColors = $productColors ?? [];
$productSizes = $productSizes ?? [];
$message = $message ?? ($_GET['message'] ?? null); // Also check $_GET as fallback
$error = $error ?? ($_GET['error'] ?? null);       // Also check $_GET as fallback

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - <?php echo isset($product['name']) ? htmlspecialchars($product['name']) : 'N/A'; ?></title>
    <style>
        /* Basic CSS (keep your existing styles or use linked CSS) */
        body { font-family: Arial, sans-serif; margin: 20px; padding: 0; background-color: #f4f4f4; }
        .form-container { background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 15px rgba(0, 0, 0, 0.1); max-width: 700px; margin: 20px auto; }
        h1 { text-align: center; color: #333; margin-bottom: 25px; }
        .form-group { margin-bottom: 20px; } /* Added spacing */
        label { display: block; font-weight: bold; margin-bottom: 8px; color: #555; } /* Increased margin */
        input[type="text"], input[type="number"], input[type="url"], textarea, select {
             width: 100%;
             padding: 12px; /* Increased padding */
             margin-bottom: 5px; /* Reduced direct margin */
             border: 1px solid #ccc;
             border-radius: 4px;
             box-sizing: border-box; /* Important */
             font-size: 1em; /* Ensure consistent font size */
         }
        textarea { min-height: 100px; resize: vertical; } /* Use min-height */
        button[type="submit"] { background-color: #28a745; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; width: 100%; margin-top: 15px; /* Added top margin */ }
        button[type="submit"]:hover { background-color: #218838; }
        .message, .error { padding: 12px 15px; margin-bottom: 20px; border-radius: 4px; text-align: left; font-size: 0.95em;} /* Align left */
        .message { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; white-space: pre-wrap; } /* Allow wrapping */
        .current-items { margin-top: 5px; margin-bottom: 10px; font-size: 0.9em; color: #666; padding-left: 5px; line-height: 1.6; } /* Add line-height */
        .current-items strong { color: #333; }
        .current-items span.item-pill { display: inline-block; background-color: #e9ecef; padding: 4px 8px; margin: 3px 4px 3px 0; border-radius: 10px; font-size: 0.95em; } /* Pill style */
        .current-items img.item-thumb { width: 45px; height: 45px; object-fit: cover; margin: 3px 4px 3px 0; vertical-align: middle; border-radius: 4px; border: 1px solid #eee; }
        small { display: block; margin-top: 3px; margin-bottom: 10px; font-size: 0.85em; color: #777; } /* Style for helper text */
    </style>
</head>
<body>

<div class="form-container">
    <h1>Edit Product</h1>

    <?php if ($message): ?>
        <p class="message"><?php echo htmlspecialchars(urldecode($message)); // Decode if needed ?></p>
    <?php endif; ?>
    <?php if ($error): ?>
        <?php
        // Handle pipe-separated errors from validation redirect
        $errorMessages = explode('|', urldecode($error));
        ?>
        <div class="error">
            <strong>Please fix the following issues:</strong><br>
            <?php foreach ($errorMessages as $errMsg): ?>
                 - <?php echo htmlspecialchars(trim($errMsg)); ?><br>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php // Check if product data is loaded before showing the form ?>
    <?php if (isset($product) && is_array($product) && !empty($product)): ?>
        <?php $productId = htmlspecialchars($product['id']); ?>
        <form action="index.php?controller=Product&action=update&product_id=<?php echo $productId; ?>" method="POST">
            <?php // Optional: Include product ID as a hidden field if needed, though it's in the action URL
                  // <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
            ?>

            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Product Description</label>
                <textarea id="description" name="description" required><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
            </div>

            <div class="form-group">
                <label for="price">Price ($)</label>
                <input type="number" id="price" step="0.01" min="0" name="price" value="<?php echo htmlspecialchars($product['price'] ?? '0.00'); ?>" required>
            </div>

            <div class="form-group">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id" required>
                    <option value="">-- Select Category --</option>
                    <?php if (isset($categories) && is_array($categories) && !empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <?php
                                $catId = $category['id'] ?? null;
                                $catName = $category['name'] ?? 'Unnamed Category';
                            ?>
                            <?php if ($catId !== null): ?>
                                <option value="<?php echo htmlspecialchars($catId); ?>"
                                        <?php echo selected($catId, $product['category_id'] ?? null); ?>
                                >
                                    <?php echo htmlspecialchars($catName); ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="" disabled>No categories available.</option>
                         <?php error_log("Edit Product View: No categories found or passed to view for product ID: " . $productId); ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="product_type_id">Product Type</label>
                <select id="product_type_id" name="product_type_id" required>
                    <option value="">-- Select Product Type --</option>
                     <?php if (isset($productTypes) && is_array($productTypes) && !empty($productTypes)): ?>
                         <?php foreach ($productTypes as $productType): ?>
                             <?php
                                 $typeId = $productType['id'] ?? null;
                                 $typeName = $productType['name'] ?? 'Unnamed Type';
                             ?>
                             <?php if ($typeId !== null): ?>
                                 <option value="<?php echo htmlspecialchars($typeId); ?>"
                                         <?php echo selected($typeId, $product['product_type_id'] ?? null); ?>
                                 >
                                     <?php echo htmlspecialchars($typeName); ?>
                                 </option>
                             <?php endif; ?>
                         <?php endforeach; ?>
                     <?php else: ?>
                         <option value="" disabled>No product types available.</option>
                         <?php error_log("Edit Product View: No product types found or passed to view for product ID: " . $productId); ?>
                     <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="images">Product Images (Enter URLs, comma-separated)</label>
                <div class="current-items">
                    <strong>Current:</strong>
                    <?php if (!empty($productImages)): ?>
                        <?php foreach ($productImages as $image): ?>
                             <?php // Expecting $image to be ['image_url' => '...']
                                 $imageUrl = $image['image_url'] ?? null;
                             ?>
                             <?php if ($imageUrl): ?>
                                <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="Image Thumbnail" class="item-thumb">
                             <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class="item-pill">None</span>
                    <?php endif; ?>
                </div>
                <?php
                    // Prepare image URLs for input field from the fetched data
                    $imageUrls = [];
                    if (is_array($productImages)) {
                         foreach ($productImages as $img) {
                             if (isset($img['image_url']) && !empty(trim($img['image_url']))) {
                                 $imageUrls[] = trim($img['image_url']);
                             }
                         }
                    }
                    $imageInputString = htmlspecialchars(implode(', ', $imageUrls));
                ?>
                <input type="text" id="images" name="images" placeholder="e.g., https://example.com/img1.jpg, https://example.com/img2.png" value="<?php echo $imageInputString; ?>">
                <small>Enter all desired image URLs separated by commas. Existing images will be replaced by this list.</small>
            </div>


            <div class="form-group">
                <label for="colors">Product Colors (Enter names, comma-separated)</label>
                <div class="current-items">
                     <strong>Current:</strong>
                    <?php if (!empty($productColors)): ?>
                        <?php foreach ($productColors as $color): ?>
                             <?php // Expecting $color to be ['color' => '...']
                                $colorName = $color['color'] ?? null;
                             ?>
                            <?php if ($colorName): ?>
                                <span class="item-pill"><?php echo htmlspecialchars($colorName); ?></span>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                         <span class="item-pill">None</span>
                    <?php endif; ?>
                </div>
                <?php
                    $colorNames = [];
                    if (is_array($productColors)) {
                         foreach ($productColors as $c) {
                             if (isset($c['color']) && !empty(trim($c['color']))) {
                                $colorNames[] = trim($c['color']);
                             }
                         }
                    }
                    $colorInputString = htmlspecialchars(implode(', ', $colorNames));
                ?>
                <input type="text" id="colors" name="colors" placeholder="e.g., Red, Blue, Green" value="<?php echo $colorInputString; ?>">
                <small>Enter all desired colors separated by commas. Existing colors will be replaced by this list.</small>
            </div>

            <div class="form-group">
                <label for="sizes">Product Sizes (Enter names, comma-separated)</label>
                 <div class="current-items">
                     <strong>Current:</strong>
                    <?php if (!empty($productSizes)): ?>
                        <?php foreach ($productSizes as $size): ?>
                            <?php // Expecting $size to be ['size' => '...']
                                $sizeName = $size['size'] ?? null;
                            ?>
                            <?php if ($sizeName): ?>
                                <span class="item-pill"><?php echo htmlspecialchars($sizeName); ?></span>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class="item-pill">None</span>
                    <?php endif; ?>
                </div>
                <?php
                    $sizeNames = [];
                     if (is_array($productSizes)) {
                        foreach ($productSizes as $s) {
                           if (isset($s['size']) && !empty(trim($s['size']))) {
                               $sizeNames[] = trim($s['size']);
                           }
                        }
                    }
                    $sizeInputString = htmlspecialchars(implode(', ', $sizeNames));
                ?>
                <input type="text" id="sizes" name="sizes" placeholder="e.g., S, M, L, XL" value="<?php echo $sizeInputString; ?>">
                <small>Enter all desired sizes separated by commas. Existing sizes will be replaced by this list.</small>
            </div>

            <div class="form-group">
                <label for="on_sale">On Sale</label>
                <select id="on_sale" name="on_sale">
                     <?php // Use boolean check (true/false) or integer check (1/0) based on what $product['on_sale'] contains
                           // Assuming it contains 1 for true, 0 for false from DB
                           $isOnSale = !empty($product['on_sale']); // Treat non-zero/non-empty as true
                     ?>
                    <option value="1" <?php echo selected(true, $isOnSale); ?>>Yes</option>
                    <option value="0" <?php echo selected(false, $isOnSale); ?>>No</option>
                </select>
            </div>

            <button type="submit">Update Product</button>
        </form>
    <?php else: ?>
        <?php // This part shows if $product data failed to load in the controller ?>
        <p class="error">Product data could not be loaded. It might not exist or there was an error.</p>
        <?php error_log("Edit Product View: \$product variable was not set or empty when rendering form."); ?>
        <p><a href="index.php?controller=Admin&action=listProducts">Back to Product List</a></p> <?php // Link to admin list ?>
    <?php endif; ?>

</div>

</body>
</html>