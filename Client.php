<?php

namespace KingApp\SocialQueenApi;

class Client
{
    protected $guzzle;
    const URL = 'https://socialqueen.com/api/mobile/';
    protected $credentials;

    public function __construct($guzzle = null)
    {
        $this->guzzle = $guzzle ?: new \GuzzleHttp\Client(['base_uri' => self::URL]);
    }

    public function __get($name)
    {
        return (string)$this->credentials[$name];
    }

    public function setCredentials($credentials)
    {
        $this->credentials = $credentials;
        return $this;
    }

    public function channels()
    {
        return new Client\Channel($this);
    }

    public function posts()
    {
        return new Client\Post($this);
    }

    public function __call($name, $parameters = [])
    {
        return json_decode($this->guzzle->$name($parameters[0], $parameters[1])->getBody()->getContents());
    }
}
