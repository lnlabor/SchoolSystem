<?php
include 'auth.php';
require_role(['admin','staff']);
include 'db.php';
$errors = [];
$code = '';
$title = '';
$unit = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $code = trim($_POST['code'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $unit = trim($_POST['unit'] ?? '');

    if($code === '') $errors[] = 'Code is required.';
    if($title === '') $errors[] = 'Title is required.';
    if($unit === '' || !is_numeric($unit) || intval($unit) <= 0) $errors[] = 'Unit must be a number greater than 0.';

    if(empty($errors)){
        $stmt = $conn->prepare("INSERT INTO subject (code, title, unit) VALUES (?, ?, ?)");
        $u = intval($unit);
        $stmt->bind_param('ssi', $code, $title, $u);
        if($stmt->execute()){
            header('Location: subject_list.php'); exit;
        } else {
            $errors[] = 'Database error: could not save subject.';
        }
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Add Subject</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="container">
        <div class="top-actions">
            <h2>Add Subject</h2>
            <div>
                <a class="btn secondary" href="subject_list.php">Back to List</a>
            </div>
        </div>

        <?php if(!empty($errors)): ?>
            <div class="error">
                <ul>
                <?php foreach($errors as $e): ?>
                    <li><?php echo htmlspecialchars($e); ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post">
            <label>Code
                <input type="text" name="code" value="<?php echo htmlspecialchars($code); ?>">
            </label>
            <label>Title
                <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>">
            </label>
            <label>Unit
                <input type="number" name="unit" value="<?php echo htmlspecialchars($unit); ?>" min="1">
            </label>
            <button class="btn" type="submit">Save</button>
        </form>

        
    </main>
</body>
</html>
