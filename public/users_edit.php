<?php

require_once dirname(__DIR__) . '/bootstrap.php';

use App\Controllers\UserController;

$controller = new UserController();
$controller->edit();

if ($id <= 0) {
    SessionManager::setError('Invalid user ID.');
    Redirect::to('users_list.php');
}

$userModel = new User();
$result = $userModel->getById($id);

if (!$result || $result->num_rows === 0) {
    SessionManager::setError('User not found.');
    Redirect::to('users_list.php');
}

$userData = $result->fetch_assoc();
$username = $userData['username'];
$accountType = $userData['account_type'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $accountType = trim($_POST['account_type'] ?? '');

    if (!Validator::required($username)) {
        $errors[] = 'Username is required.';
    } elseif (!Validator::length($username, 3, 50)) {
        $errors[] = 'Username must be between 3 and 50 characters.';
    }

    if (!Validator::enum($accountType, $roles)) {
        $errors[] = 'Invalid account type selected.';
    }

    if ($userModel->usernameExists($username, $id)) {
        $errors[] = 'Username already exists for another user.';
    }

    if (empty($errors)) {
        if ($userModel->update($id, $username, $accountType)) {
            SessionManager::setSuccess('User updated successfully!');
            Redirect::to('users_list.php');
        } else {
            $errors[] = 'Database error: could not update user.';
        }
    }
}

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit User</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <main class="container">
        <div class="top-actions">
            <h2>Edit User</h2>
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
            <label>Account Type
                <select name="account_type" required>
                    <option value="">-- Select --</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?php echo $role; ?>" <?php echo ($accountType === $role ? 'selected' : ''); ?>><?php echo ucfirst($role); ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <button class="btn" type="submit">Save</button>
        </form>
    </main>
</body>
</html>
