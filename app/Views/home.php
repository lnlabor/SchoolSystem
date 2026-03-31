<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>School Encoding Module</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="../style.css">
</head>
<body class="home">
    <main class="container">
        <?php echo \App\Core\SessionManager::getFlashMessages(); ?>
        <h1>School Encoding Module</h1>
        <p>Welcome, <?php echo htmlspecialchars($user['username']); ?> (<?php echo htmlspecialchars($user['account_type']); ?>)</p>
        <p>Use the buttons below to manage programs and subjects.</p>
        <div class="top-actions">
            <div></div>
            <div>
                <a class="btn" href="index.php?controller=program&action=list">Manage Programs</a>
                <a class="btn" href="index.php?controller=subject&action=list">Manage Subjects</a>
                <a class="btn" href="index.php?controller=password&action=change">Change Password</a>
                <a class="btn" href="index.php?controller=auth&action=logout">Logout</a>
                <?php if ($user['account_type'] === 'admin'): ?>
                    <a class="btn" href="index.php?controller=user&action=list">Manage Users</a>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>
