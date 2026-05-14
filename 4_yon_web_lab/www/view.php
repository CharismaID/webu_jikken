<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Все заказы - ЛР 3</title>
</head>
<body>
    <h2>Все сохранённые заказы:</h2>

    <ul>
        <?php
        if (file_exists("data.txt")) {
            $lines = file("data.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            foreach ($lines as $line) {
                list($name, $email, $qty, $food, $sauce, $delivery) = explode(";", $line);

                echo "<li><b>$name</b> ($email) заказал(а): $food ($qty шт.). Соус: $sauce. Способ: $delivery</li>";
            }
        } else {
            echo "<li>Заказов пока нет. Файл пуст.</li>";
        }
        ?>
    </ul>

    <hr>
    <a href="index.php">На главную</a> |
    <a href="form.html">Оформить новый заказ</a>
</body>
</html>