<?php
session_start();
require 'vendor/autoload.php';
require 'QueueManager.php';

$q = new QueueManager();

$orderData = [
    'customer_name' => htmlspecialchars($_POST['customer_name'] ?? 'Без имени'),
    'quantity'      => intval($_POST['quantity'] ?? 1),
    'food'          => htmlspecialchars($_POST['food'] ?? ''),
    'extra_sauce'   => isset($_POST['extra_sauce']) ? 1 : 0,
    'delivery'      => htmlspecialchars($_POST['delivery'] ?? ''),
    'timestamp'     => date('Y-m-d H:i:s')
];

$q->publishToKafka($orderData);

$_SESSION['success_msg'] = "Заказ отправлен в очередь Kafka!";
header("Location: index.php");
exit();