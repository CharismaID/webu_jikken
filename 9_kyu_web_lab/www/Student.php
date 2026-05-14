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
            name VARCHAR(100) NOT NULL,
            age INT NOT NULL,
            faculty VARCHAR(100) NOT NULL,
            agreed TINYINT(1) DEFAULT 0,
            study_form VARCHAR(50) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";
        $this->pdo->exec($sql);
    }

    public function add($name, $age, $faculty, $agreed, $study_form) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO students (name, age, faculty, agreed, study_form) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$name, $age, $faculty, $agreed, $study_form]);
    }

    public function getTotalCount() {
        return $this->pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
    }

    public function getAll() {
        return $this->pdo->query("SELECT * FROM students")->fetchAll();
    }
}