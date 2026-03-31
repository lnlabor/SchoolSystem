<?php

require_once dirname(__DIR__) . '/bootstrap.php';

use App\Controllers\PasswordController;

$controller = new PasswordController();
$controller->change();

$errors = [];
$current = '';
$newPass = '';
$confirm = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current'] ?? '';
    $newPass = $_POST['new'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if (!Validator::required($current) || !Validator::required($newPass) || !Validator::required($confirm)) {
        $errors[] = 'All fields are required.';
    }

    if (!Validator::passwordStrength($newPass, 6)) {
        $errors[] = 'New password must be at least 6 characters.';
    }

    if ($newPass !== $confirm) {
        $errors[] = 'Password confirmation does not match.';
    }

    if (empty($errors)) {
        $userId = Auth::currentUser()['id'];
        $userModel = new User();

        if (!$userModel->verifyPassword($userId, $current)) {
            $errors[] = 'Current password is incorrect.';
        } else {
            if ($userModel->changePassword($userId, $newPass)) {
                SessionManager::setSuccess('Password updated successfully.');
                Redirect::to('home.php');
            } else {
                $errors[] = 'Database error: could not update password.';
            }
        }
    }
}

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Change Password</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <main class="container">
        <div class="top-actions">
            <h2>Change Password</h2>
            <div>
                <a class="btn secondary" href="home.php">Back to Home</a>
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
            <label>Current Password
                <input type="password" name="current" required>
            </label>
            <label>New Password
                <input type="password" name="new" required>
            </label>
            <label>Confirm New Password
                <input type="password" name="confirm" required>
            </label>
            <button class="btn" type="submit">Change Password</button>
        </form>
    </main>
</body>
</html>
