<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
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

        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #000;
            border-radius: 4px;
            background-color: #fff;
            font-size: 14px;
            font-family: inherit;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        .form-control:focus {
            outline: none;
            border-width: 2px;
            padding: 9px 11px; /* Adjust padding to prevent shift on 2px border */
        }

        .btn {
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

        .btn:hover {
            background-color: #333;
        }

        .btn:focus {
            outline: none;
            border-color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Product</h2>
        <p class="back-link"><a href="<?= site_url('/product/display'); ?>">&larr; Back to products</a></p>
        
        <form action="<?= site_url('/product/create'); ?>" method="post">
            <div class="form-group">
                <label for="product_name">Product Name:</label>
                <input type="text" class="form-control" id="product_name" name="product_name" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description"></textarea>
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required>
            </div>

            <button type="submit" class="btn btn-primary">Add Product</button>
        </form>
    </div>
</body>
</html>