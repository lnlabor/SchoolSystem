<?php
include 'auth.php';
require_admin();

$res = $conn->query("SELECT id, username, account_type, created_on, updated_on FROM users ORDER BY id ASC");
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Users - List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="container">
        <?php echo flash_message(); ?>
        <div class="top-actions">
            <h2>Users</h2>
            <div>
                <a class="btn secondary" href="home.php">Back to Home</a>
                <a class="btn" href="users_new.php">Add New User</a>
            </div>
        </div>

        <table>
            <tr><th>Username</th><th>Account Type</th><th>Created</th><th>Updated</th><th>Action</th></tr>
            <?php if($res && $res->num_rows>0): ?>
                <?php while($row = $res->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['account_type']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_on']); ?></td>
                        <td><?php echo htmlspecialchars($row['updated_on']); ?></td>
                        <td><a href="users_edit.php?id=<?php echo intval($row['id']); ?>">Edit</a></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">No users found.</td></tr>
            <?php endif; ?>
        </table>

    </main>
</body>
</html>
