<?php
if(session_status() === PHP_SESSION_NONE) session_start();
include_once __DIR__ . '/db.php';

function current_user(){
    if(!isset($_SESSION['user_id'])) return null;
    return [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
        'account_type' => $_SESSION['account_type']
    ];
}

function require_login(){
    if(!isset($_SESSION['user_id'])){
        header('Location: login.php'); exit;
    }
}

function require_admin(){
    require_login();
    if(($_SESSION['account_type'] ?? '') !== 'admin'){
        $_SESSION['error'] = 'Access denied';
        header('Location: home.php'); exit;
    }
}

function require_role(array $roles){
    require_login();
    if(!in_array(($_SESSION['account_type'] ?? ''), $roles, true)){
        $_SESSION['error'] = 'Access denied';
        header('Location: home.php'); exit;
    }
}

function flash_message(){
    $out = '';
    if(!empty($_SESSION['error'])){
        $out .= '<div class="error">'.htmlspecialchars($_SESSION['error']).'</div>';
        unset($_SESSION['error']);
    }
    if(!empty($_SESSION['success'])){
        $out .= '<div class="success">'.htmlspecialchars($_SESSION['success']).'</div>';
        unset($_SESSION['success']);
    }
    return $out;
}

?>
