<?php
session_start();
require_once 'vendor/autoload.php';
require_once 'QueueManager.php';

// Инициализируем менеджер очередей для получения статистики
$q = new QueueManager();
$stats = $q->getStats();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Система регистрации студентов — ЛР 8</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; margin: 20px; color: #333; }
        .stats-container { 
            background: #f4f4f4; 
            padding: 15px; 
            border: 1px solid #ddd; 
            border-radius: 8px;
            width: fit-content; 
            margin-bottom: 20px; 
        }
        .success-msg { color: #27ae60; font-weight: bold; padding: 10px; background: #e8f8f5; border-radius: 4px; }
        .nav-links a { 
            display: inline-block; 
            margin-right: 15px; 
            padding: 10px 15px; 
            background: #3498db; 
            color: white; 
            text-decoration: none; 
            border-radius: 4px; 
        }
        .nav-links a:hover { background: #2980b9; }
    </style>
</head>
<body>
    <h1>🎓 Система регистрации студентов</h1>

    <?php if (isset($_SESSION['success_msg'])): ?>
        <p class="success-msg"><?= $_SESSION['success_msg'] ?></p>
        <?php unset($_SESSION['success_msg']); ?>
    <?php endif; ?>

    <div class="stats-container">
        <h3>📊 Статистика системы (Kafka/RabbitMQ):</h3>
        <p>⏳ <b>Ожидают обработки (Kafka):</b> <?= $stats['kafka_waiting'] ?></p>
        <p>🚨 <b>Ошибки регистрации (RabbitMQ):</b> <?= $stats['rabbit_errors'] ?></p>
    </div>

    <div class="nav-links">
        <a href="form.html">Зарегистрировать студента</a>
        <a href="view.php">Список студентов</a>
    </div>

    <hr>
    <p><small>Текущая лабораторная работа: №8 (Тестирование системы)</small></p>
</body>
</html>