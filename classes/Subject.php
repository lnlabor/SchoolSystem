<?php
/**
 * Subject Model Class
 */
class Subject {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $result = $this->conn->query("SELECT subject_id AS id, code, title, unit FROM subject ORDER BY subject_id ASC");
        return $result;
    }

    public function getById($subject_id) {
        $stmt = $this->conn->prepare("SELECT * FROM subject WHERE subject_id = ?");
        $stmt->bind_param('i', $subject_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function create($code, $title, $unit) {
        $u = floatval($unit);
        $stmt = $this->conn->prepare("INSERT INTO subject (code, title, unit) VALUES (?, ?, ?)");
        $stmt->bind_param('ssd', $code, $title, $u);
        return $stmt->execute();
    }

    public function update($subject_id, $code, $title, $unit) {
        $u = floatval($unit);
        $stmt = $this->conn->prepare("UPDATE subject SET code = ?, title = ?, unit = ? WHERE subject_id = ?");
        $stmt->bind_param('ssdi', $code, $title, $u, $subject_id);
        return $stmt->execute();
    }

    public function delete($subject_id) {
        $stmt = $this->conn->prepare("DELETE FROM subject WHERE subject_id = ?");
        $stmt->bind_param('i', $subject_id);
        return $stmt->execute();
    }

    public function codeExists($code, $excludeId = null) {
        if ($excludeId !== null) {
            $stmt = $this->conn->prepare("SELECT subject_id FROM subject WHERE code = ? AND subject_id != ? LIMIT 1");
            $stmt->bind_param('si', $code, $excludeId);
        } else {
            $stmt = $this->conn->prepare("SELECT subject_id FROM subject WHERE code = ? LIMIT 1");
            $stmt->bind_param('s', $code);
        }
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function validateUnit($unit) {
        return is_numeric($unit) && floatval($unit) > 0 && floatval($unit) <= 10;
    }
}
?>
