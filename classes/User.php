<?php
/**
 * User Model Class
 */
class User {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $res = $this->conn->query("SELECT id, username, account_type, created_on, updated_on FROM users ORDER BY id ASC");
        return $res;
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function create($username, $password, $account_type) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (username, password, account_type) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $username, $hashed, $account_type);
        return $stmt->execute();
    }

    public function update($id, $username, $account_type) {
        $stmt = $this->conn->prepare("UPDATE users SET username = ?, account_type = ? WHERE id = ?");
        $stmt->bind_param('ssi', $username, $account_type, $id);
        return $stmt->execute();
    }

    public function changePassword($id, $newPassword) {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param('si', $hashed, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function usernameExists($username, $excludeId = null) {
        if ($excludeId !== null) {
            $stmt = $this->conn->prepare("SELECT id FROM users WHERE username = ? AND id != ? LIMIT 1");
            $stmt->bind_param('si', $username, $excludeId);
        } else {
            $stmt = $this->conn->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
            $stmt->bind_param('s', $username);
        }
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }
}
?>
