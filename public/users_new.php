<?php

require_once dirname(__DIR__) . '/bootstrap.php';

use App\Controllers\UserController;

$controller = new UserController();
$controller->create();

$errors = [];
$username = '';
$accountType = '';

$roles = ['admin', 'staff', 'teacher', 'student'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';
    $accountType = trim($_POST['account_type'] ?? '');

    if (!Validator::required($username)) {
        $errors[] = 'Username is required.';
    } elseif (!Validator::length($username, 3, 50)) {
        $errors[] = 'Username must be between 3 and 50 characters.';
    }

    if (!Validator::required($password)) {
        $errors[] = 'Password is required.';
    } elseif (!Validator::passwordStrength($password, 6)) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if ($password !== $confirm) {
        $errors[] = 'Password confirmation does not match.';
    }

    if (!Validator::enum($accountType, $roles)) {
        $errors[] = 'Invalid account type selected.';
    }

    $userModel = new User();
    if ($userModel->usernameExists($username)) {
        $errors[] = 'Username already exists.';
    }

    if (empty($errors)) {
        if ($userModel->create($username, $password, $accountType)) {
            SessionManager::setSuccess('User created successfully!');
            Redirect::to('users_list.php');
        } else {
            $errors[] = 'Database error: could not create user.';
        }
    }
}

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Add User</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <main class="container">
        <div class="top-actions">
            <h2>Add User</h2>
            <div>
                <a class="btn secondary" href="users_list.php">Back to List</a>
            </div>
        </div>

        <?php echo SessionManager::getFlashMessages(); ?>

        <?php if (!empty($errors)): ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post">
            <label>Username
                <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
            </label>
            <label>Password
                <input type="password" name="password" required>
            </label>
            <label>Confirm Password
                <input type="password" name="confirm" required>
            </label>
            <label>Account Type
                <select name="account_type" required>
                    <option value="">-- Select --</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?php echo $role; ?>" <?php echo ($accountType === $role ? 'selected' : ''); ?>><?php echo ucfirst($role); ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <button class="btn" type="submit">Create</button>
        </form>
    </main>
</body>
</html>
