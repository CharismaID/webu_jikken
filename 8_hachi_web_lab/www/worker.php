<?php
require 'vendor/autoload.php';
require 'QueueManager.php';
require 'db.php';
require 'Student.php';

$q = new QueueManager();
$studentManager = new Student($pdo);

$q->consumeFromKafka(function($data) use ($studentManager, $q) {
    echo "📥 Регистрация студента: " . $data['name'] . "\n";
    
    // Передаем данные в метод add согласно новой структуре
    $studentManager->add(
        $data['name'],
        $data['age'],
        $data['faculty'],
        $data['agreed'],
        $data['study_form']
    );
    echo "✅ УСПЕХ: Студент сохранен в базу!\n\n";
});