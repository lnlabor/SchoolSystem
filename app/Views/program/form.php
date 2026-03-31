<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $action; ?> Program</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <main class="container">
        <div class="top-actions">
            <h2><?php echo $action; ?> Program</h2>
            <div>
                <a class="btn secondary" href="index.php?controller=program&action=list">Back to List</a>
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

        <form method="post" action="<?php echo isset($id) ? 'index.php?controller=program&action=edit&id=' . intval($id) : 'index.php?controller=program&action=new'; ?>">
            <label>Code
                <input type="text" name="code" value="<?php echo htmlspecialchars($code); ?>" required>
            </label>
            <label>Title
                <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
            </label>
            <label>Years
                <input type="number" name="years" value="<?php echo htmlspecialchars($years); ?>" min="1" max="6" required>
            </label>
            <button class="btn" type="submit"><?php echo $action === 'Edit' ? 'Update' : 'Save'; ?></button>
        </form>
    </main>
</body>
</html>
