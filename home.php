<?php
include 'auth.php';
require_login();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>School Encoding Module</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body class="home">
    <main class="container">
        <?php echo flash_message(); ?>
        <h1>School Encoding Module</h1>
        <?php $user = current_user(); ?>
        <p>Welcome, <?php echo htmlspecialchars($user['username']); ?> (<?php echo htmlspecialchars($user['account_type']); ?>)</p>
        <p>Use the buttons below to manage programs and subjects.</p>
        <div class="top-actions">
            <div></div>
            <div>
                <a class="btn" href="program_list.php">Manage Programs</a>
                <a class="btn" href="subject_list.php">Manage Subjects</a>
                <a class="btn" href="change_password.php">Change Password</a>
                <a class="btn" href="logout.php">Logout</a>
                <?php if($user['account_type'] === 'admin'): ?>
                    <a class="btn" href="users_list.php">Manage Users</a>
                <?php endif; ?>
            </div>
        </div>
        
    </main>
</body>
</html>
