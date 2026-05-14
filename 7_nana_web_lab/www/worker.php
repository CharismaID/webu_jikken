<?php
require 'vendor/autoload.php';
require 'QueueManager.php';
require 'db.php';
require 'Order.php';

$q = new QueueManager();
$orderManager = new Order($pdo);

echo "👷 Рабочий запущен (Kafka + RabbitMQ). Ожидаю заказы...\n";

$q->consumeFromKafka(function($data) use ($orderManager, $q) {
    echo "📥 Взят в работу заказ от: " . $data['customer_name'] . "\n";
    sleep(1); // Имитация обработки

    $isError = (rand(1, 100) <= 30);

    if ($isError) {
        echo "❌ ОШИБКА: Сбой при сохранении заказа! Отправляю в очередь ошибок RabbitMQ.\n\n";
        $q->publishToRabbitMQError($data, "Симуляция ошибки сервера БД");
    } else {
        $orderManager->add(
            $data['customer_name'],
            $data['quantity'],
            $data['food'],
            $data['extra_sauce'],
            $data['delivery']
        );
        echo "✅ УСПЕХ: Заказ сохранен в базу!\n\n";
    }
});