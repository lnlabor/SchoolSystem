<?php

namespace App\Core;

/**
 * Database Connection Class
 * 
 * Singleton pattern for database connection management.
 * Handles MySQLi connection with UTF-8 charset.
 */
class Database
{
    private static ?self $instance = null;
    private \mysqli $conn;

    const DB_HOST = '127.0.0.1';
    const DB_USER = 'root';
    const DB_PASS = '';
    const DB_NAME = 'school';
    const DB_CHARSET = 'utf8mb4';

    /**
     * Constructor - Private to prevent direct instantiation
     * Initializes database connection
     */
    private function __construct()
    {
        $this->conn = new \mysqli(
            self::DB_HOST,
            self::DB_USER,
            self::DB_PASS,
            self::DB_NAME
        );

        if ($this->conn->connect_error) {
            throw new \Exception('Database connection error: ' . $this->conn->connect_error);
        }

        $this->conn->set_charset(self::DB_CHARSET);
    }

    /**
     * Get singleton instance
     * 
     * @return self
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get MySQLi connection object
     * 
     * @return \mysqli
     */
    public function getConnection(): \mysqli
    {
        return $this->conn;
    }

    private function __clone() {}
    public function __wakeup() {}
}
