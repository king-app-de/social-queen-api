<?php declare(strict_types=1);

namespace KingApp\SocialQueenApi\Client;

class Media extends Base
{
    protected $path;
    protected $url;
    protected $uuid;
    protected $hardLink;

    public function list(): array
    {
        return $this->get('posts');
    }

    public function setPath(string $filePath): self
    {
        $this->path = $filePath;
        return $this;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function setRectangle(array $rectangle): self
    {
        foreach (['x', 'y', 'w', 'h'] as $name) {
            $this->data['rectangle'] = (int)$rectangle[$name];
        }
    }

    public function setLabel(string $label): self
    {
        $this->data['label'] = $label;
        return $this;
    }

    public function getHardLink(): string
    {
        return $this->hardLink;
    }

    public function toArray(): array
    {
        $params = $this->data;
        $params['uuid'] = $this->uuid;
        $params['hardLink'] = $this->hardLink;
        return $params;
    }

    public function create(): self
    {
        if (!$this->uuid) {
            $result = $this->post('media', ['multipart' => [[
                'name' => 'fileUpload',
                'contents' => fopen($this->path, 'r'),
            ]]]);
            $this->uuid = $result->uuid;
            $this->hardLink = $result->hardLink;
        }
        return $this;
    }
}
