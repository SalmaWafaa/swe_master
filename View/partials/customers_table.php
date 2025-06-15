<!-- Customer Management Section -->
<div class="card">
    <div class="card-header">
        <h3>Customer Management</h3>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-hover" id="customerTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($customers) && is_array($customers)): ?>
                    <?php foreach ($customers as $customer): ?>
                    <tr id="customer-row-<?php echo $customer['id']; ?>">
                        <td><?php echo $customer['id']; ?></td>
                        <td><?php echo htmlspecialchars($customer['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($customer['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($customer['email']); ?></td>
                        <td>
                            <button class="btn btn-danger btn-sm"
                                onclick="confirmDeleteCustomer(<?php echo $customer['id']; ?>)">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No customers found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmDeleteCustomer(customerId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will permanently delete the customer.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            deleteCustomerAjax(customerId);
        }
    });
}

function deleteCustomerAjax(customerId) {
    fetch(`index.php?controller=AdminDashboardController&action=deleteCustomerAjax`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${customerId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`customer-row-${customerId}`).remove();
            Swal.fire('Deleted!', 'Customer has been deleted.', 'success');
        } else {
            Swal.fire('Error', data.message || 'Failed to delete customer.', 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error', 'Something went wrong.', 'error');
        console.error('Delete error:', error);
    });
}
</script>
