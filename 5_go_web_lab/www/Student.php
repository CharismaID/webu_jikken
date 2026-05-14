<?php
class Student { // Переименовали класс
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
            agree_rules TINYINT(1) DEFAULT 0,
            study_form VARCHAR(50) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";
        $this->pdo->exec($sql);
    }

    public function add($name, $age, $faculty, $agree, $form) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO students (name, age, faculty, agree_rules, study_form) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$name, $age, $faculty, $agree, $form]);
    }

    public function getAll($filter = null) {
        // Штрафное задание: фильтр "старше 18 лет"
        if ($filter === 'adults') {
            $stmt = $this->pdo->query("SELECT * FROM students WHERE age >= 18 ORDER BY created_at DESC");
        } else {
            $stmt = $this->pdo->query("SELECT * FROM students ORDER BY created_at DESC");
        }
        return $stmt->fetchAll();
    }

    public function getTotalCount() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM students");
        return $stmt->fetch()['total'];
    }
}