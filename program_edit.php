<?php
include 'auth.php';
require_role(['admin','staff']);
include 'db.php';
$errors = [];

$program_id = isset($_GET['program_id']) ? intval($_GET['program_id']) : 0;
if($program_id <= 0){
    die('Invalid program id.');
}

// Load existing
$stmt = $conn->prepare("SELECT * FROM program WHERE program_id = ?");
$stmt->bind_param('i', $program_id);
$stmt->execute();
$res = $stmt->get_result();
if($res->num_rows===0){
    die('Program not found.');
}
$program = $res->fetch_assoc();

$code = $program['code'];
$title = $program['title'];
$years = $program['years'];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $code = trim($_POST['code'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $years = trim($_POST['years'] ?? '');

    if($code === '') $errors[] = 'Code is required.';
    if($title === '') $errors[] = 'Title is required.';
    if($years === '' || !is_numeric($years) || intval($years) < 1 || intval($years) > 6) $errors[] = 'Years must be a number between 1 and 6.';

    if(empty($errors)){
        $y = intval($years);
        $up = $conn->prepare("UPDATE program SET code = ?, title = ?, years = ? WHERE program_id = ?");
        $up->bind_param('ssii', $code, $title, $y, $program_id);
        if($up->execute()){
            header('Location: program_list.php'); exit;
        } else {
            $errors[] = 'Database error: could not update program.';
        }
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit Program</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="container">
        <div class="top-actions">
            <h2>Edit Program</h2>
            <div>
                <a class="btn secondary" href="program_list.php">Back to List</a>
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
            <label>Years
                <input type="number" name="years" value="<?php echo htmlspecialchars($years); ?>" min="1" max="6">
            </label>
            <button class="btn" type="submit">Update</button>
        </form>

        
    </main>
</body>
</html>
