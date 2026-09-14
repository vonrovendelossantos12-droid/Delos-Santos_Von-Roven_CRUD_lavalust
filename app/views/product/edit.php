<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
</head>
<body>
    <h2>Edit Product</h2>
    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form action="<?= site_url('/product/edit/' . $product['id']); ?>" method="post">
        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" value="<?= htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?= htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" value="<?= htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="<?= htmlspecialchars($product['quantity'], ENT_QUOTES, 'UTF-8'); ?>" required>

        <button type="submit">Update Product</button>
    </form>
    <a href="<?= site_url('/product/display'); ?>">Back to products</a>
</body>
</html>