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

    public function __call($name, $parameters = [])
    {
        $parameters[1]['headers'] = $this->wrap(isset($parameters[1]['headers']) ? $parameters[1]['headers'] : ['clientKey', 'deviceSecret', 'deviceKey', 'apiVersion']);
        try {
            return $this->client->$name($parameters[0], $parameters[1]);
        } catch (\Throwable $exception) {
            if ($exception->getCode() === 403) {
                $body = $exception->getResponse()->getBody()->getContents();
                throw new \Exception(json_decode($body));
            }
            if ($exception->getCode() === 401) throw new \Exception("Not authorized");
            if ($exception->getCode() === 406) {
                $body = $exception->getResponse()->getBody()->getContents();
                throw new \Exception("Data is not acceptable. Check the api version you are using. Error: " . $body);
            }
            throw new \Exception("Communication error");
        }
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
