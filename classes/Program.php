<?php
/**
 * Program Model Class
 */
class Program {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $result = $this->conn->query("SELECT program_id AS id, code, title, years FROM program ORDER BY program_id ASC");
        return $result;
    }

    public function getById($program_id) {
        $stmt = $this->conn->prepare("SELECT * FROM program WHERE program_id = ?");
        $stmt->bind_param('i', $program_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function create($code, $title, $years) {
        $y = intval($years);
        $stmt = $this->conn->prepare("INSERT INTO program (code, title, years) VALUES (?, ?, ?)");
        $stmt->bind_param('ssi', $code, $title, $y);
        return $stmt->execute();
    }

    public function update($program_id, $code, $title, $years) {
        $y = intval($years);
        $stmt = $this->conn->prepare("UPDATE program SET code = ?, title = ?, years = ? WHERE program_id = ?");
        $stmt->bind_param('ssii', $code, $title, $y, $program_id);
        return $stmt->execute();
    }

    public function delete($program_id) {
        $stmt = $this->conn->prepare("DELETE FROM program WHERE program_id = ?");
        $stmt->bind_param('i', $program_id);
        return $stmt->execute();
    }

    public function codeExists($code, $excludeId = null) {
        if ($excludeId !== null) {
            $stmt = $this->conn->prepare("SELECT program_id FROM program WHERE code = ? AND program_id != ? LIMIT 1");
            $stmt->bind_param('si', $code, $excludeId);
        } else {
            $stmt = $this->conn->prepare("SELECT program_id FROM program WHERE code = ? LIMIT 1");
            $stmt->bind_param('s', $code);
        }
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function validateYears($years) {
        return is_numeric($years) && intval($years) >= 1 && intval($years) <= 6;
    }
}
?>
