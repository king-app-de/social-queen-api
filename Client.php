<?php

namespace KingApp\SocialQueenApi;

class Client
{
    protected $guzzle;
    private const URL = 'https://socialqueen.com/api/mobile/';
    protected $credentials;

    public function __construct(?\GuzzleHttp\Client $guzzle = null)
    {
        $this->guzzle = $guzzle ?: new \GuzzleHttp\Client(['base_uri' => self::URL]);
    }

    public function __get(string $name): string
    {
        return (string) $this->credentials[$name];
    }

    public function setCredentials(array $credentials): self
    {
        $this->credentials = $credentials;
        return $this;
    }

    public function channels(): Client\Channel
    {
        return new Client\Channel($this);
    }

    public function posts(): Client\Post
    {
        return new Client\Post($this);
    }

    public function __call(string $name, ?array $parameters = [])
    {
        return json_decode($this->guzzle->$name(...$parameters)->getBody()->getContents());
    }
}
