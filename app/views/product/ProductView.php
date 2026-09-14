<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProductView</title>
    <style>
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            min-width: 260px;
            padding: 14px 18px;
            color: #fff;
            background: #198754;
            border-radius: 4px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .notification button {
            float: right;
            margin-left: 16px;
            color: inherit;
            background: transparent;
            border: 0;
            cursor: pointer;
            font-size: 18px;
            line-height: 1;
        }
    </style>
</head>
<body>
    <h4><?php echo $name; ?></h4>
    <?php if (!empty($notification)): ?>
        <div class="notification" role="status" id="notification">
            <button type="button" onclick="document.getElementById('notification').remove();" aria-label="Close notification">&times;</button>
            <?= htmlspecialchars($notification, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>
    <?php if ($user_role === 'admin'): ?>
        <a href="<?= site_url('/product/create'); ?>">Add Product</a>
    <?php endif; ?>
    <a href="<?= site_url('/logout'); ?>">Logout</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Product Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Created At</th>
            <?php if ($user_role === 'admin'): ?>
                <th>Actions</th>
            <?php endif; ?>
        </tr>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?php echo $product['id']; ?></td>
                <td><?php echo $product['product_name']; ?></td>
                <td><?php echo $product['description']; ?></td>
                <td><?php echo $product['price']; ?></td>
                <td><?php echo $product['created_at']; ?></td>
                <?php if ($user_role === 'admin'): ?>
                    <td>
                        <a href="<?= site_url('/product/edit/' . $product['id']); ?>">Edit</a>
                        <a href="<?= site_url('/product/delete/' . $product['id']); ?>" onclick="return confirm('Delete this product?');">Delete</a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </table>
        <?php if (!empty($notification)): ?>
            <script>
                window.setTimeout(function () {
                    var notification = document.getElementById('notification');
                    if (notification) {
                        notification.remove();
                    }
                }, 4000);
            </script>
        <?php endif; ?>
</body>
</html>