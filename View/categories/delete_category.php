<!-- C:\xampp\htdocs\swe_master\View\categories\delete_category.php -->
<p>Are you sure you want to delete the category "<?php echo $categoryData['name']; ?>"?</p>
<form method="POST" action="index.php?controller=Category&action=deleteCategory&id=<?php echo $categoryData['id']; ?>">
    <button type="submit">Yes, Delete</button>
    <a href="index.php?controller=Category&action=listCategories">Cancel</a>
</form>