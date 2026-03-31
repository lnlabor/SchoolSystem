<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <main class="container">
        <h2>Login</h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post">
            <label>Username
                <input type="text" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
            </label>
            <label>Password
                <input type="password" name="password" required>
            </label>
            <button class="btn" type="submit">Login</button>
        </form>
    </main>
</body>
</html>
