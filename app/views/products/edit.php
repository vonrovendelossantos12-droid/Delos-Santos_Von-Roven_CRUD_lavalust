<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Edit Product') ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9fafb; margin: 0; padding: 30px; }
        .container { max-width: 700px; margin: 0 auto; background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.08); }
        h1 { margin-top: 0; }
        label { display: block; margin-bottom: 8px; font-weight: 600; }
        input, textarea { width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; margin-bottom: 15px; box-sizing: border-box; }
        textarea { min-height: 110px; resize: vertical; }
        .btn { display: inline-block; padding: 10px 14px; border-radius: 8px; text-decoration: none; border: none; cursor: pointer; }
        .btn-primary { background: #2563eb; color: white; }
        .btn-secondary { background: #e5e7eb; color: #111827; }
        .error { color: #b91c1c; font-size: 14px; margin-top: -10px; margin-bottom: 12px; }
        .actions { display: flex; gap: 10px; align-items: center; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Product</h1>

        <form method="post" action="<?= BASE_URL ?: '/' ?>products/update/<?= (int) ($product['id'] ?? 0) ?>">
            <label for="name">Name</label>
            <input id="name" name="name" type="text" value="<?= htmlspecialchars((string) ($product['name'] ?? '')) ?>" required>
            <?php if (!empty($errors['name'])): ?><div class="error"><?= htmlspecialchars($errors['name']) ?></div><?php endif; ?>

            <label for="description">Description</label>
            <textarea id="description" name="description"><?= htmlspecialchars((string) ($product['description'] ?? '')) ?></textarea>

            <label for="price">Price</label>
            <input id="price" name="price" type="number" min="0" step="0.01" value="<?= htmlspecialchars((string) ($product['price'] ?? '')) ?>" required>
            <?php if (!empty($errors['price'])): ?><div class="error"><?= htmlspecialchars($errors['price']) ?></div><?php endif; ?>

            <label for="stock">Stock</label>
            <input id="stock" name="stock" type="number" min="0" value="<?= htmlspecialchars((string) ($product['stock'] ?? 0)) ?>" required>
            <?php if (!empty($errors['stock'])): ?><div class="error"><?= htmlspecialchars($errors['stock']) ?></div><?php endif; ?>

            <div class="actions">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="<?= BASE_URL ?: '/' ?>products" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
