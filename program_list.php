<?php
include 'auth.php';
require_login();
include 'db.php';
$result = $conn->query("SELECT program_id AS id, code, title, years FROM program ORDER BY program_id ASC");
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Programs - List</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="container">
        <div class="top-actions">
            <h2>Programs</h2>
            <div>
                <a class="btn secondary" href="home.php">Back to Home</a>
                <?php $u = current_user(); if(in_array($u['account_type'], ['admin','staff'])): ?>
                    <a class="btn" href="program_new.php">Add New Program</a>
                <?php endif; ?>
            </div>
        </div>

        <table>
            <tr><th>Code</th><th>Title</th><th>Years</th><th>Action</th></tr>
            <?php if($result && $result->num_rows>0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['code']); ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['years']); ?></td>
                        <td><?php $u = current_user(); if(in_array($u['account_type'], ['admin','staff'])): ?><a href="program_edit.php?program_id=<?php echo intval($row['id']); ?>">Edit</a><?php else: ?>-<?php endif; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4">No programs found.</td></tr>
            <?php endif; ?>
        </table>

        
    </main>
</body>
</html>
