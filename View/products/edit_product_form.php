<?php
// Helper function for selecting dropdown options
function selected($value1, $value2): string {
    return ($value1 == $value2) ? 'selected' : '';
}

// Helper function to convert an array of associative arrays to a comma-separated string
function getInputString(array $items, string $key): string {
    $values = [];
    foreach ($items as $item) {
        if (isset($item[$key])) {
            $values[] = $item[$key];
        }
    }
    return implode(', ', $values);
}

// Helper function to render pills for colors and sizes
function renderPills(array $items, string $key): string {
    $html = '';
    foreach ($items as $item) {
        if (isset($item[$key])) {
            $html .= '<span class="pill">' . htmlspecialchars($item[$key]) . '</span> ';
        }
    }
    return $html;
}

// --- Data passed from controller should be in $viewData array ---
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
    <title>Edit Product - <?= htmlspecialchars($product['name'] ?? 'N/A') ?></title>
    <link rel="stylesheet" href="assets/css/product.css"> <!-- Link to your CSS -->
</head>
<body>
    <div class="form-container">
        <h1>Edit Product</h1>

        <?php if ($message): ?>
            <p class="message"><?= htmlspecialchars(urldecode($message)) ?></p>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error">
                <strong>Please fix the following issues:</strong><br>
                <?php foreach (explode('|', urldecode($error)) as $errMsg): ?>
                    - <?= htmlspecialchars(trim($errMsg)) ?><br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($product)): ?>
            <form action="index.php?controller=Product&action=updateProduct" method="POST">
                <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">

                <!-- Product Name -->
                <div class="form-group">
                    <label for="name">Product Name</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($product['name'] ?? '') ?>" required>
                </div>

                <!-- Product Description -->
                <div class="form-group">
                    <label for="description">Product Description</label>
                    <textarea id="description" name="description" required><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                </div>

                <!-- Price -->
                <div class="form-group">
                    <label for="price">Price ($)</label>
                    <input type="number" id="price" step="0.01" min="0" name="price" value="<?= htmlspecialchars($product['price'] ?? '0.00') ?>" required>
                </div>

                <!-- Category -->
                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">-- Select Category --</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category['id']) ?>" <?= selected($category['id'], $product['category_id']) ?>>
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Product Type -->
                <div class="form-group">
                    <label for="product_type_id">Product Type</label>
                    <select id="product_type_id" name="product_type_id" required>
                        <option value="">-- Select Product Type --</option>
                        <?php foreach ($productTypes as $type): ?>
                            <option value="<?= htmlspecialchars($type['id']) ?>" <?= selected($type['id'], $product['product_type_id']) ?>>
                                <?= htmlspecialchars($type['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Product Images -->
                <div class="form-group">
                    <label for="images">Product Images (comma-separated URLs)</label>
                    <div class="current-items"><strong>Current:</strong>
                        <?php foreach ($productImages as $image): ?>
                            <img src="<?= htmlspecialchars($image['image_url']) ?>" class="item-thumb">
                        <?php endforeach; ?>
                    </div>
                    <input type="text" id="images" name="images" value="<?= getInputString($productImages, 'image_url') ?>" placeholder="e.g., https://example.com/img1.jpg">
                    <small>Enter image URLs separated by commas. Existing images will be replaced.</small>
                </div>

                <!-- Product Colors -->
                <div class="form-group">
                    <label for="colors">Product Colors</label>
                    <div class="current-items"><strong>Current:</strong><?= renderPills($productColors, 'color') ?></div>
                    <input type="text" id="colors" name="colors" value="<?= getInputString($productColors, 'color') ?>" placeholder="e.g., Red, Blue, Green">
                </div>

                <!-- Product Sizes -->
                <div class="form-group">
                    <label for="sizes">Product Sizes</label>
                    <div class="current-items"><strong>Current:</strong><?= renderPills($productSizes, 'size') ?></div>
                    <input type="text" id="sizes" name="sizes" value="<?= getInputString($productSizes, 'size') ?>" placeholder="e.g., S, M, L, XL">
                </div>

                <!-- Product On Sale -->
                <div class="form-group">
                    <label for="on_sale">On Sale</label>
                    <select id="on_sale" name="on_sale">
                        <option value="1" <?= selected(true, $product['on_sale']) ?>>Yes</option>
                        <option value="0" <?= selected(false, $product['on_sale']) ?>>No</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <button type="submit">Update Product</button>
            </form>
        <?php else: ?>
            <p class="error">Product data could not be loaded.</p>
            <p><a href="index.php?controller=Product&action=listProducts">Back to Product List</a></p>
        <?php endif; ?>
    </div>
</body>
</html>
