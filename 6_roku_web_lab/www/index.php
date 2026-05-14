<?php
require 'vendor/autoload.php';

use App\RedisExample;
use App\ElasticExample;
use App\ClickhouseExample;

echo "<h1>Лабораторная работа №6 - Вариант 1 (Пользователи)</h1>";

// --- REDIS: ТЕМАТИЧЕСКОЕ ЗАДАНИЕ (Пользователи) ---
echo "<h2>1. Redis: Работа с пользователями</h2>";
$redis = new RedisExample();

// Сохраняем данные пользователя (например, в формате JSON или просто полями)
$userId = 101;
$userData = ['id' => 101, 'name' => 'John Doe', 'role' => 'admin', 'email' => 'john@example.com'];

$redis->setValue("user:$userId", json_encode($userData));
echo "Данные пользователя сохранены в Redis.<br>";

$savedUser = $redis->getValue("user:$userId");
echo "Извлеченные данные пользователя: <b>" . $savedUser . "</b><hr>";


// --- ELASTICSEARCH: КНИГИ ---
echo "<h2>2. Elasticsearch: Поиск книг</h2>";
$elastic = new ElasticExample();
// Индексируем
$elastic->indexDocument('books', 1, ['title' => '1984', 'author' => 'Orwell']);
// Ищем
echo "Результат поиска: " . $elastic->search('books', ['author' => 'Orwell']) . "<hr>";


// --- CLICKHOUSE: АНАЛИТИКА ---
echo "<h2>3. ClickHouse: Системные данные</h2>";
$click = new ClickhouseExample();
echo "Кол-во таблиц в системе: " . $click->query('SELECT count() FROM system.tables') . "<br>";

$click->query("CREATE TABLE IF NOT EXISTS logs (id UInt32, event String) ENGINE = MergeTree() ORDER BY id;");
$click->query("INSERT INTO logs (id, event) VALUES (1, 'User Login'), (2, 'View Page');");
echo "Данные из ClickHouse (logs):<br><pre>";
var_dump($click->query("SELECT * from logs;"));
echo "</pre>";