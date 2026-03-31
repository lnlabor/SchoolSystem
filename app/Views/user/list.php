<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Users - List</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <main class="container">
        <div class="top-actions">
            <h2>Users</h2>
            <div>
                <a class="btn secondary" href="index.php?controller=home&action=index">Back to Home</a>
                <a class="btn" href="index.php?controller=user&action=new">Add New User</a>
            </div>
        </div>

        <?php echo \App\Core\SessionManager::getFlashMessages(); ?>

        <table>
            <tr>
                <th>Username</th>
                <th>Account Type</th>
                <th>Created</th>
                <th>Updated</th>
                <th>Action</th>
            </tr>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['account_type']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_on']); ?></td>
                        <td><?php echo htmlspecialchars($row['updated_on']); ?></td>
                        <td><a href="index.php?controller=user&action=edit&id=<?php echo intval($row['id']); ?>">Edit</a></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No users found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </main>
</body>
</html>
