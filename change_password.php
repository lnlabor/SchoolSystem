<?php
include 'auth.php';
require_login();

$errors = [];
$success = '';
$uid = $_SESSION['user_id'];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $current = $_POST['current'] ?? '';
    $new = $_POST['new'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if($current === '' || $new === '' || $confirm === '') $errors[] = 'All fields are required.';
    if(strlen($new) < 6) $errors[] = 'New password must be at least 6 characters.';
    if($new !== $confirm) $errors[] = 'New password confirmation does not match.';

    if(empty($errors)){
        $st = $conn->prepare('SELECT password FROM users WHERE id = ? LIMIT 1');
        $st->bind_param('i', $uid);
        $st->execute();
        $r = $st->get_result();
        if(!$r || $r->num_rows===0){
            $errors[] = 'User not found.';
        } else {
            $row = $r->fetch_assoc();
            if(!password_verify($current, $row['password'])){
                $errors[] = 'Current password is incorrect.';
            } else {
                $hash = password_hash($new, PASSWORD_DEFAULT);
                $up = $conn->prepare('UPDATE users SET password = ?, updated_on = NOW(), updated_by = ? WHERE id = ?');
                $me = $uid;
                $up->bind_param('sii', $hash, $me, $uid);
                if($up->execute()){
                    $_SESSION['success'] = 'Password updated.';
                    header('Location: home.php'); exit;
                } else {
                    $errors[] = 'Database error: could not update password.';
                }
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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="container">
        <?php if(!empty($errors)): ?><div class="error"><ul><?php foreach($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?></ul></div><?php endif; ?>
        <div class="top-actions">
            <h2>Change Password</h2>
            <div>
                <a class="btn secondary" href="home.php">Back to Home</a>
            </div>
        </div>
        <form method="post">
            <label>Current Password
                <input type="password" name="current">
            </label>
            <label>New Password
                <input type="password" name="new">
            </label>
            <label>Confirm New Password
                <input type="password" name="confirm">
            </label>
            <button class="btn" type="submit">Change Password</button>
        </form>
    </main>
</body>
</html>
