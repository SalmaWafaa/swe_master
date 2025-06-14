<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            text-align: center;
        }

        h1 {
            color: #333;
            margin: 20px 0;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: auto;
        }

        /* Category & Subcategory Grid */
        .category-grid, .subcategory-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            justify-content: center;
            padding: 20px;
        }

        .category-box, .subcategory-box {
            background: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            text-align: center;
        }

        .category-box:hover, .subcategory-box:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .category-box h2, .subcategory-box h3 {
            color: #333;
            margin: 0;
        }

        /* Buttons */
        .actions {
            margin-top: 10px;
        }

        .actions a {
            text-decoration: none;
        }

        button {
            padding: 8px 12px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
            transition: 0.3s;
        }

        .edit-button {
            background-color: #007bff;
            color: white;
        }

        .edit-button:hover {
            background-color: #0056b3;
        }

        .delete-button {
            background-color: #dc3545;
            color: white;
        }

        .delete-button:hover {
            background-color: #c82333;
        }

        .view-button {
            background-color: #28a745;
            color: white;
        }

        .view-button:hover {
            background-color: #218838;
        }

        .add-button {
            background-color: #17a2b8;
            color: white;
        }

        .add-button:hover {
            background-color: #138496;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            text-align: center;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background-color: #007bff;
            color: white;
        }

        .header h1 {
            margin: 0;
        }

        .auth-buttons {
            display: flex;
            gap: 10px;
        }

        .auth-buttons a {
            text-decoration: none;
        }

        .auth-buttons button {
            padding: 8px 12px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
            color: white;
        }

        .login-button {
            background-color: #28a745;
        }

        .login-button:hover {
            background-color: #218838;
        }

        .register-button {
            background-color: #ffc107;
            color: black;
        }

        .register-button:hover {
            background-color: #e0a800;
        }
    </style>
</head>
<body>
<div class="header">
    <h1>HomePage</h1>
    <div class="auth-buttons">
        <a href="http://localhost/ecommerce_master/View/login.php
">
            <button class="login-button">Login</button>
        </a>
        <a href="http://localhost/ecommerce_master/View/register.php"
>
            <button class="register-button">Register</button>
        </a>
    </div>
</div>

    <div class="container">
        <h1>Categories</h1>

        <!-- Add Category Button -->
        <a href="index.php?controller=Category&action=addCategoryForm">
            <button class="add-button">Add Category</button>
        </a>

        <!-- Main Categories and Subcategories -->
        <div class="category-grid">
            <?php
            // Remove duplicate categories based on name
            $uniqueCategories = [];
            foreach ($mainCategories as $category) {
                $name = $category['name'];
                if (!isset($uniqueCategories[$name])) {
                    $uniqueCategories[$name] = $category;
                }
            }
            $mainCategories = array_values($uniqueCategories);
            ?>

            <?php if (!empty($mainCategories)): ?>
                <?php foreach ($mainCategories as $mainCategory): ?>
                    <!-- Main Category Box -->
                    <div class="category-box">
                        <h2><?php echo htmlspecialchars($mainCategory['name']); ?></h2>

                        <!-- Actions for Main Category -->
                        <div class="actions">
                            <a href="index.php?controller=Category&action=editCategoryForm&id=<?php echo $mainCategory['id']; ?>">
                                <button class="edit-button">Edit</button>
                            </a>
                            <a href="index.php?controller=Category&action=deleteCategoryForm&id=<?php echo $mainCategory['id']; ?>">
                                <button class="delete-button">Delete</button>
                            </a>
                        </div>

                        <!-- Subcategories Section -->
                        <h3>Subcategories</h3>
                        <div class="subcategory-grid">
                            <?php if (!empty($mainCategory['subcategories'])): ?>
                                <?php foreach ($mainCategory['subcategories'] as $subcategory): ?>
                                    <!-- Subcategory Box -->
                                    <div class="subcategory-box">
                                        <h4><?php echo htmlspecialchars($subcategory['name']); ?></h4>
                                        <!-- Actions for Subcategory -->
                                        <div class="actions">
                                            <a href="index.php?controller=Category&action=editCategoryForm&id=<?php echo $subcategory['id']; ?>">
                                                <button class="edit-button">Edit</button>
                                            </a>
                                            <a href="index.php?controller=Category&action=deleteCategoryForm&id=<?php echo $subcategory['id']; ?>">
                                                <button class="delete-button">Delete</button>
                                            </a>
                                            <a href="index.php?controller=Category&action=viewSubcategoryProducts&subcategory_id=<?php echo $subcategory['id']; ?>">
                                                <button class="view-button">View Products</button>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <!-- No Subcategories Found -->
                                <p>No subcategories found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- No Categories Found -->
                <p>No categories found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>