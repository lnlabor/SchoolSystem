<?php
include 'auth.php';
require_admin();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if($id<=0) die('Invalid user id.');

$stmt = $conn->prepare('SELECT id, username, account_type FROM users WHERE id = ? LIMIT 1');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
if(!$res || $res->num_rows===0) die('User not found.');
$user = $res->fetch_assoc();

$errors = [];
$allowed = ['admin','staff','teacher','student'];
$username = $user['username'];
$account_type = $user['account_type'];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = trim($_POST['username'] ?? '');
    $account_type = $_POST['account_type'] ?? 'student';
    if($username === '') $errors[] = 'Username is required.';
    if(!in_array($account_type, $allowed, true)) $errors[] = 'Invalid account type.';

    // check unique excluding current
    $ch = $conn->prepare('SELECT id FROM users WHERE username = ? AND id != ? LIMIT 1');
    $ch->bind_param('si', $username, $id);
    $ch->execute();
    $rch = $ch->get_result();
    if($rch && $rch->num_rows>0) $errors[] = 'Username already taken.';

    if(empty($errors)){
        $up = $conn->prepare('UPDATE users SET username = ?, account_type = ?, updated_on = NOW(), updated_by = ? WHERE id = ?');
        $admin = $_SESSION['user_id'];
        $up->bind_param('ssii', $username, $account_type, $admin, $id);
        if($up->execute()){
            $_SESSION['success'] = 'User updated.';
            header('Location: users_list.php'); exit;
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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="container">
        <?php if(!empty($errors)): ?>
            <div class="error"><ul><?php foreach($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?></ul></div>
        <?php endif; ?>
        <div class="top-actions">
            <h2>Edit User</h2>
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
            <p>To change password use the user's own Change Password page.</p>
            <button class="btn" type="submit">Update</button>
        </form>
    </main>
</body>
</html>
