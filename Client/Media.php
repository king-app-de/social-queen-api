<?php

namespace KingApp\SocialQueenApi\Client;

class Media extends Base
{
    protected $id;
    protected $path;

    public function series()
    {
        return $this->get('posts');
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setPath($filePath)
    {
        $this->path = $filePath;
        return $this;
    }

    public function setRectangle($rectangle)
    {
        foreach (['x', 'y', 'w', 'h'] as $name) {
            $this->data['rectangle'] = (int)$rectangle[$name];
        }
    }

    public function setLabel($label)
    {
        $this->data['label'] = $label;
        return $this;
    }

    public function toArray()
    {
        $params = $this->data;
        $params['uuid'] = $this->id ?: $this->create();
        return $params;
    }

    public function create()
    {
        return $this->id = $this->post('media', ['multipart' => [[
            'name' => 'fileUpload',
            'contents' => fopen($this->path, 'r'),
        ]]])->uuid;
    }
}
