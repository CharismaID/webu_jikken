<?php
session_start();
require_once 'vendor/autoload.php';
require_once 'QueueManager.php';

$q = new QueueManager();
$stats = $q->getStats();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Главная - ЛР 7 (Регистрация студентов)</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .stats-box { background: #f4f4f4; padding: 15px; border: 1px solid #ccc; width: 350px; margin-bottom: 20px; }
        .success-msg { color: green; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Система регистрации студентов (Асинхронная)</h2>

    <div class="stats-box">
        <h3>📊 Статистика очередей:</h3>
        <p>⏳ <b>В обработке (Kafka):</b> <?= $stats['kafka_waiting'] ?></p>
        <p>🚨 <b>Ошибки регистрации (RabbitMQ):</b> <?= $stats['rabbit_errors'] ?></p>
    </div>

    <?php if (isset($_SESSION['success_msg'])): ?>
        <p class="success-msg"><?= $_SESSION['success_msg'] ?></p>
        <?php unset($_SESSION['success_msg']); ?>
    <?php endif; ?>

    <nav>
        <a href="form.html">Зарегистрировать нового студента</a> |
        <a href="view.php">Список студентов</a>
    </nav>
</body>
</html>