<?php declare(strict_types=1);

namespace KingApp\SocialQueenApi\Client;

class Media extends Base
{
    protected $id;
    protected $path;
    protected $url;

    public function list(): array
    {
        return $this->get('posts');
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
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

    public function toArray(): array
    {
        $params = $this->data;
        $params['uuid'] = $this->id ?: $this->create();
        return $params;
    }

    public function create(): string
    {
        return $this->id = $this->post('media', ['multipart' => [[
            'url' => $this->url,
            'name' => 'fileUpload',
            'contents' => fopen($this->path, 'r'),
        ]]])->uuid;
    }
}
