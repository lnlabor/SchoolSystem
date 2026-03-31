<?php

namespace App\Models;

use App\Core\Database;

/**
 * User Model Class
 * 
 * Handles user-related database operations:
 * - CRUD operations
 * - Password management
 * - Validation helpers
 */
class User
{
    private \mysqli $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    /**
     * Get all users
     * 
     * @return \mysqli_result|bool
     */
    public function getAll(): \mysqli_result|bool
    {
        return $this->conn->query(
            "SELECT id, username, account_type, created_on, updated_on 
             FROM users ORDER BY id ASC"
        );
    }

    /**
     * Get user by ID
     * 
     * @param int $id
     * @return \mysqli_result|bool
     */
    public function getById(int $id): \mysqli_result|bool
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    /**
     * Create new user
     * 
     * @param string $username
     * @param string $password
     * @param string $accountType
     * @return bool
     */
    public function create(string $username, string $password, string $accountType): bool
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare(
            "INSERT INTO users (username, password, account_type) VALUES (?, ?, ?)"
        );
        $stmt->bind_param('sss', $username, $hashedPassword, $accountType);
        return $stmt->execute();
    }

    /**
     * Update user information
     * 
     * @param int $id
     * @param string $username
     * @param string $accountType
     * @return bool
     */
    public function update(int $id, string $username, string $accountType): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE users SET username = ?, account_type = ?, updated_on = NOW() WHERE id = ?"
        );
        $stmt->bind_param('ssi', $username, $accountType, $id);
        return $stmt->execute();
    }

    /**
     * Change user password
     * 
     * @param int $id
     * @param string $newPassword
     * @return bool
     */
    public function changePassword(int $id, string $newPassword): bool
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare(
            "UPDATE users SET password = ?, updated_on = NOW() WHERE id = ?"
        );
        $stmt->bind_param('si', $hashedPassword, $id);
        return $stmt->execute();
    }

    /**
     * Verify password for user
     * 
     * @param int $id
     * @param string $password
     * @return bool
     */
    public function verifyPassword(int $id, string $password): bool
    {
        $stmt = $this->conn->prepare("SELECT password FROM users WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            return password_verify($password, $user['password']);
        }

        return false;
    }

    /**
     * Delete user
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    /**
     * Check if username exists
     * 
     * @param string $username
     * @param int|null $excludeId
     * @return bool
     */
    public function usernameExists(string $username, ?int $excludeId = null): bool
    {
        if ($excludeId !== null) {
            $stmt = $this->conn->prepare(
                "SELECT id FROM users WHERE username = ? AND id != ? LIMIT 1"
            );
            $stmt->bind_param('si', $username, $excludeId);
        } else {
            $stmt = $this->conn->prepare(
                "SELECT id FROM users WHERE username = ? LIMIT 1"
            );
            $stmt->bind_param('s', $username);
        }
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }
}
