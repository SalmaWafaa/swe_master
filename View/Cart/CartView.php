
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4; color: #333; }
        h1, h2 { color: #333; text-align: center; margin-bottom: 20px;}
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background-color: #fff; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        img { max-width: 80px; height: auto; display: block; margin: 0 auto; border-radius: 5px;}
        .button-group { display: flex; gap: 5px; justify-content: center; align-items: center; }
        button {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        button.add { background-color: #4CAF50; color: white; }
        button.add:hover { background-color: #45a049; }
        button.delete { background-color: #f44336; color: white; }
        button.delete:hover { background-color: #da190b; }
        button.decrease { background-color: #FFC107; color: white; }
        button.decrease:hover { background-color: #e0a800; }
        .place-order-button {
            display: block;
            width: fit-content;
            margin: 20px auto;
            padding: 15px 30px;
            font-size: 18px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .place-order-button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
        p { text-align: center; font-size: 1.1em; color: #666; }

        /* Product Listing Styles */
        .product-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }
        .product-card {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }
        .product-card img {
            max-width: 120px;
            height: auto;
            margin-bottom: 10px;
        }
        .product-card h3 {
            font-size: 1.2em;
            margin-bottom: 5px;
        }
        .product-card p {
            font-size: 1.1em;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .product-card .add-to-cart-btn {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .product-card .add-to-cart-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <h1>Your Shopping Cart</h1>



    <h2>Cart Contents</h2>
    <?php if (empty($cartItems)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Product Image</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price (Each)</th>
                    <th>Total Item Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td><img src="/swe_master/uploads/<?= htmlspecialchars($item['product_image'] ?? 'placeholder.jpg') ?>" alt="<?= htmlspecialchars($item['name']) ?>" onerror="this.onerror=null;this.src='https://placehold.co/80x80/e2e8f0/64748b?text=Image';"/></td>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= htmlspecialchars($item['quantity']) ?></td>
                        <td>$<?= htmlspecialchars(number_format($item['price'], 2)) ?></td>
                        <td>$<?= htmlspecialchars(number_format($item['total_price'], 2)) ?></td>
                        <td>
                            <div class="button-group">
                                <form method="post" action="index.php?controller=RCart&action=addAndModifyCart">
                                    <input type="hidden" name="action" value="addItem">
                                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($item['product_id']) ?>">
                                    <button type="submit" class="add">➕ Add</button>
                                </form>
                                <form method="post" action="index.php?controller=RCart&action=addAndModifyCart">
                                    <input type="hidden" name="action" value="decreaseItem">
                                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($item['product_id']) ?>">
                                    <button type="submit" class="decrease">➖ Decrease</button>
                                </form>
                                <form method="post" action="index.php?controller=RCart&action=addAndModifyCart">
                                    <input type="hidden" name="action" value="deleteItem">
                                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($item['product_id']) ?>">
                                    <button type="submit" class="delete">🗑️ Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <form action="index.php?controller=Order&action=advanceState" method="post">
            <input type="hidden" name="order_id" value="123"> <!-- Placeholder order ID -->
            <button type="submit" class="place-order-button">
                🛒 Place Order
            </button>
        </form>
    <?php endif; ?>
</body>
</html>
