<?php
session_start();

$customer_name = htmlspecialchars($_POST['customer_name'] ?? '');
$email         = htmlspecialchars($_POST['email'] ?? '');
$quantity      = htmlspecialchars($_POST['quantity'] ?? 1);
$food          = htmlspecialchars($_POST['food'] ?? '');
$extra_sauce   = htmlspecialchars($_POST['extra_sauce'] ?? 'Нет');
$delivery      = htmlspecialchars($_POST['delivery'] ?? '');

$errors = [];
if (empty($customer_name)) {
    $errors[] = "Ошибка: Имя не может быть пустым.";
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Ошибка: Некорректный формат email.";
}
if ($quantity < 1) {
    $errors[] = "Ошибка: Количество порций не может быть меньше 1.";
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: index.php");
    exit();
}

$_SESSION['customer_name'] = $customer_name;
$_SESSION['email']         = $email;
$_SESSION['quantity']      = $quantity;
$_SESSION['food']          = $food;
$_SESSION['extra_sauce']   = $extra_sauce;
$_SESSION['delivery']      = $delivery;

$line = $customer_name . ";" . $email . ";" . $quantity . ";" . $food . ";" . $extra_sauce . ";" . $delivery . "\n";
file_put_contents("data.txt", $line, FILE_APPEND);

require_once 'ApiClient.php';
$api = new ApiClient();
$url = 'https://api.hh.ru/areas'; // API территорий для поиска мест учебы/работы
$cacheFile = 'api_cache.json';
$cacheTtl = 300;

if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTtl)) {
    $cachedData = json_decode(file_get_contents($cacheFile), true);
    $_SESSION['api_data'] = $cachedData;
} else {
    $apiData = $api->request($url);

    if (!isset($apiData['error'])) {
        file_put_contents($cacheFile, json_encode($apiData, JSON_UNESCAPED_UNICODE));
    }
    $_SESSION['api_data'] = $apiData;
}

setcookie("last_submission", date('Y-m-d H:i:s'), time() + 3600, "/");

header("Location: index.php");
exit();