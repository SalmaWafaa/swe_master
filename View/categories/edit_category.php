<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <link rel="stylesheet" href="assets/css/form.css"> <!-- Add your CSS -->
   
</head>
<body>

    <div class="container">
        <h1>Edit Category</h1>

        <!-- Check for any error or success messages -->
        <?php if (isset($_GET['error'])): ?>
            <div class="feedback error"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        
        <form action="index.php?controller=Category&action=updateCategory&id=<?php echo $categoryData->getId(); ?>" method="POST">
            <!-- Category Name -->
            <label for="name">Category Name</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($categoryData->getName()); ?>" required><br><br>

            <!-- Category Image URL -->
            <label for="image">Category Image URL</label>
            <input type="text" name="image" id="image" value="<?php echo htmlspecialchars($categoryData->getImage()); ?>"><br><br>

            <!-- Parent Category -->
            <label for="parent_id">Parent Category</label>
            <input type="number" name="parent_id" id="parent_id" value="<?php echo $categoryData->getParentId(); ?>"><br><br>

            <button type="submit">Update Category</button>
        </form>
    </div>

</body>
</html>
