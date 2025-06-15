<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        h1 {
            font-weight: 700;
        }
        .stats-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }
        .stats-card:hover {
            transform: translateY(-4px);
        }
        .card-header h3 {
            margin-bottom: 0;
        }
        .low-stock {
            color: #dc3545;
            font-weight: bold;
        }
        .btn-primary, .btn-outline-primary {
            border-radius: 8px;
        }
        .table thead {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-chart-line me-2 text-primary"></i> Admin Dashboard</h1>
            <a href="index.php" class="btn btn-outline-primary"><i class="fas fa-home me-1"></i>Back to Home</a>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card stats-card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Products</h5>
                        <h2 class="card-text"><?= $statistics['total_products'] ?? 0 ?></h2>
                        <p>Total Stock: <?= number_format($statistics['total_stock'] ?? 0) ?> items</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stats-card bg-warning text-dark">
                    <div class="card-body">
                        <h5 class="card-title">Low Stock Products</h5>
                        <h2 class="card-text"><?= $statistics['low_stock'] ?? 0 ?></h2>
                        <p>Products with stock < 10</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stats-card bg-danger text-white">
                    <div class="card-body">
                        <h5 class="card-title">Out of Stock</h5>
                        <h2 class="card-text"><?= $statistics['out_of_stock'] ?? 0 ?></h2>
                        <p>Products need restock</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert Section -->
        <?php if (!empty($statistics['low_stock_products'])): ?>
        <div class="card shadow-sm mb-4 border border-warning">
            <div class="card-header bg-warning text-dark d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <h3 class="mb-0">Low Stock Alert</h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Product Name</th>
                            <th>Current Stock</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($statistics['low_stock_products'] as $product): ?>
                        <tr>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td class="low-stock"><?= $product['quantity'] ?></td>
                            <td><?= number_format($product['price'], 2) ?> EGP</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <!-- Products Table -->
        <div class="card mb-4">
            <div class="card-header">
                <h3>Products</h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td><?= htmlspecialchars($product['category_name']) ?></td>
                            <td><?= number_format($product['price'], 2) ?> EGP</td>
                            <td><?= $product['quantity'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Customers Table -->
        <div class="card mb-4">
            <div class="card-header">
                <h3>Customers</h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td><?= htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']) ?></td>
                            <td><?= htmlspecialchars($customer['email']) ?></td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="deleteCustomer(<?= $customer['id'] ?>)">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card mb-4">
            <div class="card-header">
                <h3>Orders</h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Payment Type</th>
                            <th>Items</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['order_id']) ?></td>
                            <td><?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?></td>
                            <td><?= number_format($order['total'], 2) ?> EGP</td>
                            <td><?= htmlspecialchars($order['state']) ?></td>
                            <td><?= date('Y-m-d H:i', strtotime($order['date_created'])) ?></td>
                            <td><?= htmlspecialchars($order['payment_type']) ?></td>
                            <td><?= htmlspecialchars($order['products']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteCustomer(customerId) {
            if (confirm('Are you sure you want to delete this customer?')) {
                fetch('index.php?controller=AdminDashboard&action=deleteCustomerAjax', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id=' + customerId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error deleting customer: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting customer');
                });
            }
        }
    </script>
</body>
</html>
