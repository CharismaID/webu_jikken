<?php
session_start();

require_once 'db.php';
require_once 'Student.php'; // Подключаем переименованный класс

// Инициализируем менеджер студентов
$studentManager = new Student($pdo);

// Получаем и фильтруем данные из формы
$name       = htmlspecialchars($_POST['name'] ?? '');
$age        = intval($_POST['age'] ?? 0);
$faculty    = htmlspecialchars($_POST['faculty'] ?? '');
$agree      = isset($_POST['agree']) ? 1 : 0;
$study_form = htmlspecialchars($_POST['study_form'] ?? '');

// Валидация данных
$errors = [];
if (empty($name)) {
    $errors[] = "Ошибка: ФИО не может быть пустым.";
}
if ($age < 16) {
    $errors[] = "Ошибка: Возраст студента должен быть не менее 16 лет.";
}
if (empty($faculty)) {
    $errors[] = "Ошибка: Выберите факультет.";
}

// Если есть ошибки, возвращаем на главную и показываем их
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: index.php");
    exit();
}

// Сохраняем данные в сессию (для вывода "текущего" студента на главной)
$_SESSION['student_name'] = $name;
$_SESSION['age']          = $age;
$_SESSION['faculty']      = $faculty;
$_SESSION['agree_rules']  = $agree ? 'Да' : 'Нет';
$_SESSION['study_form']   = $study_form;

// Сохраняем данные в базу данных MySQL через метод класса
$studentManager->add($name, $age, $faculty, $agree, $study_form);

// --- Работа с API (оставляем общую логику кэширования из вашего кода) ---
require_once 'ApiClient.php';
$api = new ApiClient();
// Можно оставить тот же URL или заменить на другой справочник
$url = 'https://www.themealdb.com/api/json/v1/1/categories.php';
$cacheFile = 'api_cache.json';
$cacheTtl = 300;

if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTtl)) {
    $cachedData = json_decode(file_get_contents($cacheFile), true);
    $_SESSION['api_data'] = $cachedData;
} else {
    $apiData = $api->request($url);
    if (!isset($apiData['error'])) {
        file_put_contents($cacheFile, json_encode($apiData, JSON_UNESCAPED_UNICODE));
        $_SESSION['api_data'] = $apiData;
    }
}

// Перенаправляем на главную страницу после успешной обработки
header("Location: index.php");
exit();