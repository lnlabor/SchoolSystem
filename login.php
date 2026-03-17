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
        if(Auth::login($username, $password)){
            header('Location: home.php'); exit;
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
