<?php
/**
 * Authentication and database initialization
 */

// Include database and auth classes
require_once 'classes/Database.php';
require_once 'classes/Auth.php';

// Start session
Auth::startSession();

// Get database connection
$conn = Database::getInstance()->getConnection();

// Convenience functions for existing code
function require_login() {
    Auth::requireLogin();
}

function require_admin() {
    Auth::requireAdmin();
}

function require_role($roles) {
    Auth::requireRole($roles);
}

function current_user() {
    return Auth::currentUser();
}

function is_logged_in() {
    return Auth::isLoggedIn();
}

function flash_message() {
    return Auth::flashMessage();
}
?>
