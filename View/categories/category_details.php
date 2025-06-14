<!-- category_details.php - View -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Details</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333;
            padding: 20px;
            margin-top: 20px;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .view-button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .view-button:hover {
            background-color: #218838;
        }

        .subcategory-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .subcategory-box {
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .subcategory-box:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .subcategory-box h3 {
            font-size: 18px;
            margin-bottom: 15px;
            color: #333;
        }

        .subcategory-box .actions a {
            text-decoration: none;
        }

        .subcategory-box button {
            padding: 8px 15px;
            background-color: #007bff;
            color: white;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .subcategory-box button:hover {
            background-color: #0056b3;
        }

        .back-button-container {
            text-align: center;
            margin-bottom: 30px;
        }

    </style>
</head>
<body>

    <div class="container">
        <!-- Back Button -->
        <div class="back-button-container">
            <a href="index.php?controller=Category&action=listCategories">
                <button class="view-button">Back to Main Categories</button>
            </a>
        </div>

        <h1>Subcategories</h1>

        <!-- Display Subcategories -->
        <div class="subcategory-grid">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $subcategory): ?>
                    <div class="subcategory-box">
                        <h3><?php echo htmlspecialchars($subcategory['name']); ?></h3>
                        <div class="actions">
                        <?php if ($isAdmin): ?>
                        
                            <a href="index.php?controller=Category&action=editCategoryForm&id=<?php echo $mainCategory->getId(); ?>">
                                <button class="edit-button">Edit</button>
                            </a>
                            <a href="index.php?controller=Category&action=deleteCategoryForm&id=<?php echo $mainCategory->getId(); ?>">
                                <button class="delete-button">Delete</button>
                            </a>
                      
                    <?php endif; ?>
                            <a href="index.php?controller=Category&action=viewSubcategoryProducts&subcategory_id=<?php echo $subcategory['id']; ?>">
                                <button class="view-button">View Products</button>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No subcategories found.</p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
