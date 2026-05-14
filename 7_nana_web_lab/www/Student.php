<?php
class Student {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->createTable();
    }

    private function createTable() {
        $sql = "CREATE TABLE IF NOT EXISTS students (
            id INT AUTO_INCREMENT PRIMARY KEY,
            full_name VARCHAR(255) NOT NULL,
            age INT NOT NULL,
            faculty VARCHAR(100) NOT NULL,
            agreed TINYINT(1) DEFAULT 0,
            education_form VARCHAR(50) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";
        $this->pdo->exec($sql);
    }

    public function register($name, $age, $faculty, $agreed, $form) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO students (full_name, age, faculty, agreed, education_form) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$name, $age, $faculty, $agreed, $form]);
    }
}