<?php
/**
 * Authentication Class
 */
class Auth {
    
    public static function startSession() {
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function currentUser() {
        if(!isset($_SESSION['user_id'])) return null;
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'account_type' => $_SESSION['account_type']
        ];
    }

    public static function requireLogin() {
        if(!isset($_SESSION['user_id'])){
            header('Location: login.php'); exit;
        }
    }

    public static function requireAdmin() {
        self::requireLogin();
        if(($_SESSION['account_type'] ?? '') !== 'admin'){
            $_SESSION['error'] = 'Access denied';
            header('Location: home.php'); exit;
        }
    }

    public static function requireRole(array $roles) {
        self::requireLogin();
        if(!in_array(($_SESSION['account_type'] ?? ''), $roles, true)){
            $_SESSION['error'] = 'Access denied';
            header('Location: home.php'); exit;
        }
    }

    public static function flashMessage() {
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

    public static function login($username, $password) {
        $conn = Database::getInstance()->getConnection();
        $stmt = $conn->prepare('SELECT id, username, password, account_type FROM users WHERE username = ? LIMIT 1');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $res = $stmt->get_result();
        
        if($res && $res->num_rows === 1){
            $u = $res->fetch_assoc();
            if(password_verify($password, $u['password'])){
                session_regenerate_id(true);
                $_SESSION['user_id'] = $u['id'];
                $_SESSION['username'] = $u['username'];
                $_SESSION['account_type'] = $u['account_type'];
                return true;
            }
        }
        return false;
    }

    public static function logout() {
        session_destroy();
    }

    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}
?>
