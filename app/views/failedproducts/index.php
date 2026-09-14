<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Products') ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9fafb; margin: 0; padding: 30px; }
        .container { max-width: 1000px; margin: 0 auto; }
        .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .actions { display: flex; gap: 10px; align-items: center; }
        .btn { display: inline-block; padding: 10px 14px; border-radius: 8px; text-decoration: none; border: none; cursor: pointer; }
        .btn-primary { background: #2563eb; color: white; }
        .btn-danger { background: #dc2626; color: white; }
        .btn-secondary { background: #e5e7eb; color: #111827; }
        .alert { background: #dcfce7; color: #166534; padding: 12px; border-radius: 8px; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 6px 18px rgba(0,0,0,0.05); }
        th, td { padding: 14px; border-bottom: 1px solid #e5e7eb; text-align: left; }
        th { background: #eef2ff; }
        .actions-cell { display: flex; gap: 8px; }
        .empty { background: white; padding: 20px; text-align: center; border-radius: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="topbar">
            <h1>Products</h1>
            <div class="actions">
                <a class="btn btn-primary" href="<?= BASE_URL ?: '/' ?>products/create">Add Product</a>
                <a class="btn btn-secondary" href="<?= BASE_URL ?: '/' ?>logout">Logout</a>
            </div>
        </div>

        <?php if (!empty($success)): ?>
            <div class="alert"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if (empty($products)): ?>
            <div class="empty">No products found.</div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= htmlspecialchars((string) ($product['id'] ?? '')) ?></td>
                            <td><?= htmlspecialchars((string) ($product['name'] ?? '')) ?></td>
                            <td><?= htmlspecialchars((string) ($product['description'] ?? '')) ?></td>
                            <td>$<?= number_format((float) ($product['price'] ?? 0), 2) ?></td>
                            <td><?= htmlspecialchars((string) ($product['stock'] ?? 0)) ?></td>
                            <td class="actions-cell">
                                <a class="btn btn-secondary" href="<?= BASE_URL ?: '/' ?>products/edit/<?= (int) ($product['id'] ?? 0) ?>">Edit</a>
                                <form method="post" action="<?= BASE_URL ?: '/' ?>products/delete/<?= (int) ($product['id'] ?? 0) ?>" onsubmit="return confirm('Delete this product?');">
                                    <button class="btn btn-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
