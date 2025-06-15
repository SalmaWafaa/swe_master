<!-- Order State Table -->
<div class="card mt-4">
    <div class="card-header bg-info text-white">
        <h3><i class="fas fa-box"></i> Order States</h3>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User</th>
                    <th>Total</th>
                    <th>Current State</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= $order['order_id'] ?></td>
                    <td><?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?></td>
                    <td><?= number_format($order['total'], 2) ?> EGP</td>
                    <td>
                        <span class="badge bg-secondary"><?= htmlspecialchars($order['state']) ?></span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
