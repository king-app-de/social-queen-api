<?php declare(strict_types=1);

namespace KingApp\SocialQueenApi;

class Client
{
    const BASE_URL = 'https://socialqueen.com/api/mobile/';

    protected $guzzle;
    protected $credentials;
    protected $baseUrl = self::BASE_URL;

    public function __construct(?\GuzzleHttp\Client $guzzle = null)
    {
        $guzzle ? $this->guzzle = $guzzle : $this->setBaseUrl(self::BASE_URL);
    }

    public function __get(string $name): string
    {
        return (string)$this->credentials[$name];
    }

    public function setCredentials(array $credentials): self
    {
        $this->credentials = $credentials;
        return $this;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function setBaseUrl(string $url): self
    {
        if (strrpos($url, '/') !== 0) $url .= '/';
        if (strpos($url, 'http') !== 0) throw new \Exception('Bad url');
        $this->guzzle = new \GuzzleHttp\Client(['base_uri' => $url]);
        $this->baseUrl = $url;
        return $this;
    }

    public function user(): Client\User
    {
        return new Client\User($this);
    }
    
    public function channels(): Client\Channel
    {
        return new Client\Channel($this);
    }

    public function posts(): Client\Post
    {
        return new Client\Post($this);
    }

    public function news(): Client\News
    {
        return new Client\News($this);
    }

    public function __call(string $name, ?array $parameters = [])
    {
        return json_decode($this->guzzle->$name(...$parameters)->getBody()->getContents());
    }
}
