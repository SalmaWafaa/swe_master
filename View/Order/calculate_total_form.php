<!DOCTYPE html>
<html>
<head>
    <title>Calculate Final Order Total</title>
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
        <div class="col-md-8">
            <h2 class="mb-4 text-center">🧾 Calculate Final Order Total</h2>

            <div class="d-flex justify-content-between mb-4">
                <a href="/swe_master/View/Cart/CartView.php" class="btn btn-outline-secondary">
                    <i class="fas fa-shopping-cart"></i> Back to Cart
                </a>
                <a href="/swe_master/index.php" class="btn btn-outline-primary">
                    <i class="fas fa-home"></i> Back to Home
                </a>
            </div>

            <?php if (isset($success) && $success == 1): ?>
                <div class="alert alert-success">
                    ✅ Order total calculated successfully! Final Total: <?= number_format($total, 2) ?> EGP
                </div>
            <?php elseif (isset($error)): ?>
                <div class="alert alert-danger">
                    🚫 Error: <?= htmlspecialchars($message ?? 'An unknown error occurred.') ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?controller=Order&action=calculateForm" class="bg-white p-4 rounded shadow-sm mb-4">
                <div class="mb-3">
                    <label class="form-label">Promo Code:</label>
                    <input type="text" name="promo" class="form-control" value="<?= htmlspecialchars($promoCode) ?>" placeholder="Enter promo code if any (e.g. SYS20)">
                </div>

                <div class="mb-3">
                    <label class="form-label">Shipping City:</label>
                    <select name="city" class="form-select" required>
                        <option value="">-- Select City --</option>
                        <?php foreach ($shippingRates as $cityName => $cost): ?>
                            <option value="<?= $cityName ?>" <?= $city === $cityName ? 'selected' : '' ?>>
                                <?= $cityName ?> - <?= $cost ?> EGP
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" name="calculate" class="btn btn-primary w-100">Calculate Total</button>
            </form>

            <div class="card p-4">
                <h5 class="mb-3">📦 Order Summary</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Subtotal: <strong><?= number_format($subtotal, 2) ?> EGP</strong></li>
                    <li class="list-group-item">Tax (14%): <strong><?= number_format($tax, 2) ?> EGP</strong></li>
                    <li class="list-group-item">Shipping: <strong><?= number_format($shippingCost, 2) ?> EGP</strong></li>
                    <li class="list-group-item">Promo Discount: <strong>-<?= number_format($promoDiscount, 2) ?> EGP</strong></li>
                </ul>
                <hr>
                <h5 class="text-end">Total: <span class="text-success"><?= number_format($finalTotal, 2) ?> EGP</span></h5>

             <form method="post" action="index.php?controller=Order&action=proceedToPayment">
    <input type="hidden" name="promo" value="<?= htmlspecialchars($promoCode) ?>">
    <input type="hidden" name="city" value="<?= htmlspecialchars($city) ?>">
    <button type="submit" class="btn btn-success w-100 mt-3">
        <i class="fas fa-money-check-alt"></i> Proceed to Payment
    </button>
</form>

                <?php if (isset($error) && $error === 'missing_city'): ?>
                    <div class="alert alert-danger mt-3">
                        🚫 Please select a shipping city before proceeding to payment.
                    </div>
                <?php endif; ?>
                 <?php if (isset($error) && $error === 'empty_cart'): ?>
                    <div class="alert alert-warning mt-3">
                        🛒 Your cart is empty. Please add items before proceeding to payment.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>