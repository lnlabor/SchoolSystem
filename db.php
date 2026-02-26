<?php
// Database connection - update credentials if needed
$db_host = '127.0.0.1';
$db_user = 'root';
$db_pass = '';
$db_name = 'school';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die('Database connection error: ' . $conn->connect_error);
}
$conn->set_charset('utf8mb4');

?>
