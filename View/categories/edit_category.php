<!-- C:\xampp\htdocs\ecommerce_master\View\categories\edit_category.php -->
<form method="POST" action="index.php?controller=Category&action=updateCategory&id=<?php echo $categoryData['id']; ?>">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo $categoryData['name']; ?>" required>
    <label for="image">Image URL:</label>
    <input type="text" id="image" name="image" value="<?php echo $categoryData['image']; ?>" required>
    <label for="parent_id">Parent Category ID (optional):</label>
    <input type="number" id="parent_id" name="parent_id" value="<?php echo $categoryData['parent_id']; ?>">
    <button type="submit">Update Category</button>
</form>