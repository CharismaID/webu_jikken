<?php
session_start();
require_once 'UserInfo.php';
$info = UserInfo::getInfo();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация студента - ЛР 4</title>
</head>
<body style="font-family: sans-serif; line-height: 1.5; max-width: 800px; margin: 0 auto; padding: 20px;">

    <h2>Личный кабинет абитуриента</h2>

    <?php if (isset($_SESSION['errors'])): ?>
        <div style="background-color: #ffcccc; border: 1px solid red; padding: 10px; margin-bottom: 20px;">
            <ul style="color:red; margin: 0;">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['customer_name'])): ?>
        <div style="background-color: #e7f3fe; border-left: 6px solid #2196F3; padding: 15px; margin-bottom: 20px;">
            <h3>Данные вашей заявки:</h3>
            <ul style="list-style: none; padding: 0;">
                <li><b>ФИО студента:</b> <?= $_SESSION['customer_name'] ?></li>
                <li><b>Контактный Email:</b> <?= $_SESSION['email'] ?></li>
                <li><b>Выбранный курс:</b> <?= $_SESSION['quantity'] ?></li>
                <li><b>Направление:</b> <?= $_SESSION['food'] ?></li>
                <li><b>Общежитие:</b> <?= $_SESSION['extra_sauce'] ?></li>
                <li><b>Форма обучения:</b> <?= $_SESSION['delivery'] ?></li>
            </ul>
        </div>
    <?php else: ?>
        <p>Вы еще не заполнили анкету регистрации. Данных в сессии нет.</p>
    <?php endif; ?>

    <hr>
    <h3>Информация о сессии:</h3>
    <ul style="font-size: 0.9em; color: #555;">
        <?php foreach ($info as $key => $val): ?>
            <li><b><?= htmlspecialchars($key) ?>:</b> <?= htmlspecialchars($val) ?></li>
        <?php endforeach; ?>

        <?php if (isset($_COOKIE['last_submission'])): ?>
            <li><b>Последняя активность:</b> <?= htmlspecialchars($_COOKIE['last_submission']) ?></li>
        <?php endif; ?>
    </ul>

    <hr>
    <h3>Доступные регионы для поступления (из API HH):</h3>

    <button id="refreshBtn" style="background-color: #4CAF50; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; margin-bottom: 15px;">
        Обновить список регионов (Fetch)
    </button>

    <div id="categories-container" style="background: #f9f9f9; border: 1px solid #ddd; padding: 15px; border-radius: 4px;">
        <?php if (isset($_SESSION['api_data']) && is_array($_SESSION['api_data']) && !isset($_SESSION['api_data']['error'])): ?>
            <p>Ниже представлены страны и крупнейшие регионы:</p>
            <ul style="column-count: 2;">
                <?php 
                // Выводим первые 12 элементов для компактности
                $areas = array_slice($_SESSION['api_data'], 0, 12);
                foreach ($areas as $area): ?>
                    <li style="margin-bottom: 5px;"><?= htmlspecialchars($area['name']) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php elseif (isset($_SESSION['api_data']['error'])): ?>
            <p style="color: red;">Ошибка API: <?= htmlspecialchars($_SESSION['api_data']['error']) ?></p>
        <?php else: ?>
            <p>Список регионов загрузится автоматически после первой отправки формы.</p>
        <?php endif; ?>
    </div>

    <script>
        document.getElementById('refreshBtn').addEventListener('click', function() {
            const container = document.getElementById('categories-container');
            container.innerHTML = '<p>🛠 Загрузка свежих данных через API...</p>';

            fetch('api_update.php')
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        let html = '<p>Данные успешно обновлены:</p><ul style="column-count: 2;">';
                        // API HH возвращает массив регионов. Берем первые 15.
                        const list = result.data.slice(0, 15);
                        list.forEach(area => {
                            html += `<li style="margin-bottom: 5px;">${area.name}</li>`;
                        });
                        html += '</ul>';
                        container.innerHTML = html;
                    } else {
                        container.innerHTML = `<p style="color: red;">Ошибка: ${result.error}</p>`;
                    }
                })
                .catch(error => {
                    container.innerHTML = `<p style="color: red;">Критическая ошибка сети: ${error}</p>`;
                });
        });
    </script>

    <div style="margin-top: 30px; padding-top: 10px; border-top: 1px solid #eee;">
        <a href="form.html">Заполнить анкету</a> |
        <a href="view.php">Список всех заявок</a>
    </div>

</body>
</html>