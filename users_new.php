<?php
include 'auth.php';
require_admin();

$errors = [];
$username = '';
$account_type = 'student';

$allowed = ['admin','staff','teacher','student'];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = trim($_POST['username'] ?? '');
    $account_type = $_POST['account_type'] ?? 'student';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if($username === '') $errors[] = 'Username is required.';
    if(!in_array($account_type, $allowed, true)) $errors[] = 'Invalid account type.';
    if(strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
    if($password !== $confirm) $errors[] = 'Password confirmation does not match.';

    // unique username
    $stmt = $conn->prepare('SELECT id FROM users WHERE username = ? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $r = $stmt->get_result();
    if($r && $r->num_rows>0) $errors[] = 'Username already taken.';

    if(empty($errors)){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $ins = $conn->prepare('INSERT INTO users (username, password, account_type, created_on, created_by, updated_on, updated_by) VALUES (?, ?, ?, NOW(), ?, NOW(), ?)');
        $admin_id = $_SESSION['user_id'];
        $ins->bind_param('sssii', $username, $hash, $account_type, $admin_id, $admin_id);
        if($ins->execute()){
            $_SESSION['success'] = 'User created.';
            header('Location: users_list.php'); exit;
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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="container">
        <?php if(!empty($errors)): ?>
            <div class="error"><ul><?php foreach($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?></ul></div>
        <?php endif; ?>
        <div class="top-actions">
            <h2>Add User</h2>
            <div>
                <a class="btn secondary" href="users_list.php">Back to List</a>
            </div>
        </div>
        <form method="post">
            <label>Username
                <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>">
            </label>
            <label>Account Type
                <select name="account_type">
                    <?php foreach($allowed as $a): ?>
                        <option value="<?php echo $a; ?>" <?php if($account_type===$a) echo 'selected'; ?>><?php echo ucfirst($a); ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Password
                <input type="password" name="password">
            </label>
            <label>Confirm Password
                <input type="password" name="confirm">
            </label>
            <button class="btn" type="submit">Create</button>
        </form>
    </main>
</body>
</html>
