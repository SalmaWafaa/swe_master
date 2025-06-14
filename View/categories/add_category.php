<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link rel="stylesheet" href="assets/css/form.css">
   
</head>
<body>
    <div class="form-container">
        <h1>Add New Category</h1>

        <!-- Display success/error messages -->
        <?php if (isset($message)): ?>
            <div class="feedback"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <form action="index.php?controller=Category&action=createCategory" method="POST">
            <div>
                <label for="name">Category Name</label>
                <input type="text" name="name" id="name" required placeholder="Enter category name">
            </div>
            
            <div>
                <label for="image">Category Image URL</label>
                <input type="text" name="image" id="image" placeholder="Enter image URL (optional)">
            </div>

            <div>
                <label for="parent_id">Parent Category (Optional)</label>
                <input type="number" name="parent_id" id="parent_id" placeholder="Enter parent category ID (if applicable)">
            </div>

            <div>
                <button type="submit">Add Category</button>
            </div>
        </form>
    </div>
</body>
</html>
