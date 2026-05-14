<?php
session_start();
require 'vendor/autoload.php';
require 'QueueManager.php';

$q = new QueueManager();

$studentData = [
    'name'       => htmlspecialchars($_POST['name'] ?? 'Аноним'),
    'age'        => intval($_POST['age'] ?? 18),
    'faculty'    => htmlspecialchars($_POST['faculty'] ?? ''),
    'agreed'     => isset($_POST['agreed']) ? 1 : 0,
    'study_form' => htmlspecialchars($_POST['study_form'] ?? 'Очная'),
    'timestamp'  => date('Y-m-d H:i:s')
];

$q->publishToKafka($studentData);

$_SESSION['success_msg'] = "Заявка студента отправлена в очередь!";
header("Location: index.php");
exit();