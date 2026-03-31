<?php

namespace App\Models;

use App\Core\Database;

/**
 * Subject Model Class
 * 
 * Handles subject-related database operations:
 * - CRUD operations
 * - Validation helpers
 */
class Subject
{
    private \mysqli $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    /**
     * Get all subjects
     * 
     * @return \mysqli_result|bool
     */
    public function getAll(): \mysqli_result|bool
    {
        return $this->conn->query(
            "SELECT subject_id AS id, code, title, unit FROM subject ORDER BY subject_id ASC"
        );
    }

    /**
     * Get subject by ID
     * 
     * @param int $id
     * @return \mysqli_result|bool
     */
    public function getById(int $id): \mysqli_result|bool
    {
        $stmt = $this->conn->prepare(
            "SELECT subject_id AS id, code, title, unit FROM subject WHERE subject_id = ?"
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    /**
     * Create new subject
     * 
     * @param string $code
     * @param string $title
     * @param float $unit
     * @return bool
     */
    public function create(string $code, string $title, float $unit): bool
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO subject (code, title, unit) VALUES (?, ?, ?)"
        );
        $stmt->bind_param('ssd', $code, $title, $unit);
        return $stmt->execute();
    }

    /**
     * Update subject
     * 
     * @param int $id
     * @param string $code
     * @param string $title
     * @param float $unit
     * @return bool
     */
    public function update(int $id, string $code, string $title, float $unit): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE subject SET code = ?, title = ?, unit = ? WHERE subject_id = ?"
        );
        $stmt->bind_param('ssdi', $code, $title, $unit, $id);
        return $stmt->execute();
    }

    /**
     * Delete subject
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM subject WHERE subject_id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    /**
     * Check if subject code exists
     * 
     * @param string $code
     * @param int|null $excludeId
     * @return bool
     */
    public function codeExists(string $code, ?int $excludeId = null): bool
    {
        if ($excludeId !== null) {
            $stmt = $this->conn->prepare(
                "SELECT subject_id FROM subject WHERE code = ? AND subject_id != ? LIMIT 1"
            );
            $stmt->bind_param('si', $code, $excludeId);
        } else {
            $stmt = $this->conn->prepare(
                "SELECT subject_id FROM subject WHERE code = ? LIMIT 1"
            );
            $stmt->bind_param('s', $code);
        }
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    /**
     * Validate unit value
     * 
     * @param mixed $unit
     * @return bool
     */
    public static function validateUnit(mixed $unit): bool
    {
        return is_numeric($unit) && floatval($unit) > 0 && floatval($unit) <= 10;
    }
}
