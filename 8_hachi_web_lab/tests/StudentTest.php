<?php
use PHPUnit\Framework\TestCase;

// Исправленный путь для работы внутри контейнера 
require_once __DIR__ . '/../Student.php'; 

class StudentTest extends TestCase
{
    // Unit-тест с Mock (остается без изменений, так как не зависит от БД)
    public function testAddStudentWithMock()
    {
        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);

        $pdoMock->expects($this->once())->method('prepare')->willReturn($stmtMock);
        $stmtMock->expects($this->once())->method('execute')->willReturn(true);
        $pdoMock->method('exec')->willReturn(0);

        $student = new Student($pdoMock);
        // Передаем: Имя, Возраст, Факультет, Согласие(1), Форма обучения
        $student->add("Алексей", 20, "ИТ", 1, "Очная");
    }

    // Интеграционный тест (Исправлено для Docker)
    public function testIntegrationRealDatabase()
    {
        // Пытаемся прочитать конфиг из папки www (в контейнере это /var/www/html)
        $envFile = __DIR__ . '/../.env.test';
        
        if (file_exists($envFile)) {
            $env = parse_ini_file($envFile);
            $host = $env['DB_HOST'] ?? 'db'; // Используем 'db' из конфига 
        } else {
            // Резервный вариант, если файл не найден
            $host = 'db'; 
            $env = [
                'DB_USER' => 'lab5_user', 
                'DB_PASSWORD' => 'lab5_pass', 
                'DB_NAME' => 'test_db'
            ];
        }

        // ВАЖНО: host=db, так как это имя сервиса в docker-compose 
        $dsn = "mysql:host=$host;charset=utf8mb4";

        try {
            // Сразу добавляем имя базы в DSN
            $dsn = "mysql:host=$host;dbname={$env['DB_NAME']};charset=utf8mb4";
            
            $pdo = new PDO($dsn, $env['DB_USER'], $env['DB_PASSWORD']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Убираем CREATE DATABASE и USE, так как база уже в DSN
            // Оставляем только очистку таблицы перед тестом
            $pdo->exec("DROP TABLE IF EXISTS students");

            $student = new Student($pdo);
            $student->add("Интеграционный Тест", 22, "Экономика", 1, "Заочная");

            // Проверки
            $this->assertEquals(1, $student->getTotalCount());
            
            $all = $student->getAll();
            $this->assertEquals("Интеграционный Тест", $all[0]['name']);
            
        } catch (PDOException $e) {
            $this->fail("Ошибка интеграционного теста: " . $e->getMessage());
        }
    }
}