<!-- Products Section -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <script>
        Swal.fire({
            title: 'Success!',
            text: 'Stock and price updated successfully!',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    </script>
<?php endif; ?>

<?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
    <script>
        Swal.fire({
            title: 'Error!',
            text: 'There was a problem updating the product.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    </script>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-header">
        <h3>Products Inventory</h3>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($products) && is_array($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <tr <?php echo $product['quantity'] < 10 ? 'class="table-warning"' : ''; ?>>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></td>
                            <td>
                            <form method="POST" action="index.php?controller=AdminDashboardController&action=updateStockPrice">
                            <input type="number" name="price" value="<?php echo $product['price']; ?>" step="0.01" class="form-control" required>
                            </td>
                            <td>
                                    <input type="number" name="stock" value="<?php echo $product['quantity']; ?>" class="form-control" required>
                            </td>
                            <td>
                                <?php if ($product['quantity'] == 0): ?>
                                    <span class="badge bg-danger">Out of Stock</span>
                                <?php elseif ($product['quantity'] < 10): ?>
                                    <span class="badge bg-warning text-dark">Low Stock</span>
                                <?php else: ?>
                                    <span class="badge bg-success">In Stock</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                    <!-- <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>"> -->
                                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No products found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
