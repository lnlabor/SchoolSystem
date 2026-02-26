<?php
if(session_status() === PHP_SESSION_NONE) session_start();
// clear session and redirect to login
session_unset();
session_destroy();
header('Location: login.php'); exit;
