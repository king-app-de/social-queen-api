<?php declare(strict_types=1);

namespace KingApp\SocialQueenApi\Client;

use KingApp\SocialQueenApi\Client;

/*
 * @method array get(string $url, ?array $params = [])
 * @method array post(string $url, ?array $params = [])
 * @method array delete(string $url, ?array $params = [])
 * @method array put(string $url, ?array $params = [])
 */

abstract class Base
{
    protected $client;
    protected $data = [];

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function __call(string $name, ?array $parameters = [])
    {
        $parameters[1]['headers'] = $this->wrap($parameters[1]['headers'] ?? ['clientKey', 'deviceSecret', 'deviceKey', 'apiVersion']);
        return $this->client->$name(...$parameters);
    }

    private function wrap(array $headers): array
    {
        $result = [];
        foreach ($headers as $header) {
            $result["x-$header"] = $this->client->$header;
        }
        return $result;
    }
}
