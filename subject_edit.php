<?php
include 'auth.php';
require_role(['admin','staff']);
include 'db.php';
$errors = [];

$subject_id = isset($_GET['subject_id']) ? intval($_GET['subject_id']) : 0;
if($subject_id <= 0){
    die('Invalid subject id.');
}

// Load existing
$stmt = $conn->prepare("SELECT * FROM subject WHERE subject_id = ?");
$stmt->bind_param('i', $subject_id);
$stmt->execute();
$res = $stmt->get_result();
if($res->num_rows===0){
    die('Subject not found.');
}
$subject = $res->fetch_assoc();

$code = $subject['code'];
$title = $subject['title'];
$unit = $subject['unit'];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $code = trim($_POST['code'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $unit = trim($_POST['unit'] ?? '');

    if($code === '') $errors[] = 'Code is required.';
    if($title === '') $errors[] = 'Title is required.';
    if($unit === '' || !is_numeric($unit) || intval($unit) <= 0) $errors[] = 'Unit must be a number greater than 0.';

    if(empty($errors)){
        $u = intval($unit);
        $up = $conn->prepare("UPDATE subject SET code = ?, title = ?, unit = ? WHERE subject_id = ?");
        $up->bind_param('ssii', $code, $title, $u, $subject_id);
        if($up->execute()){
            header('Location: subject_list.php'); exit;
        } else {
            $errors[] = 'Database error: could not update subject.';
        }
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit Subject</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="container">
        <div class="top-actions">
            <h2>Edit Subject</h2>
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
            <button class="btn" type="submit">Update</button>
        </form>

        
    </main>
</body>
</html>
