<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border: 2px solid #000;
            border-radius: 5px;
            width: 300px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        p {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #000;
            border-radius: 3px;
            box-sizing: border-box; /* Include padding and border in width */
        }

        button {
            background-color: #000;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #333;
        }

        input:focus, select:focus, button:focus {
            outline: none;
            border-color: #555;
        }
    </style>
</head>
<body>
    <form action="<?= site_url('/login'); ?>" method="post">
        <h1>Login</h1>
        <?php if (!empty($error)): ?>
            <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="role">Login as:</label>
        <select id="role" name="role" required>
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>

        <button type="submit">Login</button>
    </form>
</body>
</html>