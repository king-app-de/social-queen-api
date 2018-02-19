<?php

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

    public function __call($name, $parameters = [])
    {
        $parameters[1]['headers'] = $this->wrap(isset($parameters[1]['headers']) ? $parameters[1]['headers'] : ['clientKey', 'deviceSecret', 'deviceKey', 'apiVersion']);
        return $this->client->$name($parameters[0], $parameters[1]);
    }

    private function wrap(array $headers)
    {
        $result = [];
        foreach ($headers as $header) {
            $result["x-$header"] = $this->client->$header;
        }
        return $result;
    }
}
