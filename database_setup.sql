-- SQL schema for School database
CREATE DATABASE IF NOT EXISTS school DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE school;

CREATE TABLE IF NOT EXISTS subject (
  subject_id INT AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(50) NOT NULL,
  title VARCHAR(255) NOT NULL,
  unit INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS program (
  program_id INT AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(50) NOT NULL,
  title VARCHAR(255) NOT NULL,
  years INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  account_type ENUM('admin','staff','teacher','student') NOT NULL DEFAULT 'student',
  created_on DATETIME DEFAULT CURRENT_TIMESTAMP,
  created_by INT DEFAULT 0,
  updated_on DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  updated_by INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed: default admin account (username: admin, password: password)
INSERT INTO users (username, password, account_type, created_on, created_by, updated_on, updated_by) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW(), 0, NOW(), 0);

-- Optional sample data
INSERT INTO subject (code, title, unit) VALUES
('MATH101','Mathematics I',3),
('ENG101','English Composition',3);

INSERT INTO program (code, title, years) VALUES
('BSCS','Bachelor of Science in Computer Science',4),
('BSED','Bachelor of Secondary Education',4);
