<?php

namespace App\Core;

/**
 * Auth Class
 * 
 * Handles authentication operations including:
 * - User login/logout
 * - Session-based authentication checks
 * - Role-based access control
 */
class Auth
{
    const ROLE_ADMIN = 'admin';
    const ROLE_STAFF = 'staff';
    const ROLE_TEACHER = 'teacher';
    const ROLE_STUDENT = 'student';

    const SESSION_USER_ID = 'user_id';
    const SESSION_USERNAME = 'username';
    const SESSION_ACCOUNT_TYPE = 'account_type';

    /**
     * Authenticate user with username and password
     * 
     * @param string $username
     * @param string $password
     * @return bool
     */
    public static function login(string $username, string $password): bool
    {
        $conn = Database::getInstance()->getConnection();
        $stmt = $conn->prepare('SELECT id, username, password, account_type FROM users WHERE username = ? LIMIT 1');
        
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                SessionManager::set(self::SESSION_USER_ID, $user['id']);
                SessionManager::set(self::SESSION_USERNAME, $user['username']);
                SessionManager::set(self::SESSION_ACCOUNT_TYPE, $user['account_type']);
                return true;
            }
        }

        return false;
    }

    /**
     * Logout current user
     */
    public static function logout(): void
    {
        SessionManager::destroy();
    }

    /**
     * Check if user is logged in
     * 
     * @return bool
     */
    public static function isLoggedIn(): bool
    {
        return SessionManager::has(self::SESSION_USER_ID);
    }

    /**
     * Get current user data
     * 
     * @return array|null
     */
    public static function currentUser(): ?array
    {
        if (!self::isLoggedIn()) {
            return null;
        }

        return [
            'id' => SessionManager::get(self::SESSION_USER_ID),
            'username' => SessionManager::get(self::SESSION_USERNAME),
            'account_type' => SessionManager::get(self::SESSION_ACCOUNT_TYPE),
        ];
    }

    /**
     * Check if current user is admin
     * 
     * @return bool
     */
    public static function isAdmin(): bool
    {
        return SessionManager::get(self::SESSION_ACCOUNT_TYPE) === self::ROLE_ADMIN;
    }

    /**
     * Check if current user has any of the given roles
     * 
     * @param array $roles
     * @return bool
     */
    public static function hasRole(array $roles): bool
    {
        $userRole = SessionManager::get(self::SESSION_ACCOUNT_TYPE);
        return in_array($userRole, $roles, true);
    }

    /**
     * Require login - redirect if not logged in
     */
    public static function requireLogin(): void
    {
        if (!self::isLoggedIn()) {
            header('Location: /public/login.php');
            exit;
        }
    }

    /**
     * Require admin role - redirect if not admin
     */
    public static function requireAdmin(): void
    {
        self::requireLogin();
        if (!self::isAdmin()) {
            SessionManager::setError('Access denied. Admin role required.');
            header('Location: /public/home.php');
            exit;
        }
    }

    /**
     * Require one of the specified roles - redirect if not authorized
     * 
     * @param array $roles
     */
    public static function requireRole(array $roles): void
    {
        self::requireLogin();
        if (!self::hasRole($roles)) {
            SessionManager::setError('Access denied. You do not have permission to access this resource.');
            header('Location: /public/home.php');
            exit;
        }
    }
}
