<!DOCTYPE html>
<html>
<head>
    <title>Payment Success</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <h2 class="mb-4 text-success">🎉 Payment Successful!</h2>
            <div class="alert alert-success d-flex align-items-center justify-content-center" role="alert">
                <i class="fas fa-check-circle me-2 fa-2x"></i>
                <div>Your payment has been successfully processed and your order is being prepared.</div>
            </div>
            <a href="/swe_master/index.php" class="btn btn-primary mt-3">
                <i class="fas fa-home"></i> Back to Home
            </a>
            <a href="/swe_master/View/Order/calculate_total_form.php" class="btn btn-info mt-3 ms-2">
                <i class="fas fa-receipt"></i> View Order Summary
            </a>
        </div>
    </div>
</body>
</html>