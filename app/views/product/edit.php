<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            background-color: #fff;
            padding: 24px;
            border: 2px solid #000;
            border-radius: 6px;
            width: 100%;
            max-width: 420px;
        }

        h2 {
            text-align: center;
            margin-top: 0;
            margin-bottom: 8px;
            font-size: 24px;
        }

        .back-link {
            text-align: center;
            margin-bottom: 20px;
        }

        .back-link a {
            color: #000;
            text-decoration: underline;
            font-size: 14px;
        }

        .back-link a:hover {
            opacity: 0.7;
        }

        .error-list {
            background-color: #fff;
            border: 1px solid #000;
            padding: 12px 16px 12px 32px;
            margin-bottom: 20px;
            border-radius: 4px;
            color: #d9534f;
            font-size: 14px;
        }

        .error-list li {
            margin-bottom: 4px;
        }

        .error-list li:last-child {
            margin-bottom: 0;
        }

        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            font-size: 14px;
        }

        input, textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #000;
            border-radius: 4px;
            background-color: #fff;
            font-size: 14px;
            font-family: inherit;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        input:focus, textarea:focus {
            outline: none;
            border-width: 2px;
            padding: 9px 11px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #000;
            color: #fff;
            border: 1px solid #000;
            border-radius: 4px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.2s ease;
            margin-top: 8px;
        }

        button:hover {
            background-color: #333;
        }

        button:focus {
            outline: none;
            border-color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Product</h2>
        <p class="back-link"><a href="<?= site_url('/product/display'); ?>">&larr; Back to products</a></p>

        <?php if (!empty($errors)): ?>
            <ul class="error-list">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form action="<?= site_url('/product/edit/' . $product['id']); ?>" method="post">
            <div class="form-group">
                <label for="product_name">Product Name:</label>
                <input type="text" id="product_name" name="product_name" value="<?= htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required><?= htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" step="0.01" value="<?= htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" value="<?= htmlspecialchars($product['quantity'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>

            <button type="submit">Update Product</button>
        </form>
    </div>
</body>
</html>