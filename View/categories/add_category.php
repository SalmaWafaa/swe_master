<!-- C:\xampp\htdocs\ecommerce_master\View\categories\add_category.php -->
<form method="POST" action="index.php?controller=Category&action=createCategory">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required>
    <label for="image">Image URL:</label>
    <input type="text" id="image" name="image" required>
    <label for="parent_id">Parent Category ID (optional):</label>
    <input type="number" id="parent_id" name="parent_id">
    <button type="submit">Add Category</button>
</form>