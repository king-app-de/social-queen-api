<?php declare(strict_types=1);

namespace KingApp\SocialQueenApi;

class Client
{
    protected $guzzle;
    protected $credentials;
    protected $url = 'https://socialqueen.com/api/mobile/';

    public function __construct(?\GuzzleHttp\Client $guzzle = null)
    {
        $this->guzzle = $guzzle ?: new \GuzzleHttp\Client(['base_uri' => $this->url]);
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

    public function setBaseUrl(string $url): self
    {
        $this->guzzle = new \GuzzleHttp\Client(['base_uri' => $url]);
        $this->url = $url;
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

    public function news(): Client\News
    {
        return new Client\News($this);
    }

    public function __call(string $name, ?array $parameters = [])
    {
        return json_decode($this->guzzle->$name(...$parameters)->getBody()->getContents());
    }
}
