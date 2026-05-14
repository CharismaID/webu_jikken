<?php
use Kafka\Producer;
use Kafka\ProducerConfig;
use Kafka\Consumer;
use Kafka\ConsumerConfig;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class QueueManager {
    private $kafkaTopic = 'lab7_orders';
    private $rabbitQueue = 'lab7_errors';

    public function publishToKafka($data) {
        $config = ProducerConfig::getInstance();
        $config->setMetadataBrokerList('kafka:9092');

        $producer = new Producer(function() use ($data) {
            return [[
                'topic' => $this->kafkaTopic,
                'value' => json_encode($data),
                'key' => '',
            ]];
        });
        $producer->send(true);
        $this->incrementStat('kafka_sent');
    }

    public function consumeFromKafka(callable $callback) {
        $config = ConsumerConfig::getInstance();
        $config->setMetadataBrokerList('kafka:9092');
        $config->setGroupId('lab7_group');
        $config->setTopics([$this->kafkaTopic]);
        $config->setOffsetReset('earliest');

        $consumer = new Consumer();
        $consumer->start(function($topic, $part, $message) use ($callback) {
            $data = json_decode($message['message']['value'], true);
            $callback($data);
            $this->incrementStat('kafka_processed');
        });
    }

    public function publishToRabbitMQError($data, $errorMessage) {
        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare($this->rabbitQueue, false, true, false, false);

        $errorPayload = [
            'original_data' => $data,
            'error_reason' => $errorMessage,
            'failed_at' => date('Y-m-d H:i:s')
        ];

        $msg = new AMQPMessage(json_encode($errorPayload), ['delivery_mode' => 2]);
        $channel->basic_publish($msg, '', $this->rabbitQueue);

        $channel->close();
        $connection->close();
    }


    public function getStats() {
        $stats = ['kafka_waiting' => 0, 'rabbit_errors' => 0];

        $fileStats = $this->readStatsFile();
        $stats['kafka_waiting'] = max(0, $fileStats['kafka_sent'] - $fileStats['kafka_processed']);

        $rabbitApiUrl = "http://guest:guest@rabbitmq:15672/api/queues/%2f/{$this->rabbitQueue}";
        $rabbitResponse = @file_get_contents($rabbitApiUrl);
        if ($rabbitResponse) {
            $rabbitData = json_decode($rabbitResponse, true);
            $stats['rabbit_errors'] = $rabbitData['messages'] ?? 0;
        }

        return $stats;
    }

    private function readStatsFile() {
        $file = __DIR__ . '/stats.json';
        if (!file_exists($file)) return ['kafka_sent' => 0, 'kafka_processed' => 0];
        return json_decode(file_get_contents($file), true);
    }

    private function incrementStat($key) {
        $file = __DIR__ . '/stats.json';
        $stats = $this->readStatsFile();
        $stats[$key]++;
        file_put_contents($file, json_encode($stats));
    }
}