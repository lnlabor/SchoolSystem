<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $action; ?> User</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <main class="container">
        <div class="top-actions">
            <h2><?php echo $action; ?> User</h2>
            <div>
                <a class="btn secondary" href="index.php?controller=user&action=list">Back to List</a>
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

        <form method="post" action="<?php echo isset($id) ? 'index.php?controller=user&action=edit&id=' . intval($id) : 'index.php?controller=user&action=new'; ?>">
            <label>Username
                <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
            </label>

            <?php if ($action === 'Add'): ?>
                <label>Password
                    <input type="password" name="password" required>
                </label>
                <label>Confirm Password
                    <input type="password" name="confirm" required>
                </label>
            <?php endif; ?>

            <label>Account Type
                <select name="account_type" required>
                    <option value="">-- Select --</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?php echo $role; ?>" <?php echo ($accountType === $role ? 'selected' : ''); ?>><?php echo ucfirst($role); ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <button class="btn" type="submit"><?php echo $action === 'Edit' ? 'Save' : 'Create'; ?></button>
        </form>
    </main>
</body>
</html>
