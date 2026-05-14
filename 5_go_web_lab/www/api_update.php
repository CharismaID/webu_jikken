<?php
session_start();
require_once 'ApiClient.php';


$api = new ApiClient();
$url = 'https://www.themealdb.com/api/json/v1/1/categories.php';
$cacheFile = 'api_cache.json';

$apiData = $api->request($url);

if (!isset($apiData['error'])) {
    file_put_contents($cacheFile, json_encode($apiData, JSON_UNESCAPED_UNICODE));
    $_SESSION['api_data'] = $apiData;

    echo json_encode(['success' => true, 'data' => $apiData['categories']]);
} else {
    echo json_encode(['success' => false, 'error' => $apiData['error']]);
}