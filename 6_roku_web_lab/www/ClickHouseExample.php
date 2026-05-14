<?php
namespace App;

use App\Helpers\ClientFactory;

class ClickhouseExample {
    private $client;

    public function __construct() {
        $this->client = ClientFactory::make('http://clickhouse:8123');
    }

    public function query($sql) {
        return $this->client->post('', ['body' => $sql])->getBody()->getContents();
    }
}