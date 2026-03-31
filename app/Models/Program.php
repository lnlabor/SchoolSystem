<?php

namespace App\Models;

use App\Core\Database;

/**
 * Program Model Class
 * 
 * Handles program-related database operations:
 * - CRUD operations
 * - Validation helpers
 */
class Program
{
    private \mysqli $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    /**
     * Get all programs
     * 
     * @return \mysqli_result|bool
     */
    public function getAll(): \mysqli_result|bool
    {
        return $this->conn->query(
            "SELECT program_id AS id, code, title, years FROM program ORDER BY program_id ASC"
        );
    }

    /**
     * Get program by ID
     * 
     * @param int $id
     * @return \mysqli_result|bool
     */
    public function getById(int $id): \mysqli_result|bool
    {
        $stmt = $this->conn->prepare(
            "SELECT program_id AS id, code, title, years FROM program WHERE program_id = ?"
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    /**
     * Create new program
     * 
     * @param string $code
     * @param string $title
     * @param int $years
     * @return bool
     */
    public function create(string $code, string $title, int $years): bool
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO program (code, title, years) VALUES (?, ?, ?)"
        );
        $stmt->bind_param('ssi', $code, $title, $years);
        return $stmt->execute();
    }

    /**
     * Update program
     * 
     * @param int $id
     * @param string $code
     * @param string $title
     * @param int $years
     * @return bool
     */
    public function update(int $id, string $code, string $title, int $years): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE program SET code = ?, title = ?, years = ? WHERE program_id = ?"
        );
        $stmt->bind_param('ssii', $code, $title, $years, $id);
        return $stmt->execute();
    }

    /**
     * Delete program
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM program WHERE program_id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    /**
     * Check if program code exists
     * 
     * @param string $code
     * @param int|null $excludeId
     * @return bool
     */
    public function codeExists(string $code, ?int $excludeId = null): bool
    {
        if ($excludeId !== null) {
            $stmt = $this->conn->prepare(
                "SELECT program_id FROM program WHERE code = ? AND program_id != ? LIMIT 1"
            );
            $stmt->bind_param('si', $code, $excludeId);
        } else {
            $stmt = $this->conn->prepare(
                "SELECT program_id FROM program WHERE code = ? LIMIT 1"
            );
            $stmt->bind_param('s', $code);
        }
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    /**
     * Validate years value
     * 
     * @param mixed $years
     * @return bool
     */
    public static function validateYears(mixed $years): bool
    {
        return is_numeric($years) && intval($years) >= 1 && intval($years) <= 6;
    }
}
