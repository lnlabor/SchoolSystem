<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Programs - List</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <main class="container">
        <div class="top-actions">
            <h2>Programs</h2>
            <div>
                <a class="btn secondary" href="index.php?controller=home&action=index">Back to Home</a>
                <?php if ($canEdit): ?>
                    <a class="btn" href="index.php?controller=program&action=new">Add New Program</a>
                <?php endif; ?>
            </div>
        </div>

        <?php echo \App\Core\SessionManager::getFlashMessages(); ?>

        <table>
            <tr>
                <th>Code</th>
                <th>Title</th>
                <th>Years</th>
                <th>Action</th>
            </tr>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['code']); ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['years']); ?></td>
                        <td>
                            <?php if ($canEdit): ?>
                                <a href="index.php?controller=program&action=edit&id=<?php echo intval($row['id']); ?>">Edit</a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No programs found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </main>
</body>
</html>
