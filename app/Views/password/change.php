<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Change Password</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <main class="container">
        <div class="top-actions">
            <h2>Change Password</h2>
            <div>
                <a class="btn secondary" href="index.php?controller=home&action=index">Back to Home</a>
            </div>
        </div>

        <?php echo \App\Core\SessionManager::getFlashMessages(); ?>

        <?php if (!empty($errors)): ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="change_password.php">
            <label>Current Password
                <input type="password" name="current" required>
            </label>
            <label>New Password
                <input type="password" name="new" required>
            </label>
            <label>Confirm New Password
                <input type="password" name="confirm" required>
            </label>
            <button class="btn" type="submit">Change Password</button>
        </form>
    </main>
</body>
</html>
