<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProductView</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            color: #000;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
        }

        .container {
            width: 100%;
            max-width: 900px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }

        h4 {
            margin: 0;
            font-size: 22px;
            text-transform: capitalize;
        }

        .nav-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .btn-link {
            display: inline-block;
            padding: 8px 14px;
            border: 1px solid #000;
            border-radius: 4px;
            color: #000;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            transition: background-color 0.2s ease;
        }

        .btn-link:hover {
            background-color: #f0f0f0;
        }

        .btn-link.primary {
            background-color: #000;
            color: #fff;
        }

        .btn-link.primary:hover {
            background-color: #333;
        }

        /* Notification Banner */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            min-width: 260px;
            padding: 14px 18px;
            color: #000;
            background: #fff;
            border: 2px solid #000;
            border-radius: 4px;
            box-shadow: 4px 4px 0px #000;
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
            font-weight: bold;
        }

        /* Table Styling */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000;
            text-align: left;
            font-size: 14px;
        }

        th, td {
            padding: 12px 14px;
            border: 1px solid #000;
        }

        th {
            background-color: #000;
            color: #fff;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .action-cell {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            padding: 4px 8px;
            border: 1px solid #000;
            border-radius: 3px;
            color: #000;
            text-decoration: none;
            font-size: 12px;
            font-weight: bold;
        }

        .action-btn:hover {
            background-color: #eee;
        }

        .action-btn.delete {
            background-color: #fff;
            border-color: #000;
        }

        .action-btn.delete:hover {
            background-color: #000;
            color: #fff;
        }
    </style>
</head>
<body>

    <div class="container">
        <?php if (!empty($notification)): ?>
            <div class="notification" role="status" id="notification">
                <button type="button" onclick="document.getElementById('notification').remove();" aria-label="Close notification">&times;</button>
                <?= htmlspecialchars($notification, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <div class="header">
            <h4>Welcome, <?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></h4>
            <div class="nav-actions">
                <?php if ($user_role === 'admin'): ?>
                    <a href="<?= site_url('/product/create'); ?>" class="btn-link primary">+ Add Product</a>
                <?php endif; ?>
                <a href="<?= site_url('/logout'); ?>" class="btn-link">Logout</a>
            </div>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
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
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>$<?php echo htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($product['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <?php if ($user_role === 'admin'): ?>
                                <td>
                                    <div class="action-cell">
                                        <a href="<?= site_url('/product/edit/' . $product['id']); ?>" class="action-btn">Edit</a>
                                        <a href="<?= site_url('/product/delete/' . $product['id']); ?>" class="action-btn delete" onclick="return confirm('Delete this product?');">Delete</a>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

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