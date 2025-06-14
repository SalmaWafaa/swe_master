

<header>
    <h1>Welcome to Our E-Commerce Store</h1>
    <p>Find the best products at unbeatable prices!</p>
</header>

<section>
    <h2>Featured Products</h2>
    <div class="products">
        <?php foreach ($products as $product): ?>
            <div class="product">
                <img src="public/images/<?= $product['image']; ?>" alt="<?= $product['name']; ?>">
                <h3><?= $product['name']; ?></h3>
                <p>Price: <?= $product['price']; ?></p>
                <button>Add to Cart</button>
            </div>
        <?php endforeach; ?>
    </div>
</section>

