<!DOCTYPE html>
<html>
<head>
    <title>Proceed to Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .card {
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-label {
            font-weight: bold;
        }
        .btn i {
            margin-right: 6px;
        }
    </style>
</head>
<body class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4 text-center">💳 Select Payment Method</h2>

            <?php
            // Values passed from PaymentController::paymentForm()
            $orderId = $orderId ?? ''; // Ensure these are set
            $totalAmount = $totalAmount ?? 0;
            ?>

            <div class="card p-4">
                <h5 class="mb-3">Order Details</h5>
                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item">Order ID: <strong><?= htmlspecialchars($orderId) ?></strong></li>
                    <li class="list-group-item">Amount Due: <strong class="text-success"><?= number_format($totalAmount, 2) ?> EGP</strong></li>
                </ul>

                <form method="POST" action="index.php?controller=Payment&action=process">
                    <input type="hidden" name="order_id" value="<?= htmlspecialchars($orderId) ?>">
                    <input type="hidden" name="amount" value="<?= htmlspecialchars($totalAmount) ?>">

                    <div class="mb-3">
                        <label class="form-label" for="payment_method">Choose Payment Method:</label>
                        <select name="payment_method" id="payment_method" class="form-select" required>
                            <option value="">-- Select --</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cash_on_delivery">Cash on Delivery</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success w-100 mt-3">
                        <i class="fas fa-check-circle"></i> Confirm Payment
                    </button>
                </form>

                <div class="mt-3 text-center">
                    <a href="/swe_master/index.php" class="btn btn-outline-secondary">
                        <i class="fas fa-home"></i> Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>