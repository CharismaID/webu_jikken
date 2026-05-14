<?php
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class ApiTest extends TestCase
{
    public function testMockGuzzleRequest()
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['categories' => [['strCategory' => 'Beef']]]))
        ]);

        $handlerStack = HandlerStack::create($mock);

        $client = new Client(['handler' => $handlerStack]);

        $response = $client->get('https://www.themealdb.com/api/json/v1/1/categories.php');

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('categories', $data);
        $this->assertEquals('Beef', $data['categories'][0]['strCategory']);
    }
}