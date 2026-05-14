<?php
namespace App;

use Predis\Client;

class RedisExample {
    private $client;

    public function __construct() {
        // Указываем хост 'redis' для работы внутри Docker-сети
        $this->client = new Client([
            'scheme' => 'tcp',
            'host'   => 'redis',
            'port'   => 6379,
        ]);
    }

    public function setValue($key, $value) {
        $this->client->set($key, $value);
    }

    public function getValue($key) {
        return $this->client->get($key);
    }
}