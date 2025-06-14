<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <link rel="stylesheet" href="assets/css/categories.css">
</head>
<body>
<div class="header">
    <h1>HomePage</h1>
    <div class="auth-buttons">
        <?php if ($isLoggedIn): ?>
            <span class="welcome-message">Welcome, <?php echo htmlspecialchars($_SESSION['first_name'] ?? 'User'); ?>!</span>
            <a href="Controller/UserController.php?action=editProfile"> 
            <button class="edit-profile-button">Edit Profile</button>
                    </a>
                <a href="/index.php?controller=User&action=logout">

                <button class="logout-button">Logout</button>
            </a>
        <?php else: ?>
            <!-- Login and Register buttons -->
            <a href="View/User/login.php">
                <button class="login-button">Login</button>
            </a>
            <a href="View/User/register.php">
                <button class="register-button">Register</button>
            </a>
        <?php endif; ?>
    </div>
</div>


<div class="container">
    <h1>Categories</h1>

    <?php if (isset($_GET['message'])): ?>
        <p class="feedback success"><?php echo htmlspecialchars($_GET['message']); ?></p>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <p class="feedback error"><?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION['login_error'])): ?>
        <p class="feedback error"><?php echo htmlspecialchars($_SESSION['login_error']); ?></p>
        <?php unset($_SESSION['login_error']); // Clear message after displaying ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['register_error'])): ?>
        <p class="feedback error"><?php echo htmlspecialchars($_SESSION['register_error']); ?></p>
        <?php unset($_SESSION['register_error']); // Clear message after displaying ?>
    <?php endif; ?>

    <?php if ($isAdmin): ?>
        <a href="index.php?controller=Category&action=addCategoryForm">
            <button class="add-button">Add Category</button>
        </a>
    <?php endif; ?>

    <div class="category-grid">
    <?php if (!empty($mainCategories)): ?>
        <?php foreach ($mainCategories as $mainCategory): ?>
            <div class="category-box" id="category-<?php echo $mainCategory->getId(); ?>">
                <h2><?php echo htmlspecialchars($mainCategory->getName()); ?></h2>
                <img src="<?php echo htmlspecialchars($mainCategory->getImage()); ?>" alt="<?php echo htmlspecialchars($mainCategory->getName()); ?>" class="category-image" loading="lazy">

                <?php if ($isAdmin): ?>
                    <div class="actions">
                        <a href="index.php?controller=Category&action=editCategoryForm&id=<?php echo $mainCategory->getId(); ?>">
                            <button class="edit-button">Edit</button>
                        </a>
                        
                        <!-- Delete button with a confirmation form -->
                        <form action="index.php?controller=Category&action=deleteCategory" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');">
                            <input type="hidden" name="id" value="<?php echo $mainCategory->getId(); ?>">
                            <button type="submit" class="delete-button">Delete</button>
                        </form>
                    </div>
                <?php endif; ?>

                <h3>Subcategories</h3>
                <div class="subcategory-grid">
                    <?php if (!empty($mainCategory->subcategories)): ?>
                        <?php foreach ($mainCategory->subcategories as $subcategory): ?>
                            <div class="subcategory-box">
                                <h4><?php echo htmlspecialchars($subcategory->getName()); ?></h4>
                                <div class="actions">
                                    <?php if ($isAdmin): ?>
                                        <a href="index.php?controller=Category&action=editCategoryForm&id=<?php echo $subcategory->getId(); ?>">
                                            <button class="edit-button">Edit</button>
                                        </a>
                                        <form action="index.php?controller=Category&action=deleteCategory" method="POST" onsubmit="return confirm('Are you sure you want to delete this subcategory?');">
                                            <input type="hidden" name="id" value="<?php echo $subcategory->getId(); ?>">
                                            <button type="submit" class="delete-button">Delete</button>
                                        </form>
                                    <?php endif; ?>
                                    <a href="index.php?controller=Category&action=viewSubcategoryProducts&subcategoryId=<?php echo $subcategory->getId(); ?>">
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
        <?php endforeach; ?>
    <?php else: ?>
        <p>No categories found.</p>
    <?php endif; ?>
</div>

</div>

<div class="footer">
    <p>&copy; <?php echo date("Y"); ?> Your Company Name. All rights reserved.</p>
</div>

<script src="assets/js/script.js"></script>
<style>
    .feedback {
        padding: 10px 15px;
        margin: 15px 0;
        border-radius: 5px;
        text-align: center;
        font-weight: bold;
    }
    .feedback.success {
        color: #155724;
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
    }
    .feedback.error {
        color: #721c24;
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
    }

    /* Adjustments for fixed image size */
    .category-image {
        width: 100%; /* Ensures the image stretches to fit the container */
        max-height: 350px; /* Ensures a fixed height */
        object-fit: cover; /* Makes sure the image fits in the container without distortion */
        border-radius: 8px; /* Optional: for rounded corners */
    }
</style>

</body>
</html>
