<!-- Categories Section -->
<div class="card mb-4">
    <div class="card-header">
        <h3>Categories Overview</h3>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Category Name</th>
                    <th>Parent Category</th>
                    <th>Product Count</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($categories) && is_array($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($category['name']); ?></td>
                        <td><?php echo htmlspecialchars($category['parent_name'] ?? 'Main Category'); ?></td>
                        <td><?php echo $category['product_count']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">No categories found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div> 