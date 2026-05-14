# Лабораторная работа №6: Нереляционные базы данных (Redis, Elasticsearch, ClickHouse)

## 👩‍💻 Автор
**ФИО:** Антоненко Владислав Васильевич  
**Группа:** ПМИ-1

---

## 📌 Описание задания
В данной лабораторной работе изучены принципы работы с современными нереляционными СУБД (NoSQL) и реализовано взаимодействие с ними через HTTP API и специализированные клиенты на PHP:
* **Redis (Key-Value):** Использование библиотеки `predis/predis` для сохранения и получения данных по ключу.
* **Elasticsearch (Document-oriented / Search Engine):** Использование HTTP-клиента `Guzzle` для индексации JSON-документов и полнотекстового поиска по ним. В конфигурации Docker заданы ограничения по памяти (`ES_JAVA_OPTS`) для стабильной работы ноды.
* **ClickHouse (Column-oriented):** Использование `Guzzle` для отправки сырых SQL-запросов (создание таблиц `MergeTree`, вставка данных, агрегация).
* Настроена автозагрузка классов по стандарту **PSR-4** с помощью Composer.
* **Тематическое задание:** Реализовано хранение и управление данными пользователей (User Profiles) в базе данных Redis.

Результат работы скриптов доступен по адресу: http://localhost:8080

---

## ⚙️ Как запустить проект

1. Клонировать репозиторий:
   ```bash
   git clone https://github.com/CharismaID/webu_jikken.git
   cd webu_jikken
Запустить контейнеры:
```bash
docker-compose up -d --build
docker exec -it lab6_php composer update
docker exec -it lab6_php composer dump-autoload
```
Открыть в браузере:
```http://localhost:8080```
📂 Содержимое проекта

```docker-compose.yml``` — конфигурация сервисов (PHP-Apache, Redis, Elasticsearch, ClickHouse).

```Dockerfile``` — сборка образа PHP с установкой расширений pdo_mysql и redis.

```www/composer.json``` — зависимости проекта (guzzlehttp/guzzle, predis/predis) и настройки PSR-4.

```www/index.php``` — точка входа: настройка сессий Redis и вызов методов для работы со всеми тремя БД.

```www/Helpers/ClientFactory.php``` — фабрика для удобного создания HTTP-клиентов Guzzle.

```www/RedisExample.php``` — класс для работы с кэшем Redis.

```www/ElasticExample.php``` — класс для REST API запросов к Elasticsearch (индексация и поиск).

```www/ClickhouseExample.php``` — класс для REST API запросов к ClickHouse (аналитические SQL-запросы).


✅ Результат
Сервер в Docker успешно запущен, Nginx отдаёт мою HTML-страницу.
