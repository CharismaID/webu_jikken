<?php
require_once 'db.php';
require_once 'Student.php'; // Подключаем класс Student (бывший Order.php)

$studentManager = new Student($pdo);

// Получаем текущий фильтр из URL
$currentFilter = $_GET['filter'] ?? null;

// Получаем список студентов с учетом фильтра
$allStudents = $studentManager->getAll($currentFilter);

// Получаем общее количество записей для статистики (Штрафное задание)
$totalCount = $studentManager->getTotalCount();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Список студентов (MySQL) - ЛР 5</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        tr:nth-child(even) { background-color: #fafafa; }
        .filter-links { 
            margin: 15px 0; 
            padding: 15px; 
            background: #eef3f7; 
            border: 1px solid #d1d9e0; 
            border-radius: 4px;
        }
        .stats { font-weight: bold; color: #2c3e50; }
        .nav-links { margin-top: 20px; }
    </style>
</head>
<body>
    <h2>Список зарегистрированных студентов</h2>

    <p class="stats">Всего студентов в базе: <?= $totalCount ?></p>

    <div class="filter-links">
        <b>Фильтрация:</b>
        <?php if ($currentFilter === 'adults'): ?>
            <a href="view.php">Показать всех</a> | <b>Только совершеннолетние (18+)</b>
        <?php else: ?>
            <b>Все студенты</b> | <a href="view.php?filter=adults">Только совершеннолетние (18+)</a>
        <?php endif; ?>
    </div>

    <?php if (count($allStudents) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Дата регистрации</th>
                    <th>ФИО</th>
                    <th>Возраст</th>
                    <th>Факультет</th>
                    <th>Форма обучения</th>
                    <th>Согласие</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($allStudents as $student): ?>
                    <tr>
                        <td><?= $student['id'] ?></td>
                        <td><?= date('d.m.Y H:i', strtotime($student['created_at'])) ?></td>
                        <td><?= htmlspecialchars($student['name']) ?></td>
                        <td><?= $student['age'] ?></td>
                        <td><?= htmlspecialchars($student['faculty']) ?></td>
                        <td><?= htmlspecialchars($student['study_form']) ?></td>
                        <td><?= $student['agree_rules'] ? 'Да' : 'Нет' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Записей не найдено. Попробуйте изменить фильтр или заполнить форму.</p>
    <?php endif; ?>

    <div class="nav-links">
        <hr>
        <a href="index.php">На главную</a> | 
        <a href="form.html">Зарегистрировать студента</a>
    </div>

</body>
</html>