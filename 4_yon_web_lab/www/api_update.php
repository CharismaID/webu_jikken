<?php
session_start();
require_once 'ApiClient.php';

$api = new ApiClient();
$url = 'https://api.hh.ru/areas'; 
$cacheFile = 'api_cache.json';

$apiData = $api->request($url);

if (!isset($apiData['error'])) {
    file_put_contents($cacheFile, json_encode($apiData, JSON_UNESCAPED_UNICODE));
    $_SESSION['api_data'] = $apiData;

    // Для 1-го варианта возвращаем список стран или регионов
    echo json_encode(['success' => true, 'data' => $apiData]);
} else {
    echo json_encode(['success' => false, 'error' => $apiData['error']]);
}