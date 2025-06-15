<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- View/products/product_details.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Product Details</title>
    <link rel="stylesheet" href="/swe_master/assets/css/base.css">
    <link rel="stylesheet" href="/swe_master/assets/css/product_details.css">
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

    <!-- Size Converter -->
    <div class="size-selector">
        <h3>Size Converter</h3>
        <form action="/swe_master/index.php?controller=SizeConversion&action=convertSize" method="POST" class="size-conversion-form">
            <input type="hidden" name="categoryId" value="<?php echo $product['category_id']; ?>">
            <input type="hidden" name="return_url" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
            
            <div class="size-input-group">
                <input type="text" name="size" class="size-input" placeholder="Enter size" required>
                
                <select name="targetSystem" class="system-select" required>
                    <option value="">Select target system</option>
                    <?php
                    // Get available size systems
                    require_once __DIR__ . '/../../config/dbConnectionSingelton.php';
                    $db = Database::getInstance()->getConnection();
                    $stmt = $db->query("SELECT id, name FROM size_systems ORDER BY name");
                    $systems = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach ($systems as $system) {
                        echo '<option value="' . htmlspecialchars($system['name']) . '">' . 
                             htmlspecialchars($system['name']) . '</option>';
                    }
                    ?>
                </select>
                
                <button type="submit" class="convert-button">Convert</button>
            </div>
        </form>

        <?php if (isset($_SESSION['conversion_result'])): ?>
            <div class="conversion-result <?php echo $_SESSION['conversion_success'] ? 'success' : 'error'; ?>">
                <?php 
                echo htmlspecialchars($_SESSION['conversion_result']);
                unset($_SESSION['conversion_result']);
                unset($_SESSION['conversion_success']);
                ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Product Options and Add to Cart -->
    <div class="product-options">
        <form action="/swe_master/Controller/Cart/AddtoCart.php" method="POST" class="product-form">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            
            <div class="option-group">
                <label for="size">Size:</label>
                <select id="size" name="size" required>
                    <option value="">Select Size</option>
                    <?php foreach ($sizes as $size): ?>
                        <option value="<?php echo htmlspecialchars($size['size']); ?>"><?php echo htmlspecialchars($size['size']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="option-group">
                <label for="color">Color:</label>
                <select id="color" name="color" required>
                    <option value="">Select Color</option>
                    <?php foreach ($colors as $color): ?>
                        <option value="<?php echo htmlspecialchars($color['color']); ?>"><?php echo htmlspecialchars($color['color']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="option-group">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" value="1" min="1" required>
            </div>

            <button type="submit" class="add-cart">Add to Cart</button>
        </form>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="modal">
    <span class="close">&times;</span>
    <img class="modal-content" id="modalImage">
</div>

<script src="/swe_master/assets/js/product.js"></script>
</body>
</html>
