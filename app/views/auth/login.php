<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Login') ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f4f6; margin: 0; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .card { background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); width: min(100%, 420px); }
        h1 { text-align: center; margin-bottom: 20px; }
        .alert { background: #fee2e2; color: #991b1b; padding: 10px 12px; border-radius: 8px; margin-bottom: 15px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; }
        input { width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; margin-bottom: 15px; box-sizing: border-box; }
        button { width: 100%; background: #2563eb; color: white; border: none; padding: 12px; border-radius: 8px; font-size: 16px; cursor: pointer; }
        .hint { text-align: center; margin-top: 12px; color: #4b5563; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Login</h1>

        <?php if (!empty($error)): ?>
            <div class="alert"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= BASE_URL ?: '/' ?>login">
            <label for="username">Username</label>
            <input id="username" name="username" type="text" value="admin" required>

            <label for="password">Password</label>
            <input id="password" name="password" type="password" value="admin123" required>

            <button type="submit">Sign In</button>
        </form>

        <div class="hint">Demo credentials: admin / admin123</div>
    </div>
</body>
</html>
