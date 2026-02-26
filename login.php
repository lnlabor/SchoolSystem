<?php
include 'auth.php';
// If already logged in, go to home
if(isset($_SESSION['user_id'])){
    header('Location: home.php'); exit;
}

$error = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if($username === '' || $password === ''){
        $error = 'Username and password are required.';
    } else {
        $stmt = $conn->prepare('SELECT id, username, password, account_type FROM users WHERE username = ? LIMIT 1');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $res = $stmt->get_result();
        if($res && $res->num_rows === 1){
            $u = $res->fetch_assoc();
            if(password_verify($password, $u['password'])){
                // login
                session_regenerate_id(true);
                $_SESSION['user_id'] = $u['id'];
                $_SESSION['username'] = $u['username'];
                $_SESSION['account_type'] = $u['account_type'];
                header('Location: home.php'); exit;
            }
        }
        $error = 'Invalid username or password.';
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="container">
        <h2>Login</h2>
        <?php if($error): ?><div class="error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
        <form method="post">
            <label>Username
                <input type="text" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            </label>
            <label>Password
                <input type="password" name="password">
            </label>
            <button class="btn" type="submit">Login</button>
        </form>
    </main>
</body>
</html>
