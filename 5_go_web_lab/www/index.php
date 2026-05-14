<?php
session_start();
require_once 'UserInfo.php';
$info = UserInfo::getInfo();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Главная страница - Регистрация студентов</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; margin: 20px; }
        .info-block { background: #f4f4f4; padding: 10px; border-radius: 5px; margin: 15px 0; }
        .error-block { background-color: #ffcccc; border: 1px solid red; padding: 10px; margin-bottom: 20px; }
        .success-block { background-color: #e2f0d9; border: 1px solid #70ad47; padding: 10px; margin-bottom: 20px; }
    </style>
</head>
<body>

    <h2>Система регистрации студентов</h2>

    <?php if (isset($_SESSION['errors'])): ?>
        <div class="error-block">
            <ul style="color:red; margin: 0;">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['student_name'])): ?>
        <div class="success-block">
            <h3>Последний зарегистрированный профиль:</h3>
            <ul>
                <li><b>ФИО:</b> <?= htmlspecialchars($_SESSION['student_name']) ?></li>
                <li><b>Возраст:</b> <?= $_SESSION['age'] ?></li>
                <li><b>Факультет:</b> <?= htmlspecialchars($_SESSION['faculty']) ?></li>
                <li><b>Форма обучения:</b> <?= htmlspecialchars($_SESSION['study_form']) ?></li>
            </ul>
        </div>
    <?php else: ?>
        <p>Новых регистраций в этой сессии пока нет.</p>
    <?php endif; ?>

    <div class="info-block">
        <h4>Информация о визите:</h4>
        <p>
            IP: <?= $info['ip'] ?><br>
            Браузер: <?= $info['user_agent'] ?><br>
            Текущее время: <?= $info['time'] ?>
        </p>
    </div>

    <nav>
        <a href="form.html">Заполнить анкету студента</a> |
        <a href="view.php">Посмотреть список всех студентов</a>
    </nav>

    <hr>
    <h3>Справочная информация из API:</h3>
    <div id="api-data">
        <p>Данные загружаются...</p>
    </div>

</body>
</html>